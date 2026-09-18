<?php

namespace Tests\Feature\Documents;

use App\Models\Client;
use App\Models\Document;
use App\Models\DocumentItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteConversionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_converted_quote_cannot_be_converted_twice(): void
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

        DocumentItem::create([
            'document_id' => $quote->id,
            'service_name' => 'Servicio',
            'quantity' => 1,
            'unit_price' => 100,
            'subtotal' => 100,
        ]);

        $this->actingAs($this->user)->postJson("/quotes/{$quote->id}/convert")->assertOk();
        $this->actingAs($this->user)->postJson("/quotes/{$quote->id}/convert")->assertStatus(409);
        $this->assertSame(1, Document::where('related_doc_id', $quote->id)->count());
    }

    public function test_only_pending_quotes_can_be_converted(): void
    {
        $client = Client::factory()->create(['name' => 'Test']);
        $quote = Document::create([
            'client_id' => $client->id,
            'type' => 'quote',
            'status' => 'cancelled',
            'date' => now()->toDateString(),
            'subtotal' => 100,
            'tax' => 0,
            'total' => 100,
            'doc_number' => 'COT-0002',
        ]);

        $this->actingAs($this->user)->postJson("/quotes/{$quote->id}/convert")->assertStatus(409);
    }
}
