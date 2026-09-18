<?php

namespace Tests\Feature\Documents;

use App\Models\Client;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_payment_cannot_exceed_outstanding_balance(): void
    {
        $client = Client::factory()->create(['name' => 'Test']);
        $invoice = Document::create([
            'client_id' => $client->id,
            'type' => 'invoice',
            'total' => 100,
            'paid' => 40,
            'status' => 'pending',
            'date' => now()->toDateString(),
            'doc_number' => 'FAC-0001',
        ]);

        $this->actingAs($this->user)->postJson("/invoices/{$invoice->id}/payment", [
            'amount' => 61,
            'date' => now()->toDateString(),
            'method' => 'transfer',
        ])->assertStatus(422);

        $this->assertSame(40.0, (float) $invoice->fresh()->paid);
    }

    public function test_payment_exact_balance_marks_as_paid(): void
    {
        $client = Client::factory()->create(['name' => 'Test']);
        $invoice = Document::create([
            'client_id' => $client->id,
            'type' => 'invoice',
            'total' => 100,
            'paid' => 40,
            'status' => 'pending',
            'date' => now()->toDateString(),
            'doc_number' => 'FAC-0002',
        ]);

        $this->actingAs($this->user)->postJson("/invoices/{$invoice->id}/payment", [
            'amount' => 60,
            'date' => now()->toDateString(),
            'method' => 'transfer',
        ])->assertOk();

        $this->assertSame('paid', $invoice->fresh()->status);
    }

    public function test_bill_payment_cannot_exceed_balance(): void
    {
        $client = Client::factory()->create(['name' => 'Test']);
        $bill = Document::create([
            'client_id' => $client->id,
            'type' => 'bill',
            'total' => 200,
            'paid' => 0,
            'status' => 'pending',
            'date' => now()->toDateString(),
            'doc_number' => 'CC-0001',
        ]);

        $this->actingAs($this->user)->postJson("/bills/{$bill->id}/payment", [
            'amount' => 201,
            'date' => now()->toDateString(),
            'method' => 'cash',
        ])->assertStatus(422);

        $this->assertSame(0.0, (float) $bill->fresh()->paid);
    }
}
