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

        $user = User::factory()->create();

        $client = Client::factory()->create([
            'name' => 'Test Client',
            'email' => 'test@example.com',
        ]);

        $document = Document::create([
            'client_id' => $client->id,
            'type' => 'quote',
            'date' => now()->toDateString(),
            'subtotal' => 100.00,
            'tax' => 19.00,
            'total' => 119.00,
            'paid' => 0,
            'status' => 'pending',
            'doc_number' => 'COT-0001',
        ]);

        DocumentItem::create([
            'document_id' => $document->id,
            'service_name' => 'Diseño Web',
            'description' => 'Landing page',
            'quantity' => 1,
            'unit_price' => 100.00,
            'subtotal' => 100.00,
        ]);

        $response = $this->actingAs($user)->get(route('documents.pdf', $document));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_quote_pdf_template_hides_the_temporary_brand_mark(): void
    {
        $html = view('pdf.quote', [
            'document' => (object) [
                'doc_number' => 'COT-0001',
                'type' => 'quote',
                'date' => now(),
                'due_date' => null,
                'subtotal' => 100,
                'tax' => 19,
                'total' => 119,
                'notes' => null,
                'client' => (object) [
                    'name' => 'Test Client',
                    'email' => 'test@example.com',
                    'phone' => '123',
                    'tax_id' => 'NIT-123',
                    'address' => 'Calle 1',
                ],
                'items' => collect([(object) [
                    'service_name' => 'Servicio',
                    'description' => null,
                    'quantity' => 1,
                    'unit_price' => 100,
                    'subtotal' => 100,
                ]]),
            ],
        ])->render();

        $this->assertStringNotContainsString('Imagotipo-DK.png', $html);
        $this->assertStringNotContainsString('base64', $html);
        $this->assertStringNotContainsString('>KOSTA<', $html);
        $this->assertStringContainsString('Kosta Studio Films', $html);
        $this->assertStringContainsString('table-shell', $html);
        $this->assertStringContainsString('table-modern', $html);
        $this->assertStringContainsString('item-index', $html);
        $this->assertStringContainsString('>01<', $html);
    }

    public function test_document_summary_is_anchored_above_the_footer(): void
    {
        $template = file_get_contents(resource_path('views/pdf/quote.blade.php'));

        $this->assertStringContainsString('class="document-summary"', $template);
        $this->assertMatchesRegularExpression('/\.document-summary\s*\{[^}]*position:\s*absolute;[^}]*bottom:\s*58px;/s', $template);
        $this->assertStringContainsString('padding: 28px 36px 250px 36px;', $template);
    }

    public function test_bill_pdf_uses_dedicated_bill_template_and_computes_item_value(): void
    {
        $pdf = \Mockery::mock(PDF::class);
        $pdf->shouldReceive('loadView')
            ->once()
            ->withArgs(fn ($view) => $view === 'pdf.bill')
            ->andReturnSelf();
        $pdf->shouldReceive('stream')->once()->andReturn(
            response('%PDF-1.4 fake', 200, ['Content-Type' => 'application/pdf'])
        );
        $this->app->bind('dompdf.wrapper', fn () => $pdf);

        $user = User::factory()->create();
        $client = Client::factory()->create(['name' => 'Kool Imports', 'email' => 'contacto@koolimports.com']);

        $document = Document::create([
            'client_id' => $client->id,
            'type' => 'bill',
            'date' => '2026-11-17',
            'subtotal' => 1500000,
            'tax' => 0,
            'total' => 1500000,
            'paid' => 0,
            'status' => 'pending',
            'doc_number' => 'CC-0001',
            'notes' => "Cuenta de Ahorro para Pagos\nCTA BANCOLOMBIA: 67817145938\nCTA Nequi: 3158396238",
        ]);

        DocumentItem::create([
            'document_id' => $document->id,
            'service_name' => 'Creacion de Chatbot y Crm para Empresa',
            'description' => '',
            'quantity' => 1,
            'unit_price' => 1500000,
            'subtotal' => 0,
        ]);

        $response = $this->actingAs($user)->get(route('documents.pdf', $document));
        $response->assertOk();

        $html = view('pdf.bill', ['document' => $document->load(['items', 'client'])])->render();
        $this->assertStringContainsString('CUENTA DE COBRO', $html);
        $this->assertStringContainsString('CC-0001', $html);
        $this->assertStringContainsString('Kool Imports', $html);
        $this->assertStringContainsString('DEBE A', $html);
        $this->assertStringContainsString('Yohan Camilo Blanco Rojas', $html);
        $this->assertStringContainsString('CC. 1002196396', $html);
        $this->assertStringNotContainsString('yohanblaro18@gmail.com', $html);
        $this->assertStringContainsString('alt="Bancolombia"', $html);
        $this->assertStringContainsString('alt="Nequi"', $html);
        $this->assertStringContainsString('67817145938', $html);
        $this->assertStringContainsString('3158396238', $html);
        $this->assertStringContainsString('Por concepto de:', $html);
        $this->assertStringContainsString('$1.500.000', $html);
        $this->assertStringContainsString('Generado automáticamente por Kosta Studio Films', $html);
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
