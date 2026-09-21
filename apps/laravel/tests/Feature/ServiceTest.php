<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_soft_delete_a_service(): void
    {
        $user = User::factory()->create();
        $service = Service::factory()->create();

        $this->actingAs($user)
            ->deleteJson(route('services.destroy', $service))
            ->assertOk()
            ->assertJson(['message' => 'Servicio eliminado']);

        $this->assertSoftDeleted('services', ['id' => $service->id]);
        $this->assertNull(Service::find($service->id));
        $this->assertNotNull(Service::withTrashed()->find($service->id));
    }
}
