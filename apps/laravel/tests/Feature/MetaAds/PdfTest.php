<?php

namespace Tests\Feature\MetaAds;

use App\Models\AiMetaQuote;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_pdf_download_requires_auth(): void
    {
        $quote = AiMetaQuote::factory()->create();
        $this->get(route('meta-ads.pdf', $quote->id))->assertRedirect(route('login'));
    }

    public function test_pdf_returns_pdf_response(): void
    {
        $this->fakePdfRenderer();

        $user = User::factory()->create();
        $quote = AiMetaQuote::factory()->create();

        $response = $this->actingAs($user)->get(route('meta-ads.pdf', $quote->id));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_missing_pdf_quote_returns_not_found_without_rendering(): void
    {
        $pdf = \Mockery::mock(PDF::class);
        $pdf->shouldReceive('loadView')->never();
        $this->app->bind('dompdf.wrapper', fn () => $pdf);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('meta-ads.pdf', 999999))
            ->assertNotFound();

    }

    private function fakePdfRenderer(): void
    {
        $pdf = \Mockery::mock(PDF::class);
        $pdf->shouldReceive('loadView')->once()->andReturnSelf();
        $pdf->shouldReceive('setPaper')->once()->with('a4', 'portrait')->andReturnSelf();
        $pdf->shouldReceive('download')->once()->andReturn(
            response('fake-pdf', 200, ['Content-Type' => 'application/pdf'])
        );
        $this->app->bind('dompdf.wrapper', fn () => $pdf);
    }
}
