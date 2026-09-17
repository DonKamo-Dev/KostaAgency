<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SharedLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_layouts_render_one_global_hud_and_do_not_duplicate_livewire_hooks(): void
    {
        $user = User::factory()->create();

        $pages = [
            $this->get('/login')->assertOk()->getContent(),
            $this->get('/')->assertOk()->getContent(),
            $this->actingAs($user)->get('/dashboard')->assertOk()->getContent(),
        ];

        foreach ($pages as $html) {
            $this->assertSame(1, substr_count($html, 'id="global-thinking-hud"'));
            $this->assertStringNotContainsString("Livewire.hook('commit'", $html);
        }

        $this->assertStringContainsString('/livewire/livewire.min.js', $pages[2]);
    }
}
