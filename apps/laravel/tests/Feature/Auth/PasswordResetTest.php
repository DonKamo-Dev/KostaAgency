<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_reset_request_page_is_available(): void
    {
        $response = $this->get('/forgot-password')->assertOk();

        $this->assertStringContainsString('correo', mb_strtolower($response->getContent()));
    }

    public function test_password_reset_page_has_spanish_labels(): void
    {
        $user = User::factory()->create();

        $response = $this->get("/reset-password/{$user->id}/?token=test");
        $response->assertSee('Restablecer contraseña')
                 ->assertSee('Correo electrónico')
                 ->assertSee('Nueva contraseña');
    }
}
