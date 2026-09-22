<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_shows_updated_web_development_tags_and_action_buttons(): void
    {
        $response = $this->get(route('landing'));

        $response->assertOk();
        $response->assertSee('WordPress');
        $response->assertSee('React');
        $response->assertSee('Laravel');
        $response->assertSee('PHP');
        $response->assertSee('Explorar Desarrollo Web');
        $response->assertSee('Explorar E-commerce');
        $response->assertSee('Conocer Producción & Films');
        $response->assertSee('Conocer Estrategia Digital');
        $response->assertSee('Ver Performance & Pauta');
        $response->assertSee('Explorar Diseño & Branding');
    }

    public function test_user_can_visit_service_detail_page(): void
    {
        $response = $this->get(route('services.show', 'desarrollo-web'));

        $response->assertOk();
        $response->assertSee('Desarrollo Web');
        $response->assertSee('WordPress');
        $response->assertSee('React');
        $response->assertSee('Laravel');
        $response->assertSee('PHP');
        $response->assertSee('Solicitar Cotización');
    }

    public function test_invalid_service_slug_returns_404(): void
    {
        $response = $this->get(route('services.show', 'servicio-invalido'));

        $response->assertNotFound();
    }
}
