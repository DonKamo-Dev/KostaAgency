<?php

namespace Tests\Unit;

use App\Services\ClaudeMetaAdsService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ClaudeMetaAdsServiceTest extends TestCase
{
    private array $sampleInput = [
        'client_name'      => 'Tienda Moda',
        'industry'         => 'Moda',
        'budget_cop'       => 300000,
        'duration_days'    => 15,
        'age_range'        => '25-34',
        'location'         => 'Cartagena, Colombia',
        'interests'        => ['Moda', 'Emprendimiento'],
        'campaign_type'    => 'CONVERSIONS',
        'destination'      => 'WHATSAPP',
        'whatsapp_number'  => '+573001234567',
        'platform'         => 'META',
    ];

    private array $fakeApiResponse = [
        'resumen_ejecutivo' => 'Campaña de conversión hacia WhatsApp.',
        'estructura_campana' => [
            'objetivo'      => 'MESSAGES',
            'nombre_campana' => 'Tienda Moda | Conversiones WA',
            'ad_sets'       => [[
                'nombre'               => 'Audiencia Principal',
                'audiencia_descripcion' => 'Mujeres 25-34, Cartagena',
                'placements'           => ['Instagram Feed', 'Stories'],
                'presupuesto_diario_cop' => 15000,
                'optimizacion'         => 'CONVERSATIONS',
                'duracion_dias'        => 15,
            ]],
        ],
        'creativos' => [
            'formatos'         => ['Video 15s', 'Imagen estática'],
            'copies_sugeridos' => ['¡Escríbenos y recibe tu descuento!'],
            'call_to_action'   => 'WHATSAPP_MESSAGE',
            'recomendaciones'  => 'Usar imágenes con productos en uso real.',
        ],
        'metricas_estimadas' => [
            'alcance_diario'    => '3,000 – 6,000',
            'cpm_estimado_cop'  => '$4,000 – $7,000',
            'ctr_objetivo'      => '1.5% – 2.8%',
            'cpc_estimado_cop'  => '$200 – $400',
            'frecuencia_sugerida' => '2.5x',
        ],
        'fases' => [[
            'fase'            => 'TOFU',
            'dias'            => '1-7',
            'objetivo'        => 'Awareness',
            'presupuesto_pct' => 40,
        ]],
        'recomendaciones_pixel' => ['Instalar Meta Pixel', 'Configurar evento Lead'],
        'desglose_precios' => [
            'presupuesto_pauta_cop'    => 225000,
            'honorarios_gestion_cop'   => 45000,
            'honorarios_creativos_cop' => 30000,
            'total_agencia_cop'        => 75000,
            'total_inversion_cop'      => 300000,
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.groq.key' => 'fake-groq-key']);
    }

    public function test_generate_returns_structured_array(): void
    {
        Http::fake([
            'https://api.groq.com/openai/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode($this->fakeApiResponse),
                        ],
                    ],
                ],
            ], 200),
        ]);

        $result = app(ClaudeMetaAdsService::class)->generate($this->sampleInput);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('resumen_ejecutivo', $result);
        $this->assertArrayHasKey('estructura_campana', $result);
        $this->assertArrayHasKey('desglose_precios', $result);
        $this->assertArrayHasKey('total_inversion_cop', $result['desglose_precios']);
    }

    public function test_generate_throws_on_invalid_json(): void
    {
        Http::fake([
            'https://api.groq.com/openai/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'not valid json {{{',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/JSON inválido/');

        app(ClaudeMetaAdsService::class)->generate($this->sampleInput);
    }

    public function test_generate_throws_on_missing_required_field(): void
    {
        $input = $this->sampleInput;
        unset($input['platform']);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Missing required key: platform/');

        app(ClaudeMetaAdsService::class)->generate($input);
    }
}

