<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicViewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_have_titles_headings_and_no_placeholder_whatsapp_link(): void
    {
        foreach (['/', '/portafolio', '/contacto', '/cv'] as $path) {
            $this->get($path)->assertOk()->assertSee('<h1', false);
        }

        $this->get('/')->assertDontSee('href="https://wa.me/"', false);
        $this->get('/cv')->assertDontSee('<div onclick="copyEmailToClipboard()"', false);

        $this->get('/portafolio')
            ->assertSee('data-filter="all" aria-pressed="true"', false)
            ->assertSee('.filter-btn.active { background: var(--accent-red); border-color: var(--accent-red); color: #0A0A0A;', false)
            ->assertSeeInOrder(['data-filter="all"', 'data-filter="web"', 'data-filter="ecommerce"', 'data-filter="social"', 'data-filter="branding"'], false);

        $this->get('/')
            ->assertSeeInOrder(['01 · Desarrollo Web', '02 · Films', '03 · Estrategia', '04 · Diseño &amp; Branding'], false);

        $this->get('/contacto')
            ->assertSeeInOrder(['Desarrollo Web', 'Films &amp; Contenido', 'Estrategia Digital', 'Diseño &amp; Branding'], false);
    }
}
