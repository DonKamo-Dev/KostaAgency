<?php

namespace Tests\Feature\Documents;

use App\Models\Client;
use App\Models\Document;
use App\Models\DocumentItem;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_document_pdf_returns_valid_pdf_content(): void
    {
        $this->fakePdfRenderer();

        $user    = User::factory()->create();

        $client = Client::factory()->create([
            'name'       => 'Test Client',
            'email'      => 'test@example.com',
        ]);

        $document = Document::create([
            'client_id'  => $client->id,
            'type'       => 'quote',
            'date'       => now()->toDateString(),
            'subtotal'   => 100.00,
            'tax'        => 19.00,
            'total'      => 119.00,
            'paid'       => 0,
            'status'     => 'pending',
            'doc_number' => 'COT-0001',
        ]);

        DocumentItem::create([
            'document_id'  => $document->id,
            'service_name' => 'Diseño Web',
            'description'  => 'Landing page',
            'quantity'     => 1,
            'unit_price'   => 100.00,
            'subtotal'     => 100.00,
        ]);

        $response = $this->actingAs($user)->get(route('documents.pdf', $document));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_document_pdf_template_uses_lightweight_brand_mark(): void
    {
        $html = view('pdf.quote', [
            'document' => (object) [
                'doc_number' => 'COT-0001',
                'type'       => 'quote',
                'date'       => now(),
                'due_date'   => null,
                'subtotal'   => 100,
                'tax'        => 19,
                'total'      => 119,
                'notes'      => null,
                'client'     => (object) [
                    'name'   => 'Test Client',
                    'email'  => 'test@example.com',
                    'phone'  => '123',
                    'tax_id' => 'NIT-123',
                    'address'=> 'Calle 1',
                ],
                'items'      => collect([(object) [
                    'service_name' => 'Servicio',
                    'description'  => null,
                    'quantity'     => 1,
                    'unit_price'   => 100,
                    'subtotal'     => 100,
                ]]),
            ],
        ])->render();

        $this->assertStringNotContainsString('Imagotipo-DK.png', $html);
        $this->assertStringNotContainsString('base64', $html);
        $this->assertStringContainsString('KAMO', $html);
    }

    private function fakePdfRenderer(): void
    {
        $pdf = \Mockery::mock(PDF::class);
        $pdf->shouldReceive('loadView')->once()->andReturnSelf();
        $pdf->shouldReceive('stream')->once()->andReturn(
            response('%PDF-1.4 fake', 200, ['Content-Type' => 'application/pdf'])
        );
        $this->app->bind('dompdf.wrapper', fn () => $pdf);
    }
}
