<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ClaudeMetaAdsService
{
    private const GROQ_URL   = 'https://api.groq.com/openai/v1/chat/completions';
    private const MODEL      = 'llama-3.3-70b-versatile';
    private const MAX_TOKENS = 4096;

    private const BASE_RULES = <<<'TEXT'
Rules:
- All monetary values in COP (Colombian Pesos)
- Budget estimates must be realistic for the Colombian market
- Agency gestión fee: 30–40% of pauta budget
- Agency creativos fee: 20–30% of pauta budget
- Return ONLY valid JSON matching the exact schema provided. No markdown, no explanation outside JSON.
TEXT;

    public function generate(array $data): array
    {
        $required = ['client_name', 'industry', 'budget_cop', 'duration_days', 'age_range',
                     'location', 'interests', 'campaign_type', 'destination', 'whatsapp_number', 'platform'];
        foreach ($required as $key) {
            if (!array_key_exists($key, $data)) {
                throw new \InvalidArgumentException("Missing required key: {$key}");
            }
        }

        $apiKey = config('services.groq.key');
        if (!$apiKey) {
            throw new \RuntimeException('GROQ_API_KEY no está configurada en el .env');
        }

        $response = Http::timeout(90)
            ->withToken($apiKey)
            ->post(self::GROQ_URL, [
                'model'       => self::MODEL,
                'max_tokens'  => self::MAX_TOKENS,
                'temperature' => 0.4,
                'messages'    => [
                    ['role' => 'system', 'content' => $this->buildSystemPrompt($data['platform'])],
                    ['role' => 'user',   'content' => $this->buildPrompt($data)],
                ],
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('Groq API error ' . $response->status() . ': ' . $response->body());
        }

        $text   = $response->json('choices.0.message.content') ?? '';
        $text   = trim(preg_replace('/^```(?:json)?\s*|\s*```$/s', '', $text));
        $result = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('JSON inválido: ' . json_last_error_msg() . ' — ' . substr($text, 0, 300));
        }

        return $result;
    }

    private function buildSystemPrompt(string $platform): string
    {
        $context = match ($platform) {
            'GOOGLE' => <<<'TEXT'
You are a Google Ads strategy expert for a Colombian digital agency called Kamo Agency.
You generate professional Google Ads campaign strategies using real Google Ads structure:
Campaign → Ad Group → Ads.
Use real Google Ads terminology: Performance Max, Search, Shopping, Display, YouTube.
Metrics: Impressions, CTR, CPC, ROAS, CPA, Quality Score, Impression Share.
TEXT,
            'BOTH' => <<<'TEXT'
You are a digital advertising strategy expert for a Colombian digital agency called Kamo Agency.
You generate integrated strategies that combine Google Ads AND Meta Ads (Facebook/Instagram).
For Google Ads use: Performance Max, Search, Shopping campaigns with keywords.
For Meta Ads use: Campaign → Ad Sets → Ads with interests and audience targeting.
Split the total budget optimally between both platforms.
TEXT,
            default => <<<'TEXT'
You are a Meta Ads strategy expert for a Colombian digital agency called Kamo Agency.
You generate professional Meta Ads strategies following real Meta structure:
Campaign → Ad Sets → Ads (Facebook and Instagram).
Use real Meta Ads terminology: placements, objectives, optimization events, CTAs.
Metrics: CPM, CTR, CPC, Reach, Frequency.
TEXT,
        };

        return $context . "\n" . self::BASE_RULES;
    }

    private function buildPrompt(array $d): string
    {
        $interests   = implode(', ', $d['interests']);
        $dailyBudget = number_format((int) ($d['budget_cop'] / max(1, (int) $d['duration_days'])), 0, ',', '.');
        $budgetFmt   = number_format($d['budget_cop'], 0, ',', '.');
        $whatsapp    = $d['whatsapp_number'] ? " (WhatsApp: {$d['whatsapp_number']})" : '';
        $schema      = $this->schema($d['platform']);

        $platformLabel = match ($d['platform']) {
            'GOOGLE' => 'Google Ads',
            'BOTH'   => 'Google Ads + Meta Ads',
            default  => 'Meta Ads',
        };

        return <<<PROMPT
Generate a {$platformLabel} campaign strategy for:
- Client: {$d['client_name']} (Industry: {$d['industry']})
- Total budget: \${$budgetFmt} COP over {$d['duration_days']} days (\${$dailyBudget} COP/day)
- Target audience: {$d['age_range']}, {$d['location']}, interests/keywords: {$interests}
- Campaign objective: {$d['campaign_type']}
- Destination: {$d['destination']}{$whatsapp}

Return ONLY JSON matching exactly this schema:
{$schema}
PROMPT;
    }

    private function schema(string $platform): string
    {
        $adSetsLabel  = $platform === 'GOOGLE' ? 'ad_groups (Google Ad Groups)' : 'ad_sets (Meta Ad Sets)';
        $placeholders = $platform === 'GOOGLE'
            ? ['string (Google network: Search, Display, YouTube, Shopping)']
            : ['string (Meta placements: Feed, Stories, Reels, etc.)'];

        $metrics = $platform === 'GOOGLE'
            ? [
                'impresiones_estimadas' => 'string',
                'ctr_objetivo'          => 'string',
                'cpc_estimado_cop'      => 'string',
                'cpa_estimado_cop'      => 'string',
                'roas_objetivo'         => 'string',
            ]
            : [
                'alcance_diario'      => 'string',
                'cpm_estimado_cop'    => 'string',
                'ctr_objetivo'        => 'string',
                'cpc_estimado_cop'    => 'string',
                'frecuencia_sugerida' => 'string',
            ];

        return json_encode([
            'resumen_ejecutivo' => 'string',
            'estructura_campana' => [
                'objetivo'       => 'string',
                'nombre_campana' => 'string',
                'ad_sets'        => [[
                    'nombre'                 => 'string',
                    'audiencia_descripcion'  => 'string — include platform prefix if BOTH (Meta/Google)',
                    'placements'             => $placeholders,
                    'presupuesto_diario_cop' => 'integer',
                    'optimizacion'           => 'string',
                    'duracion_dias'          => 'integer',
                ]],
            ],
            'creativos' => [
                'formatos'         => ['string'],
                'copies_sugeridos' => ['string'],
                'call_to_action'   => 'string',
                'recomendaciones'  => 'string',
            ],
            'metricas_estimadas' => $metrics,
            'fases' => [[
                'fase'            => 'TOFU|MOFU|BOFU',
                'dias'            => 'string',
                'objetivo'        => 'string',
                'presupuesto_pct' => 'integer',
            ]],
            'recomendaciones_pixel' => ['string — use "Tag" instead of "Pixel" for Google Ads'],
            'desglose_precios' => [
                'presupuesto_pauta_cop'    => 'integer',
                'honorarios_gestion_cop'   => 'integer',
                'honorarios_creativos_cop' => 'integer',
                'total_agencia_cop'        => 'integer',
                'total_inversion_cop'      => 'integer',
            ],
        ], JSON_PRETTY_PRINT);
    }
}
