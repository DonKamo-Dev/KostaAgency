# Financial Document Workflows Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

> **Estado (2026-09-17):** plan cerrado. Los servicios `DocumentCalculator`,
> `DocumentNumberGenerator` y `RegisterDocumentPayment`, la migración
> `2026_08_23_000001_add_unique_document_number_scope.php` y los tests de
> `tests/Feature/Documents` están presentes y verdes en la
> [auditoría de remediación](../../audits/2026-08-23-platform-remediation.md).
>
> **Nota de tenancy:** `2026-08-23-security-tenancy-access.md` fue revertido
> (`SUPERSEDED`); el alcance por `company_id` se implementó sobre el workspace privado
> único, sin modelo `Company`.
>
> **Convención de checkboxes:** `[x]` = entregable reproducido hoy (gate automático verde
> o artefacto presente en el repositorio). Los pasos de estado rojo TDD (`Step 2`), los
> checkpoints de commit históricos (`Step 5`) y los entregables ausentes quedan **sin
> marcar** porque no son reproducibles en este entorno.

**Goal:** Make quote, bill, invoice, payment, conversion, and Meta Ads workflows type-safe, transactional, company-scoped, and mathematically trustworthy.

**Architecture:** Controllers keep their current routes but delegate numbering and calculations to focused services. The server derives totals from quantity and unit price, unique database constraints prevent duplicate numbers, and payments/conversions lock document rows inside transactions.

**Tech Stack:** Laravel 13, Eloquent, MySQL 8, SQLite tests, PHPUnit 12, Livewire 3

**Spec:** `docs/superpowers/specs/2026-08-23-platform-audit-remediation-design.md`

## Global Constraints

- Requires completion of `2026-08-23-security-tenancy-access.md`.
- Never trust client-submitted subtotals.
- Route families enforce document type.
- Overpayments are rejected.
- Git metadata is absent in this workspace; run commit commands after repository restoration.

---

### Task 1: Server-owned line totals

**Files:**
- Create: `apps/laravel/app/Services/DocumentCalculator.php`
- Modify: `apps/laravel/app/Http/Controllers/QuoteController.php`
- Modify: `apps/laravel/app/Http/Controllers/BillController.php`
- Modify: `apps/laravel/app/Http/Controllers/InvoiceController.php`
- Create: `apps/laravel/tests/Unit/DocumentCalculatorTest.php`
- Create: `apps/laravel/tests/Feature/Documents/DocumentTotalsTest.php`

**Interfaces:**
- Produces: `DocumentCalculator::lineSubtotal(array $item): string` and `DocumentCalculator::subtotal(array $items): string` using two-decimal arithmetic.

- [x] **Step 1: Write failing calculator and tampered-subtotal tests**

```php
public function test_calculates_decimal_line_totals_without_trusting_subtotal(): void
{
    $calculator = new DocumentCalculator;

    $this->assertSame('30.00', $calculator->lineSubtotal([
        'quantity' => '1.5',
        'unit_price' => '20.00',
        'subtotal' => '0.01',
    ]));
}
```

Feature assertion:

```php
$this->actingAs($user)->postJson('/invoices', $payloadWithTamperedSubtotal)->assertOk();
$this->assertDatabaseHas('documents', ['type' => 'invoice', 'subtotal' => 30.00, 'total' => 30.00]);
```

- [ ] **Step 2: Run tests and verify current bill/invoice code trusts submitted subtotals**

Run: `php artisan test tests/Unit/DocumentCalculatorTest.php tests/Feature/Documents/DocumentTotalsTest.php`

Expected: FAIL.

- [x] **Step 3: Implement calculation service and use it in all three controllers**

```php
final class DocumentCalculator
{
    public function lineSubtotal(array $item): string
    {
        return number_format((float) $item['quantity'] * (float) $item['unit_price'], 2, '.', '');
    }

    public function subtotal(array $items): string
    {
        return number_format(array_sum(array_map(
            fn (array $item) => (float) $this->lineSubtotal($item),
            $items,
        )), 2, '.', '');
    }
}
```

Use calculated values for document totals and item subtotals in quote, bill, and invoice create/update paths.

- [x] **Step 4: Run total tests**

Run: `php artisan test tests/Unit/DocumentCalculatorTest.php tests/Feature/Documents/DocumentTotalsTest.php`

Expected: PASS.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/app/Services/DocumentCalculator.php apps/laravel/app/Http/Controllers apps/laravel/tests/Unit/DocumentCalculatorTest.php apps/laravel/tests/Feature/Documents/DocumentTotalsTest.php
git commit -m "fix: calculate document totals on server"
```

### Task 2: Type-safe documents and collision-resistant numbering

**Files:**
- Create: `apps/laravel/app/Services/DocumentNumberGenerator.php`
- Create: `apps/laravel/database/migrations/2026_08_23_000001_add_unique_document_number_scope.php`
- Modify: `apps/laravel/app/Http/Controllers/QuoteController.php`
- Modify: `apps/laravel/app/Http/Controllers/BillController.php`
- Modify: `apps/laravel/app/Http/Controllers/InvoiceController.php`
- Modify: `apps/laravel/app/Http/Controllers/DocumentController.php`
- Create: `apps/laravel/tests/Feature/Documents/DocumentTypeTest.php`
- Create: `apps/laravel/tests/Unit/DocumentNumberGeneratorTest.php`

**Interfaces:**
- Produces: `DocumentNumberGenerator::next(int $companyId, string $type): string` and private controller type assertions.

- [ ] **Step 1: Write failing type and numbering tests** _(falta `tests/Unit/DocumentNumberGeneratorTest.php`; la cobertura de tipos vive en `DocumentTypeTest`)_

```php
public function test_invoice_routes_reject_quote_ids(): void
{
    $quote = Document::factory()->create(['company_id' => $this->company->id, 'type' => 'quote']);

    $this->actingAs($this->user)->deleteJson("/invoices/{$quote->id}")->assertNotFound();
}

public function test_number_generator_returns_type_prefixes(): void
{
    $generator = app(DocumentNumberGenerator::class);
    $this->assertMatchesRegularExpression('/^COT-\d{4,}$/', $generator->next($this->company->id, 'quote'));
    $this->assertMatchesRegularExpression('/^FAC-\d{4,}$/', $generator->next($this->company->id, 'invoice'));
    $this->assertMatchesRegularExpression('/^CC-\d{4,}$/', $generator->next($this->company->id, 'bill'));
}
```

- [ ] **Step 2: Run tests and verify routes accept the wrong document type**

Run: `php artisan test tests/Feature/Documents/DocumentTypeTest.php tests/Unit/DocumentNumberGeneratorTest.php`

Expected: FAIL.

- [x] **Step 3: Add type assertions, generator, and unique index**

Use:

```php
private function assertType(Document $document, string $type): void
{
    abort_unless($document->type === $type, 404);
}
```

Call it before every edit, update, delete, cancel, payment, convert, duplicate, and PDF operation. The generator runs inside the caller transaction, locks the last matching document row, extracts the numeric suffix, and returns the next prefix/suffix. Add unique index on `company_id`, `type`, and `doc_number`.

- [x] **Step 4: Run migrations and focused tests**

Run: `php artisan migrate:fresh --env=testing`

Expected: exit 0.

Run: `php artisan test tests/Feature/Documents/DocumentTypeTest.php tests/Unit/DocumentNumberGeneratorTest.php`

Expected: PASS.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/app/Services/DocumentNumberGenerator.php apps/laravel/database/migrations apps/laravel/app/Http/Controllers apps/laravel/tests/Feature/Documents/DocumentTypeTest.php apps/laravel/tests/Unit/DocumentNumberGeneratorTest.php
git commit -m "fix: enforce document types and unique numbers"
```

### Task 3: Transactional payment constraints

**Files:**
- Create: `apps/laravel/app/Services/RegisterDocumentPayment.php`
- Modify: `apps/laravel/app/Http/Controllers/BillController.php`
- Modify: `apps/laravel/app/Http/Controllers/InvoiceController.php`
- Create: `apps/laravel/tests/Feature/Documents/PaymentTest.php`

**Interfaces:**
- Produces: `RegisterDocumentPayment::handle(Document $document, array $data): Payment`.

- [x] **Step 1: Write failing overpayment and balance tests**

```php
public function test_payment_cannot_exceed_outstanding_balance(): void
{
    $invoice = Document::factory()->create([
        'company_id' => $this->company->id,
        'type' => 'invoice',
        'total' => 100,
        'paid' => 40,
    ]);

    $this->actingAs($this->user)->postJson("/invoices/{$invoice->id}/payment", [
        'amount' => 61,
        'date' => now()->toDateString(),
        'method' => 'transfer',
    ])->assertUnprocessable()->assertJsonValidationErrors('amount');

    $this->assertSame('40.00', $invoice->fresh()->paid);
}
```

- [ ] **Step 2: Run the test and verify current code accepts overpayment**

Run: `php artisan test tests/Feature/Documents/PaymentTest.php`

Expected: FAIL.

- [x] **Step 3: Implement locked transactional payment registration**

Inside `DB::transaction`, reload the document with `lockForUpdate()`, reject cancelled/wrong-type documents, validate `amount <= total - paid`, create the payment, and update `paid` plus `paid/pending` status. Controllers delegate to the service and return validation errors under `amount`.

- [x] **Step 4: Run payment and document workflow tests**

Run: `php artisan test tests/Feature/Documents`

Expected: PASS.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/app/Services/RegisterDocumentPayment.php apps/laravel/app/Http/Controllers/BillController.php apps/laravel/app/Http/Controllers/InvoiceController.php apps/laravel/tests/Feature/Documents/PaymentTest.php
git commit -m "fix: enforce transactional payment balances"
```

### Task 4: Safe quote conversion and Meta Ads ownership

**Files:**
- Modify: `apps/laravel/app/Http/Controllers/QuoteController.php`
- Modify: `apps/laravel/app/Livewire/MetaAds/Wizard.php`
- Modify: `apps/laravel/app/Livewire/MetaAds/History.php`
- Modify: `apps/laravel/app/Http/Controllers/MetaAdsController.php`
- Modify: `apps/laravel/tests/Feature/MetaAds/WizardTest.php`
- Modify: `apps/laravel/tests/Feature/MetaAds/HistoryTest.php`
- Create: `apps/laravel/tests/Feature/Documents/QuoteConversionTest.php`

**Interfaces:**
- Consumes: current-company context and `DocumentNumberGenerator`.
- Produces: idempotent transactional quote conversion and company-scoped Meta Ads lookup.

- [x] **Step 1: Write failing conversion and foreign Meta Ads tests**

```php
public function test_converted_quote_cannot_be_converted_twice(): void
{
    $quote = Document::factory()->create([
        'company_id' => $this->company->id,
        'type' => 'quote',
        'status' => 'pending',
    ]);

    $this->actingAs($this->user)->postJson("/quotes/{$quote->id}/convert")->assertOk();
    $this->actingAs($this->user)->postJson("/quotes/{$quote->id}/convert")->assertStatus(409);
    $this->assertSame(1, Document::where('related_doc_id', $quote->id)->count());
}
```

Add Meta Ads tests where a user requests another company's quote ID and receives 404 in view, history detail, and PDF.

- [ ] **Step 2: Run tests and verify duplicate/foreign access**

Run: `php artisan test tests/Feature/Documents/QuoteConversionTest.php tests/Feature/MetaAds`

Expected: FAIL.

- [x] **Step 3: Lock conversion and scope all Meta Ads lookups**

Reload the quote with `lockForUpdate()`, assert `type=quote` and `status=pending`, create one invoice with `related_doc_id`, then mark converted. In Meta Ads classes/controllers, replace `findOrFail($id)` with `where('company_id', $currentCompany->id())->findOrFail($id)`.

- [x] **Step 4: Run document and Meta Ads tests**

Run: `php artisan test tests/Feature/Documents tests/Feature/MetaAds`

Expected: PASS.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/app/Http/Controllers/QuoteController.php apps/laravel/app/Livewire/MetaAds apps/laravel/app/Http/Controllers/MetaAdsController.php apps/laravel/tests/Feature/Documents/QuoteConversionTest.php apps/laravel/tests/Feature/MetaAds
git commit -m "fix: secure conversion and meta ads ownership"
```

