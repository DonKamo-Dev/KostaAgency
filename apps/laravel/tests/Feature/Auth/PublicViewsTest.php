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
    }
}
