<?php

namespace Tests\Feature\MetaAds;

use App\Jobs\GenerateMetaAdsQuote;
use App\Livewire\MetaAds\History;
use App\Livewire\MetaAds\Wizard;
use App\Models\AiMetaQuote;
use App\Models\User;
use App\Services\ClaudeMetaAdsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class BackgroundGenerationTest extends TestCase
{
    use RefreshDatabase;

    private function fakeResult(): array
    {
        return [
            'resumen_ejecutivo' => 'Estrategia de prueba.',
            'estructura_campana' => ['objetivo' => 'CONVERSIONS', 'nombre_campana' => 'Test', 'ad_sets' => []],
            'creativos' => ['formatos' => ['Video'], 'copies_sugeridos' => ['Copy'], 'call_to_action' => 'LEARN_MORE', 'recomendaciones' => ''],
            'metricas_estimadas' => ['alcance_diario' => '1k', 'cpm_estimado_cop' => '$5k', 'ctr_objetivo' => '2%', 'cpc_estimado_cop' => '$300', 'frecuencia_sugerida' => '2x'],
            'fases' => [],
            'recomendaciones_pixel' => [],
            'desglose_precios' => ['presupuesto_pauta_cop' => 75000, 'honorarios_gestion_cop' => 15000, 'honorarios_creativos_cop' => 10000, 'total_agencia_cop' => 25000, 'total_inversion_cop' => 100000],
        ];
    }

    private function fillWizard(Testable $component): Testable
    {
        return $component
            ->set('clientName', 'Tienda Moda')
            ->set('industry', 'Moda')
            ->set('budgetCop', 300000)
            ->set('durationDays', 15)
            ->set('ageRange', '25-34')
            ->set('location', 'Cartagena, Colombia')
            ->set('interests', ['Moda'])
            ->set('campaignType', 'CONVERSIONS')
            ->set('destination', 'WEB')
            ->set('step', 5);
    }

    public function test_wizard_dispatches_background_job(): void
    {
        Queue::fake();

        Livewire::actingAs(User::factory()->create())
            ->test(Wizard::class)
            ->tap(fn ($component) => $this->fillWizard($component))
            ->call('generate')
            ->assertSet('generating', true);

        Queue::assertPushed(GenerateMetaAdsQuote::class);
        $this->assertDatabaseHas('ai_meta_quotes', [
            'generation_status' => AiMetaQuote::STATUS_PENDING,
        ]);
    }

    public function test_job_marks_quote_completed_on_success(): void
    {
        $this->mock(ClaudeMetaAdsService::class)
            ->shouldReceive('generate')
            ->once()
            ->andReturn($this->fakeResult());

        $quote = AiMetaQuote::factory()->create([
            'generation_status' => AiMetaQuote::STATUS_PENDING,
            'ai_result' => [],
        ]);

        (new GenerateMetaAdsQuote($quote->id))->handle(app(ClaudeMetaAdsService::class));

        $quote->refresh();
        $this->assertSame(AiMetaQuote::STATUS_COMPLETED, $quote->generation_status);
        $this->assertSame('Estrategia de prueba.', $quote->ai_result['resumen_ejecutivo']);
    }

    public function test_job_marks_quote_failed_on_error(): void
    {
        $this->mock(ClaudeMetaAdsService::class)
            ->shouldReceive('generate')
            ->andThrow(new \RuntimeException('Groq caído'));

        $quote = AiMetaQuote::factory()->create([
            'generation_status' => AiMetaQuote::STATUS_PENDING,
            'ai_result' => [],
        ]);

        try {
            (new GenerateMetaAdsQuote($quote->id))->handle(app(ClaudeMetaAdsService::class));
            $this->fail('The job should rethrow the generation error.');
        } catch (\RuntimeException) {
            // Expected: the queue retries and eventually marks the job as failed.
        }

        $quote->refresh();
        $this->assertSame(AiMetaQuote::STATUS_FAILED, $quote->generation_status);
        $this->assertStringContainsString('Groq caído', (string) $quote->error);
    }

    public function test_wizard_shows_result_after_sync_generation(): void
    {
        $this->mock(ClaudeMetaAdsService::class)
            ->shouldReceive('generate')
            ->andReturn($this->fakeResult());

        Livewire::actingAs(User::factory()->create())
            ->test(Wizard::class)
            ->tap(fn ($component) => $this->fillWizard($component))
            ->call('generate')
            ->assertSet('generating', false)
            ->assertSee('Estrategia de prueba.');
    }

    public function test_wizard_keeps_inputs_and_shows_error_when_generation_fails(): void
    {
        $this->mock(ClaudeMetaAdsService::class)
            ->shouldReceive('generate')
            ->andThrow(new \RuntimeException('Groq caído'));

        Livewire::actingAs(User::factory()->create())
            ->test(Wizard::class)
            ->tap(fn ($component) => $this->fillWizard($component))
            ->call('generate')
            ->assertSet('generating', false)
            ->assertSet('clientName', 'Tienda Moda')
            ->assertSet('step', 5)
            ->assertSee('Groq caído');
    }

    public function test_history_only_lists_completed_quotes(): void
    {
        AiMetaQuote::factory()->create([
            'client_name' => 'Completada',
            'generation_status' => AiMetaQuote::STATUS_COMPLETED,
        ]);
        AiMetaQuote::factory()->create([
            'client_name' => 'Pendiente',
            'generation_status' => AiMetaQuote::STATUS_PENDING,
        ]);

        Livewire::actingAs(User::factory()->create())
            ->test(History::class)
            ->assertSee('Completada')
            ->assertDontSee('Pendiente');
    }
}
