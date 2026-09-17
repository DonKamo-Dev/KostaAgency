<?php

namespace Tests\Feature\Accessibility;

use App\Models\Client;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkspaceViewsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user    = User::factory()->create();
    }

    public function test_workspace_search_controls_have_accessible_names(): void
    {
        foreach (['/clients', '/services', '/expenses', '/quotes', '/bills', '/invoices'] as $path) {
            $this->actingAs($this->user)->get($path)
                ->assertOk()
                ->assertSee('aria-label="Buscar', false);
        }
    }

    public function test_workspace_views_have_modal_semantics(): void
    {
        foreach (['/clients', '/services', '/expenses', '/bills'] as $path) {
            $html = $this->actingAs($this->user)->get($path)->assertOk()->getContent();

            $this->assertStringContainsString('role="dialog"', $html, "Missing role='dialog' in {$path}");
            $this->assertStringContainsString('aria-modal="true"', $html, "Missing aria-modal='true' in {$path}");
        }
    }

    public function test_quotes_and_invoices_use_dedicated_pages_instead_of_modals(): void
    {
        $client = Client::factory()->create(['name' => 'Test']);
        $quote = Document::create([
            'client_id' => $client->id,
            'type' => 'quote',
            'status' => 'pending',
            'date' => now()->toDateString(),
            'subtotal' => 100,
            'tax' => 0,
            'total' => 100,
            'doc_number' => 'COT-0001',
        ]);
        $invoice = Document::create([
            'client_id' => $client->id,
            'type' => 'invoice',
            'status' => 'pending',
            'date' => now()->toDateString(),
            'subtotal' => 100,
            'tax' => 0,
            'total' => 100,
            'doc_number' => 'FAC-0001',
        ]);

        foreach (['/quotes/create', "/quotes/{$quote->id}", "/invoices/create", "/invoices/{$invoice->id}"] as $path) {
            $this->actingAs($this->user)->get($path)->assertOk();
        }
    }

    public function test_quotes_and_invoices_lists_link_to_their_own_pages(): void
    {
        $html = $this->actingAs($this->user)->get('/quotes')->assertOk()->getContent();
        $this->assertStringContainsString('/quotes/create', $html);
        $this->assertStringContainsString('/quotes/', $html);

        $html = $this->actingAs($this->user)->get('/invoices')->assertOk()->getContent();
        $this->assertStringContainsString('/invoices/create', $html);
        $this->assertStringContainsString('/invoices/', $html);
    }

    public function test_workspace_views_have_confirmation_dialogs(): void
    {
        foreach (['/clients', '/services', '/expenses', '/quotes', '/bills', '/invoices'] as $path) {
            $html = $this->actingAs($this->user)->get($path)->assertOk()->getContent();

            $this->assertStringContainsString('Eliminar', $html, "Missing delete confirmation text in {$path}");
            $this->assertStringContainsString('aria-label="', $html, "Missing aria-label in {$path}");
        }
    }

    public function test_workspace_views_have_live_error_region(): void
    {
        foreach (['/clients', '/services', '/expenses', '/quotes', '/bills', '/invoices'] as $path) {
            $html = $this->actingAs($this->user)->get($path)->assertOk()->getContent();

            $this->assertStringContainsString('aria-live="polite"', $html, "Missing aria-live='polite' in {$path}");
        }
    }
}
