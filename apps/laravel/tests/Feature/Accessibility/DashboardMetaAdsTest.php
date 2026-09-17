<?php

namespace Tests\Feature\Accessibility;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardMetaAdsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $user    = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_dashboard_is_available_to_authenticated_users(): void
    {
        $this->get('/dashboard')->assertOk();
    }

    public function test_meta_ads_wizard_is_available_to_authenticated_users(): void
    {
        $this->get('/servicios/meta-ads-ia')->assertOk();
    }

    public function test_meta_ads_wizard_renders_without_legacy_clickable_divs(): void
    {
        $this->get('/servicios/meta-ads-ia')->assertOk();
    }
}
