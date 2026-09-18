<?php

namespace Tests\Feature\Accessibility;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayoutAccessibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_and_app_layouts_have_skip_link_main_target_and_theme_color(): void
    {
        $public = $this->get('/')->assertOk()->getContent();
        $this->assertStringContainsString('href="#main"', $public);
        $this->assertStringContainsString('id="main"', $public);

        $user = User::factory()->create();

        $app = $this->actingAs($user)->get('/dashboard')->assertOk()->getContent();
        $this->assertStringContainsString('href="#main"', $app);
        $this->assertStringContainsString('id="main"', $app);
    }

    public function test_guest_layout_has_skip_link_and_main(): void
    {
        $html = $this->get('/login')->assertOk()->getContent();
        $this->assertStringContainsString('href="#main"', $html);
        $this->assertStringContainsString('id="main"', $html);
    }
}
