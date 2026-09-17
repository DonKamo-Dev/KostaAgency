<?php

namespace App\Livewire\MetaAds;

use App\Models\AiMetaQuote;
use App\Models\Client;
use App\Services\ClaudeMetaAdsService;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Wizard extends Component
{
    public int    $step           = 1;
    public string $clientName     = '';
    public string $industry       = '';
    public string $industryOther  = '';
    public int    $budgetCop      = 0;
    public int    $durationDays   = 30;
    public string $ageRange       = '';
    public string $location       = '';
    public array  $interests      = [];
    public string $interestOther  = '';
    public string $platform       = 'META';
    public string $campaignType   = '';
    public string $destination    = '';
    public string $whatsappNumber = '';
    public bool    $generating     = false;
    public ?string $generateError  = null;
    public array   $clients        = [];
    public int    $selectedClientId = 0;

    #[Locked]
    public ?int   $quoteId   = null;
    public ?array $result    = null;

    private array $stepRules = [
        1 => ['clientName' => 'required|string|max:200'],
        2 => [
            'budgetCop'    => 'required|integer|min:100000',
            'durationDays' => 'required|integer|in:15,30,45,60',
        ],
        3 => [
            'ageRange'  => 'required|string',
            'location'  => 'required|string|max:200',
            'interests' => 'required|array|min:1',
        ],
        4 => ['campaignType' => 'required|string|in:AWARENESS,TRAFFIC,LEADS,CONVERSIONS,ENGAGEMENT'],
        5 => ['destination'  => 'required|string|in:WEB,WHATSAPP,BOTH'],
    ];

    public function mount(?int $id = null): void
    {
        $this->clients   = Client::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->toArray();

        if ($id !== null) {
            $quote = AiMetaQuote::findOrFail($id);
            $this->clientName     = $quote->client_name;
            $this->industry       = $quote->industry;
            $this->budgetCop      = $quote->budget_cop;
            $this->durationDays   = $quote->duration_days;
            $this->ageRange       = $quote->age_range;
            $this->location       = $quote->location;
            $this->interests      = $quote->interests ?? [];
            $this->platform       = $quote->platform ?? 'META';
            $this->campaignType   = $quote->campaign_type;
            $this->destination    = $quote->destination;
            $this->whatsappNumber = $quote->whatsapp_number ?? '';
            $this->quoteId        = $quote->id;
            $aiResult             = $quote->ai_result;
            if (empty($aiResult['proposal_structure'])) {
                $aiResult['proposal_structure'] = $this->defaultProposalStructure($aiResult);
            }
            $this->result = $aiResult;
        }
    }

    public function updatedSelectedClientId(int $value): void
    {
        if ($value > 0) {
            $client = collect($this->clients)->firstWhere('id', $value);
            if ($client) {
                $this->clientName = $client['name'];
            }
        }
    }

    public function toggleInterest(string $interest): void
    {
        if (in_array($interest, $this->interests)) {
            $this->interests = array_values(array_filter($this->interests, fn($i) => $i !== $interest));
        } else {
            $this->interests[] = $interest;
        }
    }

    public function setPlatform(string $value): void
    {
        $allowed = ['META', 'GOOGLE', 'BOTH'];
        if (in_array($value, $allowed)) {
            $this->platform = $value;
        }
    }

    public function setCampaignType(string $type): void
    {
        $allowed = ['AWARENESS', 'TRAFFIC', 'LEADS', 'CONVERSIONS', 'ENGAGEMENT'];
        if (in_array($type, $allowed)) {
            $this->campaignType = $type;
        }
    }

    public function setDestination(string $dest): void
    {
        $allowed = ['WEB', 'WHATSAPP', 'BOTH'];
        if (in_array($dest, $allowed)) {
            $this->destination = $dest;
        }
    }

    public function setIndustry(string $value): void
    {
        $this->industry = $value;
    }

    public function setAgeRange(string $range): void
    {
        $this->ageRange = $range;
    }

    public function setDurationDays(int $days): void
    {
        if (in_array($days, [15, 30, 45, 60])) {
            $this->durationDays = $days;
        }
    }

    public function updated(string $property): void
    {
        if (str_starts_with($property, 'result.desglose_precios')) {
            $this->recalcPricing();
        }
    }

    private function recalcPricing(): void
    {
        if (!isset($this->result['desglose_precios'])) return;

        $p = &$this->result['desglose_precios'];
        $p['total_agencia_cop']   = (int)($p['honorarios_gestion_cop'] ?? 0) + (int)($p['honorarios_creativos_cop'] ?? 0);
        $p['total_inversion_cop'] = (int)($p['presupuesto_pauta_cop']  ?? 0) + $p['total_agencia_cop'];
    }

    public function nextStep(): void
    {
        $this->validate($this->stepRules[$this->step] ?? []);

        if ($this->step < 5) {
            $this->step++;
        }
    }

    public function prevStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function generate(): void
    {
        $this->generateError = null;
        $this->validate($this->stepRules[5]);

        $this->generating = true;

        try {
            // TODO: Replace with queued job when background processing is implemented.
            set_time_limit(120);

            $service = app(ClaudeMetaAdsService::class);

            $industry  = $this->industry === 'Otro' ? ($this->industryOther ?: 'Otro') : $this->industry;
            $interests = array_map(
                fn($i) => $i === 'Otro' ? ($this->interestOther ?: 'Otro') : $i,
                $this->interests
            );

            $data = $service->generate([
                'client_name'     => $this->clientName,
                'industry'        => $industry,
                'budget_cop'      => $this->budgetCop,
                'duration_days'   => $this->durationDays,
                'age_range'       => $this->ageRange,
                'location'        => $this->location,
                'interests'       => $interests,
                'platform'        => $this->platform,
                'campaign_type'   => $this->campaignType,
                'destination'     => $this->destination,
                'whatsapp_number' => $this->whatsappNumber,
            ]);

            $quote = AiMetaQuote::create([
                'client_name'      => $this->clientName,
                'industry'         => $industry,
                'budget_cop'       => $this->budgetCop,
                'duration_days'    => $this->durationDays,
                'daily_budget_cop' => intval($this->budgetCop / max(1, $this->durationDays)),
                'age_range'        => $this->ageRange,
                'location'         => $this->location,
                'interests'        => $interests,
                'platform'         => $this->platform,
                'campaign_type'    => $this->campaignType,
                'destination'      => $this->destination,
                'whatsapp_number'  => $this->whatsappNumber ?: null,
                'ai_result'        => $data,
            ]);

            $this->quoteId = $quote->id;
            $data['proposal_structure'] = $this->defaultProposalStructure($data);
            $this->result  = $data;

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('MetaAds generation failed', ['exception' => $e->getMessage()]);
            $this->generateError = 'Error al generar: ' . $e->getMessage();
        } finally {
            $this->generating = false;
        }
    }

    private function defaultProposalStructure(array $data): array
    {
        $pauta = (int)($data['desglose_precios']['presupuesto_pauta_cop'] ?? $this->budgetCop);

        $campaigns = match ($this->platform) {
            'GOOGLE' => [
                ['name' => 'Performance Max', 'budget' => intval($pauta * 0.40), 'campaigns_count' => 1, 'ads_count' => 6],
                ['name' => 'Búsqueda',         'budget' => intval($pauta * 0.35), 'campaigns_count' => 1, 'ads_count' => 8],
                ['name' => 'Shopping',         'budget' => intval($pauta * 0.25), 'campaigns_count' => 1, 'ads_count' => 4],
            ],
            'BOTH' => [
                ['name' => 'Meta — Consumo',        'budget' => intval($pauta * 0.25), 'campaigns_count' => 2, 'ads_count' => 10],
                ['name' => 'Meta — Corporativo',    'budget' => intval($pauta * 0.25), 'campaigns_count' => 1, 'ads_count' => 6],
                ['name' => 'Google — Perf. Max',    'budget' => intval($pauta * 0.30), 'campaigns_count' => 1, 'ads_count' => 6],
                ['name' => 'Google — Búsqueda',     'budget' => intval($pauta * 0.20), 'campaigns_count' => 1, 'ads_count' => 8],
            ],
            default => [
                ['name' => 'Consumo',      'budget' => intval($pauta * 0.40), 'campaigns_count' => 2, 'ads_count' => 10],
                ['name' => 'Corporativo',  'budget' => intval($pauta * 0.40), 'campaigns_count' => 1, 'ads_count' => 10],
                ['name' => 'Marcas / Web', 'budget' => intval($pauta * 0.20), 'campaigns_count' => 1, 'ads_count' => 3],
            ],
        };

        return [
            'campaigns' => $campaigns,
            'benefits'  => [
                'Seguimiento mensual constante al desempeño de cada campaña con registro continuo de avances y resultados.',
                'A mitad de mes se inicia el diseño de los nuevos anuncios del mes siguiente, para que las campañas inicien a tiempo.',
                'Al cierre de mes se entrega reporte conciso de rendimiento por campaña con métricas del periodo.',
            ],
        ];
    }

    public function addProposalCampaign(): void
    {
        $this->result['proposal_structure']['campaigns'][] = [
            'name' => '', 'budget' => 0, 'campaigns_count' => 1, 'ads_count' => 6,
        ];
    }

    public function removeProposalCampaign(int $index): void
    {
        array_splice($this->result['proposal_structure']['campaigns'], $index, 1);
        $this->result['proposal_structure']['campaigns'] = array_values(
            $this->result['proposal_structure']['campaigns']
        );
    }

    public function addProposalBenefit(): void
    {
        $this->result['proposal_structure']['benefits'][] = '';
    }

    public function removeProposalBenefit(int $index): void
    {
        array_splice($this->result['proposal_structure']['benefits'], $index, 1);
        $this->result['proposal_structure']['benefits'] = array_values(
            $this->result['proposal_structure']['benefits']
        );
    }

    public function saveQuote(): void
    {
        if (!$this->quoteId || !$this->result) return;

        AiMetaQuote::where('id', $this->quoteId)
            ->update(['ai_result' => $this->result]);

        $this->dispatch('notify', message: 'Cambios guardados correctamente.');
    }

    public function resetWizard(): void
    {
        $this->step           = 1;
        $this->clientName     = '';
        $this->industry       = '';
        $this->industryOther  = '';
        $this->budgetCop      = 0;
        $this->durationDays   = 30;
        $this->ageRange       = '';
        $this->location       = '';
        $this->interests      = [];
        $this->interestOther  = '';
        $this->platform       = 'META';
        $this->campaignType   = '';
        $this->destination    = '';
        $this->whatsappNumber = '';
        $this->generating     = false;
        $this->quoteId          = null;
        $this->result           = null;
        $this->generateError    = null;
        $this->selectedClientId = 0;
    }

    public function render()
    {
        return view('livewire.meta-ads.wizard');
    }
}
