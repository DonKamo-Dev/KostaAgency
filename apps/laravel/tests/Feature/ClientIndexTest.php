<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_renders_existing_clients_before_javascript_initializes(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create([
            'name' => 'Cliente visible',
        ]);

        $this->actingAs($user)
            ->get('/clients')
            ->assertOk()
            ->assertSee($client->name)
            ->assertSee('aria-live="polite"', false)
            ->assertSee('/livewire/livewire.min.js');
    }
}
