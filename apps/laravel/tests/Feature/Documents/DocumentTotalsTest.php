<?php

namespace Tests\Feature\Documents;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentTotalsTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_store_recalculates_totals_from_quantity_and_unit_price(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Test']);

        $payload = [
            'client_id' => $client->id,
            'date' => now()->toDateString(),
            'notes' => null,
            'items' => [
                ['service_name' => 'Servicio A', 'quantity' => 1.5, 'unit_price' => 20.00, 'subtotal' => 0.01],
                ['service_name' => 'Servicio B', 'quantity' => 2, 'unit_price' => 50.00, 'subtotal' => 999.99],
            ],
        ];

        $this->actingAs($user)->postJson('/invoices', $payload)->assertOk();

        $this->assertDatabaseHas('documents', [
            'type' => 'invoice',
            'subtotal' => 130.00,
            'total' => 130.00,
        ]);
    }

    public function test_bill_store_recalculates_totals(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Test']);

        $payload = [
            'client_id' => $client->id,
            'date' => now()->toDateString(),
            'notes' => null,
            'items' => [
                ['service_name' => 'Servicio A', 'quantity' => 3, 'unit_price' => 100.00, 'subtotal' => 0.01],
            ],
        ];

        $this->actingAs($user)->postJson('/bills', $payload)->assertOk();

        $this->assertDatabaseHas('documents', [
            'type' => 'bill',
            'subtotal' => 300.00,
            'total' => 300.00,
        ]);
    }

    public function test_quote_store_recalculates_totals(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Test']);

        $payload = [
            'client_id' => $client->id,
            'date' => now()->toDateString(),
            'notes' => null,
            'items' => [
                ['service_name' => 'Servicio A', 'quantity' => 2, 'unit_price' => 75.50, 'subtotal' => 0.01],
            ],
        ];

        $this->actingAs($user)->postJson('/quotes', $payload)->assertOk();

        $this->assertDatabaseHas('documents', [
            'type' => 'quote',
            'subtotal' => 151.00,
            'total' => 151.00,
        ]);
    }
}
