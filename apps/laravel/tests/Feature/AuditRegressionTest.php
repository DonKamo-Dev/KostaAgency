<?php

namespace Tests\Feature;

use App\Models\CaseStudy;
use App\Models\Client;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_and_privacy_notice_are_reachable(): void
    {
        foreach (['/', '/portafolio', '/contacto', '/cv', '/privacidad'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_profile_uses_the_livewire_enabled_workspace_layout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/profile')
            ->assertOk()
            ->assertSee('/livewire/livewire');
    }

    public function test_portfolio_urls_are_normalized_to_external_https_urls(): void
    {
        $study = CaseStudy::create([
            'titulo' => 'Proyecto de prueba',
            'categoria' => 'web',
            'url_demo' => 'ejemplo.com',
        ]);

        $this->assertSame('https://ejemplo.com', $study->public_url);
        $this->get('/portafolio')->assertOk()->assertSee('href="https://ejemplo.com"', false);
    }

    public function test_dashboard_actions_open_the_corresponding_creation_flow(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('quotes/create', false)
            ->assertSee('invoices/create', false);
    }

    public function test_quote_and_invoice_creation_pages_are_reachable(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/quotes/create')->assertOk();
        $this->actingAs($user)->get('/invoices/create')->assertOk();
    }

    public function test_quote_and_invoice_store_returns_the_new_document_id(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Cliente nuevo']);

        $payload = [
            'client_id' => $client->id,
            'date' => now()->toDateString(),
            'due_date' => null,
            'notes' => '',
            'items' => [
                ['service_name' => 'Diseño web', 'quantity' => 1, 'unit_price' => 100, 'subtotal' => 100, 'service_id' => null, 'description' => ''],
            ],
        ];

        $this->actingAs($user)->postJson('/quotes', $payload)
            ->assertOk()
            ->assertJsonStructure(['message', 'id']);

        $this->actingAs($user)->postJson('/invoices', $payload)
            ->assertOk()
            ->assertJsonStructure(['message', 'id']);
    }

    public function test_invoice_show_page_contains_the_payment_section(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Cliente con factura']);
        $invoice = $this->document($client, 'invoice', 'pending', 'FAC-1002', 0);

        $this->actingAs($user)
            ->get("/invoices/{$invoice->id}")
            ->assertOk()
            ->assertSee('Registrar Pago');
    }

    public function test_quote_edit_page_is_blocked_for_non_pending_quotes(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Cliente cotizado']);
        $quote = $this->document($client, 'quote', 'converted', 'COT-1002');

        $this->actingAs($user)->get("/quotes/{$quote->id}/edit")->assertStatus(409);
    }

    public function test_invoice_edit_page_is_blocked_when_invoice_has_payments(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Cliente financiero']);
        $invoice = $this->document($client, 'invoice', 'pending', 'FAC-1003', 50);

        $this->actingAs($user)->get("/invoices/{$invoice->id}/edit")->assertStatus(409);
    }

    public function test_client_data_is_safely_serialized_inside_the_alpine_component(): void
    {
        $user = User::factory()->create();
        Client::factory()->create(['name' => 'Cliente visible']);

        $this->actingAs($user)
            ->get('/clients')
            ->assertOk()
            ->assertSee('initialRows: JSON.parse', false)
            ->assertSee('Cliente visible');
    }

    public function test_client_with_documents_cannot_be_deleted(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Cliente con historial']);
        $this->document($client, 'invoice', 'pending', 'FAC-1000');

        $this->actingAs($user)
            ->deleteJson("/clients/{$client->id}")
            ->assertStatus(409);

        $this->assertDatabaseHas('clients', ['id' => $client->id]);
    }

    public function test_closed_documents_cannot_be_changed_or_deleted(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Cliente financiero']);
        $invoice = $this->document($client, 'invoice', 'paid', 'FAC-1001', 100);
        $quote = $this->document($client, 'quote', 'converted', 'COT-1001');

        $this->actingAs($user)->deleteJson("/invoices/{$invoice->id}")->assertStatus(409);
        $this->actingAs($user)->postJson("/invoices/{$invoice->id}/cancel")->assertStatus(409);
        $this->actingAs($user)->deleteJson("/quotes/{$quote->id}")->assertStatus(409);

        $this->assertDatabaseHas('documents', ['id' => $invoice->id, 'status' => 'paid']);
        $this->assertDatabaseHas('documents', ['id' => $quote->id, 'status' => 'converted']);
    }

    public function test_client_store_returns_the_created_client(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/clients', ['name' => 'Cliente rápido'])
            ->assertOk()
            ->assertJsonStructure(['message', 'client' => ['id', 'name']]);

        $this->assertDatabaseHas('clients', ['name' => 'Cliente rápido']);
    }

    public function test_quick_client_creation_is_available_in_document_forms(): void
    {
        $user = User::factory()->create();

        foreach (['/quotes/create', '/invoices/create'] as $path) {
            $this->actingAs($user)
                ->get($path)
                ->assertOk()
                ->assertSee('clientStoreUrl', false)
                ->assertSee('openClientModal()', false);
        }
    }

    private function document(Client $client, string $type, string $status, string $number, int $paid = 0): Document
    {
        return Document::create([
            'client_id' => $client->id,
            'type' => $type,
            'status' => $status,
            'date' => now()->toDateString(),
            'subtotal' => 100,
            'tax' => 0,
            'total' => 100,
            'paid' => $paid,
            'doc_number' => $number,
        ]);
    }
}
