<?php

namespace Tests\Feature\Accessibility;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAccessibilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_dashboard_is_available_to_authenticated_users(): void
    {
        $this->get('/dashboard')->assertOk();
    }
}
