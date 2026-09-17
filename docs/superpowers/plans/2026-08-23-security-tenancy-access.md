# Security, Tenancy & Access Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make company context explicit, block cross-company resource access, and remove unsafe production access routes.

**Architecture:** A side-effect-free `User::current_company` relationship supplies company context. Authenticated workspace routes pass through `EnsureActiveCompany`, and company-owned models use scoped route binding as defense in depth. Controllers validate related IDs against the same company.

**Tech Stack:** Laravel 13, PHP 8.4, Eloquent, PHPUnit 12, MySQL 8/SQLite tests

**Spec:** `docs/superpowers/specs/2026-08-23-platform-audit-remediation-design.md`

## Global Constraints

- Preserve the current Kamo visual identity and existing public/application URLs.
- Production code must never fall back to the first company or a hard-coded company/user ID.
- Cross-company resource identifiers return `404` and cause no mutation.
- Every behavior change follows red-green-refactor.
- Git metadata is absent in this workspace; run the documented commit commands only after the repository is restored.

---

### Task 1: Side-effect-free active company context

**Files:**
- Modify: `apps/laravel/app/Models/User.php`
- Modify: `apps/laravel/app/Http/Middleware/EnsureActiveCompany.php`
- Modify: `apps/laravel/bootstrap/app.php`
- Modify: `apps/laravel/routes/web.php`
- Create: `apps/laravel/resources/views/errors/company-required.blade.php`
- Create: `apps/laravel/tests/Feature/CompanyContextTest.php`

**Interfaces:**
- Consumes: authenticated `User::companies()` relationship.
- Produces: read-only `User::current_company` attribute and `EnsureActiveCompany` HTTP guard.

- [ ] **Step 1: Write failing company-context tests**

```php
<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyContextTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_company_is_first_attached_company_without_mutating_memberships(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $user->companies()->attach($company);

        $this->assertTrue($user->current_company->is($company));
        $this->assertSame(1, $user->companies()->count());
    }

    public function test_user_without_company_gets_explicit_workspace_error(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/dashboard')
            ->assertStatus(409)
            ->assertSee('No tienes una empresa activa');

        $this->assertSame(0, $user->companies()->count());
    }
}
```

- [ ] **Step 2: Run the focused test and verify the current fallback behavior fails it**

Run: `php artisan test tests/Feature/CompanyContextTest.php`

Expected: FAIL because `current_company` attaches the first global company and the middleware currently passes every request.

- [ ] **Step 3: Implement the minimal company context and middleware**

Replace the accessor body in `User.php` with:

```php
return Attribute::make(
    get: fn () => $this->companies()->orderBy('companies.id')->first(),
);
```

Implement `EnsureActiveCompany::handle()`:

```php
if (! $request->user()?->current_company) {
    return response()->view('errors.company-required', status: 409);
}

return $next($request);
```

Register alias `company.active` in `bootstrap/app.php` and add it to the existing authenticated route group. Create a Kamo-styled error view with a logout link and no company fallback.

- [ ] **Step 4: Run the focused test and existing authentication tests**

Run: `php artisan test tests/Feature/CompanyContextTest.php tests/Feature/Auth/AuthenticationTest.php`

Expected: PASS with no memberships created as a read side effect.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/app/Models/User.php apps/laravel/app/Http/Middleware/EnsureActiveCompany.php apps/laravel/bootstrap/app.php apps/laravel/routes/web.php apps/laravel/resources/views/errors/company-required.blade.php apps/laravel/tests/Feature/CompanyContextTest.php
git commit -m "fix: require explicit active company context"
```

### Task 2: Company-scoped route binding

**Files:**
- Create: `apps/laravel/app/Models/Concerns/BelongsToCurrentCompany.php`
- Modify: `apps/laravel/app/Models/Client.php`
- Modify: `apps/laravel/app/Models/Service.php`
- Modify: `apps/laravel/app/Models/Expense.php`
- Modify: `apps/laravel/app/Models/Document.php`
- Modify: `apps/laravel/app/Models/AiMetaQuote.php`
- Create: `apps/laravel/tests/Feature/TenantRouteBindingTest.php`

**Interfaces:**
- Consumes: `request()->user()->current_company` from Task 1.
- Produces: `resolveRouteBindingQuery()` that appends `company_id = current company` for company-owned models.

- [ ] **Step 1: Write failing cross-company route tests**

```php
<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Company;
use App\Models\Document;
use App\Models\Expense;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantRouteBindingTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_owned_mutations_return_not_found_for_foreign_ids(): void
    {
        $mine = Company::factory()->create();
        $foreign = Company::factory()->create();
        $user = User::factory()->create();
        $user->companies()->attach($mine);

        $client = Client::factory()->create(['company_id' => $foreign->id]);
        $service = Service::factory()->create(['company_id' => $foreign->id]);
        $expense = Expense::factory()->create(['company_id' => $foreign->id]);
        $document = Document::factory()->create(['company_id' => $foreign->id, 'type' => 'quote']);

        $this->actingAs($user)->putJson("/clients/{$client->id}", ['name' => 'Intrusión'])->assertNotFound();
        $this->actingAs($user)->deleteJson("/services/{$service->id}")->assertNotFound();
        $this->actingAs($user)->deleteJson("/expenses/{$expense->id}")->assertNotFound();
        $this->actingAs($user)->postJson("/quotes/{$document->id}/cancel")->assertNotFound();

        $this->assertDatabaseHas('clients', ['id' => $client->id, 'company_id' => $foreign->id]);
    }
}
```

- [ ] **Step 2: Run the test and verify foreign records are currently resolved**

Run: `php artisan test tests/Feature/TenantRouteBindingTest.php`

Expected: FAIL because implicit binding currently ignores `company_id`.

- [ ] **Step 3: Add the binding concern and use it on company-owned models**

```php
<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

trait BelongsToCurrentCompany
{
    public function resolveRouteBindingQuery($query, $value, $field = null): Relation|Builder
    {
        $companyId = request()->user()?->current_company?->getKey();
        abort_if(! $companyId, 404);

        return parent::resolveRouteBindingQuery($query, $value, $field)
            ->where($this->qualifyColumn('company_id'), $companyId);
    }
}
```

Add `use BelongsToCurrentCompany;` to each listed company-owned model. For `AiMetaQuote`, retain explicit ID loading in Livewire but expose the same concern for route/controller binding.

- [ ] **Step 4: Run route-binding and existing module tests**

Run: `php artisan test tests/Feature/TenantRouteBindingTest.php tests/Feature/MetaAds`

Expected: PASS; foreign IDs return 404.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/app/Models apps/laravel/tests/Feature/TenantRouteBindingTest.php
git commit -m "fix: scope route binding to current company"
```

### Task 3: Company-scoped foreign-key validation

**Files:**
- Create: `apps/laravel/app/Support/CurrentCompany.php`
- Modify: `apps/laravel/app/Http/Controllers/ClientController.php`
- Modify: `apps/laravel/app/Http/Controllers/ServiceController.php`
- Modify: `apps/laravel/app/Http/Controllers/ExpenseController.php`
- Modify: `apps/laravel/app/Http/Controllers/QuoteController.php`
- Modify: `apps/laravel/app/Http/Controllers/BillController.php`
- Modify: `apps/laravel/app/Http/Controllers/InvoiceController.php`
- Modify: `apps/laravel/app/Http/Controllers/DashboardController.php`
- Create: `apps/laravel/tests/Feature/TenantForeignKeyValidationTest.php`

**Interfaces:**
- Consumes: `User::current_company`.
- Produces: `CurrentCompany::id(): int` and `CurrentCompany::existsRule(string $table): Exists`.

- [ ] **Step 1: Write a failing foreign-client validation test**

```php
public function test_quote_cannot_reference_another_company_client(): void
{
    $mine = Company::factory()->create();
    $foreign = Company::factory()->create();
    $user = User::factory()->create();
    $user->companies()->attach($mine);
    $client = Client::factory()->create(['company_id' => $foreign->id]);

    $this->actingAs($user)->postJson('/quotes', [
        'client_id' => $client->id,
        'date' => now()->toDateString(),
        'items' => [[
            'service_name' => 'Auditoría',
            'quantity' => 1,
            'unit_price' => 100,
        ]],
    ])->assertUnprocessable()->assertJsonValidationErrors('client_id');
}
```

- [ ] **Step 2: Run the test and verify global `exists` accepts the foreign client**

Run: `php artisan test tests/Feature/TenantForeignKeyValidationTest.php`

Expected: FAIL because `exists:clients,id` has no company predicate.

- [ ] **Step 3: Implement and inject `CurrentCompany`**

```php
final class CurrentCompany
{
    public function id(): int
    {
        return request()->user()->current_company->getKey();
    }

    public function existsRule(string $table): Exists
    {
        return Rule::exists($table, 'id')->where('company_id', $this->id());
    }
}
```

Replace every controller fallback helper with constructor/method injection of `CurrentCompany`. Use `$company->id()` in queries and `$company->existsRule('clients')` / `$company->existsRule('services')` in validation.

- [ ] **Step 4: Run tenant tests and controller feature tests**

Run: `php artisan test tests/Feature/CompanyContextTest.php tests/Feature/TenantRouteBindingTest.php tests/Feature/TenantForeignKeyValidationTest.php`

Expected: PASS.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/app/Support/CurrentCompany.php apps/laravel/app/Http/Controllers apps/laravel/tests/Feature/TenantForeignKeyValidationTest.php
git commit -m "fix: validate relations within active company"
```

### Task 4: Local-only development access

**Files:**
- Create: `apps/laravel/config/kamo.php`
- Modify: `apps/laravel/.env.example`
- Modify: `apps/laravel/routes/web.php`
- Create: `apps/laravel/tests/Feature/DevelopmentRoutesTest.php`

**Interfaces:**
- Consumes: `APP_ENV` and `KAMO_LOCAL_ACCESS_EMAIL`.
- Produces: local-only access route with configured email; no production demo routes.

- [ ] **Step 1: Write failing environment tests**

```php
public function test_development_routes_are_hidden_outside_local_environment(): void
{
    app()->detectEnvironment(fn () => 'production');

    $this->get('/entrar-kamo')->assertNotFound();
    $this->get('/react-test')->assertNotFound();
}

public function test_local_access_uses_configured_email_instead_of_fixed_id(): void
{
    $user = User::factory()->create(['email' => 'local@kamo.test']);
    config(['kamo.local_access_email' => $user->email]);

    $this->get('/entrar-kamo')->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
}
```

- [ ] **Step 2: Run the test and verify routes are globally exposed**

Run: `php artisan test tests/Feature/DevelopmentRoutesTest.php`

Expected: FAIL because both routes are unconditional and access uses ID `1`.

- [ ] **Step 3: Register routes only in local environment**

```php
if (app()->isLocal()) {
    Route::get('/entrar-kamo', function () {
        $user = User::where('email', config('kamo.local_access_email'))->firstOrFail();
        Auth::login($user);
        request()->session()->regenerate();

        return redirect()->route('dashboard');
    });

    Route::get('/react-test', fn () => Inertia::render('ReactTest', [
        'message' => 'React + Inertia.js está funcionando!',
    ]));
}
```

Set `KAMO_LOCAL_ACCESS_EMAIL=` in `.env.example`; do not commit a real user email.

- [ ] **Step 4: Run development-route and full security plan tests**

Run: `php artisan test tests/Feature/DevelopmentRoutesTest.php tests/Feature/CompanyContextTest.php tests/Feature/TenantRouteBindingTest.php tests/Feature/TenantForeignKeyValidationTest.php`

Expected: PASS.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/config/kamo.php apps/laravel/.env.example apps/laravel/routes/web.php apps/laravel/tests/Feature/DevelopmentRoutesTest.php
git commit -m "fix: restrict development access routes"
```

### Task 5: Company-owned case studies and explicit public portfolio

**Files:**
- Create: `apps/laravel/database/migrations/2026_08_23_000000_add_company_id_to_case_studies.php`
- Modify: `apps/laravel/app/Models/CaseStudy.php`
- Modify: `apps/laravel/app/Livewire/CaseStudies/Index.php`
- Modify: `apps/laravel/app/Livewire/Landing/Portfolio.php`
- Modify: `apps/laravel/config/kamo.php`
- Modify: `apps/laravel/.env.example`
- Create: `apps/laravel/tests/Feature/CaseStudyTenancyTest.php`

**Interfaces:**
- Consumes: `CurrentCompany::id()` and `KAMO_PUBLIC_COMPANY_ID`.
- Produces: company-owned case-study administration and an explicitly configured public portfolio source.

- [ ] **Step 1: Write failing Livewire tenancy tests**

```php
public function test_case_study_admin_only_lists_and_mutates_current_company_records(): void
{
    $mine = CaseStudy::create($this->studyData(['company_id' => $this->company->id]));
    $foreign = CaseStudy::create($this->studyData(['company_id' => $this->foreignCompany->id]));

    Livewire::actingAs($this->user)
        ->test(\App\Livewire\CaseStudies\Index::class)
        ->assertSee($mine->titulo)
        ->assertDontSee($foreign->titulo)
        ->call('delete', $foreign->id)
        ->assertNotFound();

    $this->assertDatabaseHas('case_studies', ['id' => $foreign->id]);
}
```

Add a public assertion that `/portafolio` shows active studies only from `config('kamo.public_company_id')`.

- [ ] **Step 2: Run the test and verify case studies are currently global**

Run: `php artisan test tests/Feature/CaseStudyTenancyTest.php`

Expected: FAIL because the table/model/component have no `company_id` scope.

- [ ] **Step 3: Add ownership and scope every case-study operation**

The migration adds nullable `company_id`, backfills existing rows to the configured/first migration-time company, then adds the foreign key and index. Add `company_id` to model fillable fields plus a `company()` relation. In the admin component, derive a locked company ID during `mount()` and add it to list, find, create, bulk update, and delete queries. In the public portfolio, query the explicit `KAMO_PUBLIC_COMPANY_ID`; return an empty portfolio when it is unset rather than leaking every tenant's records.

- [ ] **Step 4: Run migration and case-study tests**

Run: `php artisan migrate:fresh --env=testing`

Expected: exit 0.

Run: `php artisan test tests/Feature/CaseStudyTenancyTest.php`

Expected: PASS.

- [ ] **Step 5: Record the task checkpoint**

```bash
git add apps/laravel/database/migrations/2026_08_23_000000_add_company_id_to_case_studies.php apps/laravel/app/Models/CaseStudy.php apps/laravel/app/Livewire/CaseStudies/Index.php apps/laravel/app/Livewire/Landing/Portfolio.php apps/laravel/config/kamo.php apps/laravel/.env.example apps/laravel/tests/Feature/CaseStudyTenancyTest.php
git commit -m "fix: scope case studies by company"
```
