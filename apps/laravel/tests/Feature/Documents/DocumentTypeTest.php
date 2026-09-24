<?php

namespace Tests\Feature\Documents;

use App\Models\Client;
use App\Models\Document;
use App\Models\User;
use App\Services\DocumentNumberGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentTypeTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_invoice_routes_reject_quote_ids(): void
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

        $this->actingAs($this->user)->deleteJson("/invoices/{$quote->id}")->assertNotFound();
    }

    public function test_bill_routes_reject_quote_ids(): void
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
            'doc_number' => 'COT-0002',
        ]);

        $this->actingAs($this->user)->deleteJson("/bills/{$quote->id}")->assertNotFound();
    }

    public function test_quote_routes_reject_invoice_ids(): void
    {
        $client = Client::factory()->create(['name' => 'Test']);
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

        $this->actingAs($this->user)->deleteJson("/quotes/{$invoice->id}")->assertNotFound();
    }

    public function test_edit_data_routes_reject_the_wrong_document_type(): void
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
            'doc_number' => 'COT-0099',
        ]);

        $this->actingAs($this->user)->getJson("/invoices/{$quote->id}/edit-data")->assertNotFound();
        $this->actingAs($this->user)->getJson("/bills/{$quote->id}/edit-data")->assertNotFound();

        $invoice = $quote->replicate()->fill([
            'type' => 'invoice',
            'doc_number' => 'FAC-0099',
        ]);
        $invoice->save();

        $this->actingAs($this->user)->getJson("/quotes/{$invoice->id}/edit-data")->assertNotFound();
    }

    public function test_number_generator_returns_type_prefixes(): void
    {
        $generator = app(DocumentNumberGenerator::class);

        $this->assertMatchesRegularExpression('/^COT-\d{4,}$/', $generator->next('quote'));
        $this->assertMatchesRegularExpression('/^FAC-\d{4,}$/', $generator->next('invoice'));
        $this->assertMatchesRegularExpression('/^CC-\d{4,}$/', $generator->next('bill'));
    }

    public function test_number_generator_increments_sequentially(): void
    {
        $client = Client::factory()->create(['name' => 'Test']);

        Document::create([
            'client_id' => $client->id,
            'type' => 'quote',
            'status' => 'pending',
            'date' => now()->toDateString(),
            'subtotal' => 100,
            'tax' => 0,
            'total' => 100,
            'doc_number' => 'COT-0001',
        ]);

        $generator = app(DocumentNumberGenerator::class);
        $next = $generator->next('quote');

        $this->assertMatchesRegularExpression('/^COT-0002$/', $next);
    }
}
