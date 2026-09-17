<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AiMetaQuoteFactory extends Factory
{
    protected $model = \App\Models\AiMetaQuote::class;

    public function definition(): array
    {
        $budget   = $this->faker->numberBetween(100000, 1200000);
        $duration = $this->faker->randomElement([15, 30, 45, 60]);

        return [
            'client_name'      => $this->faker->company(),
            'industry'         => $this->faker->randomElement(['Moda', 'Salud', 'Tecnología']),
            'budget_cop'       => $budget,
            'duration_days'    => $duration,
            'daily_budget_cop' => intval($budget / $duration),
            'age_range'        => '25-34',
            'location'         => 'Bogotá, Colombia',
            'interests'        => ['Emprendimiento', 'Tecnología'],
            'campaign_type'    => $this->faker->randomElement(['CONVERSIONS', 'TRAFFIC', 'LEADS']),
            'destination'      => $this->faker->randomElement(['WEB', 'WHATSAPP', 'BOTH']),
            'whatsapp_number'  => null,
            'ai_result'        => [
                'resumen_ejecutivo' => 'Estrategia de prueba.',
                'estructura_campana' => ['objetivo' => 'CONVERSIONS', 'nombre_campana' => 'Test', 'ad_sets' => []],
                'creativos' => ['formatos' => [], 'copies_sugeridos' => [], 'call_to_action' => 'LEARN_MORE', 'recomendaciones' => ''],
                'metricas_estimadas' => ['alcance_diario' => '1k', 'cpm_estimado_cop' => '$5k', 'ctr_objetivo' => '2%', 'cpc_estimado_cop' => '$300', 'frecuencia_sugerida' => '2x'],
                'fases' => [],
                'recomendaciones_pixel' => [],
                'desglose_precios' => ['presupuesto_pauta_cop' => 75000, 'honorarios_gestion_cop' => 15000, 'honorarios_creativos_cop' => 10000, 'total_agencia_cop' => 25000, 'total_inversion_cop' => 100000],
            ],
        ];
    }
}
