# Meta Ads AI Wizard Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build an AI-powered Meta Ads campaign quote generator inside the Kamo platform, accessible as a sidebar sub-link under "Servicios", powered by Claude API, with wizard UI, database persistence, and PDF export.

**Architecture:** A Livewire full-page component (`MetaAds\Wizard`) collects 5 steps of input, calls `ClaudeMetaAdsService` which wraps the Anthropic PHP SDK, saves the result as JSON in `ai_meta_quotes`, and renders the result in-page. A companion `MetaAds\History` component shows past quotes. PDF export uses the already-installed `barryvdh/laravel-dompdf`.

**Tech Stack:** PHP 8.3 · Laravel 13 · Livewire 3 · `anthropic/anthropic-sdk-php` · `barryvdh/laravel-dompdf` (already installed) · PHPUnit 12

---

## File Map

| Action | Path | Responsibility |
|--------|------|----------------|
| Create | `app/Services/ClaudeMetaAdsService.php` | Wraps Anthropic SDK, builds prompt, parses JSON |
| Create | `app/Models/AiMetaQuote.php` | Eloquent model for saved quotes |
| Create | `app/Livewire/MetaAds/Wizard.php` | 5-step wizard + generate + result display |
| Create | `app/Livewire/MetaAds/History.php` | Paginated table of saved quotes |
| Create | `app/Http/Controllers/MetaAdsController.php` | PDF download only |
| Create | `resources/views/livewire/meta-ads/wizard.blade.php` | Wizard UI + result view |
| Create | `resources/views/livewire/meta-ads/history.blade.php` | History table UI |
| Create | `resources/views/pdf/meta-ads-quote.blade.php` | White-theme PDF template |
| Create | `database/migrations/xxxx_create_ai_meta_quotes_table.php` | DB schema |
| Create | `tests/Unit/ClaudeMetaAdsServiceTest.php` | Unit test for service |
| Create | `tests/Feature/MetaAds/WizardTest.php` | Feature tests for wizard component |
| Create | `tests/Feature/MetaAds/HistoryTest.php` | Feature tests for history component |
| Modify | `config/services.php` | Add anthropic key entry |
| Modify | `app/Providers/AppServiceProvider.php` | Bind Anthropic client to container |
| Modify | `routes/web.php` | Add 3 new routes |
| Modify | `resources/views/components/layouts/app.blade.php` | Add sidebar sub-links |

---

## Task 1: Install Anthropic SDK + Configure

**Files:**
- Modify: `config/services.php`
- Modify: `app/Providers/AppServiceProvider.php`
- Modify: `.env` (add key — not committed)

- [ ] **Step 1: Install the Anthropic PHP SDK**

```bash
cd apps/laravel
composer require anthropic/anthropic-sdk-php
```

Expected: `anthropic/anthropic-sdk-php` added to `composer.json` and autoloaded.

- [ ] **Step 2: Add Anthropic key to config/services.php**

Open `apps/laravel/config/services.php` and add after the `'slack'` block:

```php
    'anthropic' => [
        'key' => env('ANTHROPIC_API_KEY'),
    ],
```

- [ ] **Step 3: Bind Anthropic client in AppServiceProvider**

Open `apps/laravel/app/Providers/AppServiceProvider.php` and replace with:

```php
<?php

namespace App\Providers;

use Anthropic\Anthropic;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\Anthropic\Client::class, function () {
            return Anthropic::client(config('services.anthropic.key'));
        });
    }

    public function boot(): void
    {
        //
    }
}
```

- [ ] **Step 4: Add ANTHROPIC_API_KEY to .env**

Add to `apps/laravel/.env`:
```
ANTHROPIC_API_KEY=sk-ant-YOUR_KEY_HERE
```

- [ ] **Step 5: Verify installation**

```bash
cd apps/laravel
php artisan tinker --execute="echo class_exists(\Anthropic\Anthropic::class) ? 'OK' : 'FAIL';"
```

Expected output: `OK`

- [ ] **Step 6: Commit**

```bash
git -C "apps/laravel" add composer.json composer.lock config/services.php app/Providers/AppServiceProvider.php
git -C "apps/laravel" commit -m "feat: install anthropic SDK and configure client binding"
```

---

## Task 2: Migration + AiMetaQuote Model

**Files:**
- Create: `database/migrations/xxxx_create_ai_meta_quotes_table.php`
- Create: `app/Models/AiMetaQuote.php`

- [ ] **Step 1: Generate migration**

```bash
cd apps/laravel
php artisan make:migration create_ai_meta_quotes_table
```

- [ ] **Step 2: Write migration**

Open the generated file in `apps/laravel/database/migrations/` (ends in `_create_ai_meta_quotes_table.php`) and replace with:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_meta_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('client_name');
            $table->string('industry')->nullable();
            $table->unsignedInteger('budget_cop');
            $table->unsignedTinyInteger('duration_days');
            $table->unsignedInteger('daily_budget_cop');
            $table->string('age_range');
            $table->string('location');
            $table->json('interests');
            $table->string('campaign_type');
            $table->string('destination');
            $table->string('whatsapp_number')->nullable();
            $table->json('ai_result');
            $table->timestamps();

            $table->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_meta_quotes');
    }
};
```

- [ ] **Step 3: Run migration**

```bash
cd apps/laravel
php artisan migrate
```

Expected: `ai_meta_quotes` table created.

- [ ] **Step 4: Create AiMetaQuote model**

Create `apps/laravel/app/Models/AiMetaQuote.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiMetaQuote extends Model
{
    protected $fillable = [
        'company_id',
        'client_name',
        'industry',
        'budget_cop',
        'duration_days',
        'daily_budget_cop',
        'age_range',
        'location',
        'interests',
        'campaign_type',
        'destination',
        'whatsapp_number',
        'ai_result',
    ];

    protected $casts = [
        'interests' => 'array',
        'ai_result'  => 'array',
        'budget_cop' => 'integer',
        'duration_days' => 'integer',
        'daily_budget_cop' => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getDailyBudgetFormattedAttribute(): string
    {
        return '$' . number_format($this->daily_budget_cop, 0, ',', '.');
    }

    public function getBudgetFormattedAttribute(): string
    {
        return '$' . number_format($this->budget_cop, 0, ',', '.');
    }
}
```

- [ ] **Step 5: Commit**

```bash
git -C "apps/laravel" add database/migrations app/Models/AiMetaQuote.php
git -C "apps/laravel" commit -m "feat: add ai_meta_quotes migration and model"
```

---

## Task 3: ClaudeMetaAdsService + Unit Test

**Files:**
- Create: `app/Services/ClaudeMetaAdsService.php`
- Create: `tests/Unit/ClaudeMetaAdsServiceTest.php`

- [ ] **Step 1: Write failing unit test**

Create `apps/laravel/tests/Unit/ClaudeMetaAdsServiceTest.php`:

```php
<?php

namespace Tests\Unit;

use Anthropic\Client;
use Anthropic\Responses\Messages\CreateResponse;
use Anthropic\Responses\Messages\CreateResponseContent;
use App\Services\ClaudeMetaAdsService;
use Mockery;
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

    public function test_generate_returns_structured_array(): void
    {
        $mockContent = Mockery::mock(CreateResponseContent::class);
        $mockContent->text = json_encode($this->fakeApiResponse);

        $mockResponse = Mockery::mock(CreateResponse::class);
        $mockResponse->content = [$mockContent];

        $mockMessages = Mockery::mock();
        $mockMessages->shouldReceive('create')->once()->andReturn($mockResponse);

        $mockClient = Mockery::mock(Client::class);
        $mockClient->shouldReceive('messages')->once()->andReturn($mockMessages);

        $this->app->instance(Client::class, $mockClient);

        $service = app(ClaudeMetaAdsService::class);
        $result = $service->generate($this->sampleInput);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('resumen_ejecutivo', $result);
        $this->assertArrayHasKey('estructura_campana', $result);
        $this->assertArrayHasKey('desglose_precios', $result);
        $this->assertArrayHasKey('total_inversion_cop', $result['desglose_precios']);
    }

    public function test_generate_throws_on_invalid_json(): void
    {
        $mockContent = Mockery::mock(CreateResponseContent::class);
        $mockContent->text = 'not valid json {{{';

        $mockResponse = Mockery::mock(CreateResponse::class);
        $mockResponse->content = [$mockContent];

        $mockMessages = Mockery::mock();
        $mockMessages->shouldReceive('create')->once()->andReturn($mockResponse);

        $mockClient = Mockery::mock(Client::class);
        $mockClient->shouldReceive('messages')->once()->andReturn($mockMessages);

        $this->app->instance(Client::class, $mockClient);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/JSON inválido/');

        app(ClaudeMetaAdsService::class)->generate($this->sampleInput);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
```

- [ ] **Step 2: Run test to confirm it fails**

```bash
cd apps/laravel
php artisan test tests/Unit/ClaudeMetaAdsServiceTest.php --verbose
```

Expected: FAIL — `ClaudeMetaAdsService not found`

- [ ] **Step 3: Create ClaudeMetaAdsService**

Create `apps/laravel/app/Services/ClaudeMetaAdsService.php`:

```php
<?php

namespace App\Services;

use Anthropic\Client;

class ClaudeMetaAdsService
{
    public function __construct(private readonly Client $anthropic) {}

    private const SYSTEM_PROMPT = <<<'PROMPT'
You are a Meta Ads strategy expert for a Colombian digital agency called Kamo Agency.
You generate professional campaign strategies following Meta Ads real structure:
Campaign → Ad Sets → Ads.

Rules:
- All monetary values in COP (Colombian Pesos)
- Use real Meta Ads terminology (placements, objectives, optimization events, CTAs)
- Budget estimates must be realistic for the Colombian market
- Always structure campaigns with TOFU/MOFU/BOFU phases when budget allows
- Minimum recommended daily budget per ad set: 5,000 COP
- Agency gestión fee: 30–40% of pauta budget
- Agency creativos fee: 20–30% of pauta budget
- Return ONLY valid JSON matching the exact schema provided. No markdown, no explanation outside JSON.
PROMPT;

    public function generate(array $data): array
    {
        $response = $this->anthropic->messages()->create([
            'model'      => 'claude-sonnet-4-6',
            'max_tokens' => 2048,
            'system'     => [
                [
                    'type'          => 'text',
                    'text'          => self::SYSTEM_PROMPT,
                    'cache_control' => ['type' => 'ephemeral'],
                ],
            ],
            'messages' => [
                ['role' => 'user', 'content' => $this->buildPrompt($data)],
            ],
        ]);

        $text   = $response->content[0]->text;
        $result = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Claude devolvió JSON inválido: ' . json_last_error_msg());
        }

        return $result;
    }

    private function buildPrompt(array $d): string
    {
        $interests    = implode(', ', $d['interests']);
        $dailyBudget  = number_format((int) ($d['budget_cop'] / $d['duration_days']), 0, ',', '.');
        $budgetFmt    = number_format($d['budget_cop'], 0, ',', '.');
        $whatsapp     = $d['whatsapp_number'] ? " (WhatsApp: {$d['whatsapp_number']})" : '';
        $schema       = $this->schema();

        return <<<PROMPT
Generate a Meta Ads campaign strategy for:
- Client: {$d['client_name']} (Industry: {$d['industry']})
- Total budget: \${$budgetFmt} COP over {$d['duration_days']} days (\${$dailyBudget} COP/day)
- Target audience: {$d['age_range']}, {$d['location']}, interests: {$interests}
- Campaign objective: {$d['campaign_type']}
- Destination: {$d['destination']}{$whatsapp}

Return ONLY JSON matching exactly this schema:
{$schema}
PROMPT;
    }

    private function schema(): string
    {
        return json_encode([
            'resumen_ejecutivo' => 'string',
            'estructura_campana' => [
                'objetivo'       => 'string (Meta campaign objective)',
                'nombre_campana' => 'string',
                'ad_sets'        => [[
                    'nombre'                => 'string',
                    'audiencia_descripcion' => 'string',
                    'placements'            => ['string'],
                    'presupuesto_diario_cop' => 'integer',
                    'optimizacion'          => 'string',
                    'duracion_dias'         => 'integer',
                ]],
            ],
            'creativos' => [
                'formatos'         => ['string'],
                'copies_sugeridos' => ['string'],
                'call_to_action'   => 'string (Meta CTA enum)',
                'recomendaciones'  => 'string',
            ],
            'metricas_estimadas' => [
                'alcance_diario'     => 'string',
                'cpm_estimado_cop'   => 'string',
                'ctr_objetivo'       => 'string',
                'cpc_estimado_cop'   => 'string',
                'frecuencia_sugerida' => 'string',
            ],
            'fases' => [[
                'fase'            => 'TOFU|MOFU|BOFU',
                'dias'            => 'string',
                'objetivo'        => 'string',
                'presupuesto_pct' => 'integer',
            ]],
            'recomendaciones_pixel' => ['string'],
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
```

- [ ] **Step 4: Run tests — confirm both pass**

```bash
cd apps/laravel
php artisan test tests/Unit/ClaudeMetaAdsServiceTest.php --verbose
```

Expected: 2 tests PASS

- [ ] **Step 5: Commit**

```bash
git -C "apps/laravel" add app/Services/ClaudeMetaAdsService.php tests/Unit/ClaudeMetaAdsServiceTest.php
git -C "apps/laravel" commit -m "feat: add ClaudeMetaAdsService with unit tests"
```

---

## Task 4: MetaAds\Wizard Livewire Class + Feature Tests

**Files:**
- Create: `app/Livewire/MetaAds/Wizard.php`
- Create: `tests/Feature/MetaAds/WizardTest.php`

- [ ] **Step 1: Write failing feature tests**

Create `apps/laravel/tests/Feature/MetaAds/WizardTest.php`:

```php
<?php

namespace Tests\Feature\MetaAds;

use App\Livewire\MetaAds\Wizard;
use App\Models\AiMetaQuote;
use App\Models\Company;
use App\Models\User;
use App\Services\ClaudeMetaAdsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WizardTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Company $company;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user    = User::factory()->create();
        $this->company = Company::factory()->create();
        $this->user->companies()->attach($this->company->id);
    }

    public function test_wizard_page_requires_auth(): void
    {
        $this->get(route('meta-ads.wizard'))->assertRedirect(route('login'));
    }

    public function test_wizard_page_loads_for_authenticated_user(): void
    {
        $this->actingAs($this->user)
            ->get(route('meta-ads.wizard'))
            ->assertOk()
            ->assertSeeLivewire(Wizard::class);
    }

    public function test_initial_step_is_one(): void
    {
        $this->actingAs($this->user);

        Livewire::test(Wizard::class)
            ->assertSet('step', 1);
    }

    public function test_next_step_advances_when_step1_valid(): void
    {
        $this->actingAs($this->user);

        Livewire::test(Wizard::class)
            ->set('clientName', 'Empresa ABC')
            ->set('industry', 'Moda')
            ->call('nextStep')
            ->assertSet('step', 2)
            ->assertHasNoErrors();
    }

    public function test_next_step_fails_validation_when_client_name_empty(): void
    {
        $this->actingAs($this->user);

        Livewire::test(Wizard::class)
            ->set('clientName', '')
            ->call('nextStep')
            ->assertSet('step', 1)
            ->assertHasErrors(['clientName']);
    }

    public function test_prev_step_goes_back(): void
    {
        $this->actingAs($this->user);

        Livewire::test(Wizard::class)
            ->set('step', 3)
            ->call('prevStep')
            ->assertSet('step', 2);
    }

    public function test_budget_minimum_is_100000_cop(): void
    {
        $this->actingAs($this->user);

        Livewire::test(Wizard::class)
            ->set('step', 2)
            ->set('budgetCop', 50000)
            ->set('durationDays', 15)
            ->call('nextStep')
            ->assertHasErrors(['budgetCop']);
    }

    public function test_generate_saves_quote_and_sets_result(): void
    {
        $this->actingAs($this->user);

        $fakeResult = [
            'resumen_ejecutivo' => 'Test strategy',
            'estructura_campana' => ['objetivo' => 'CONVERSIONS', 'nombre_campana' => 'Test', 'ad_sets' => []],
            'creativos' => ['formatos' => [], 'copies_sugeridos' => [], 'call_to_action' => 'LEARN_MORE', 'recomendaciones' => ''],
            'metricas_estimadas' => ['alcance_diario' => '1k', 'cpm_estimado_cop' => '$5k', 'ctr_objetivo' => '2%', 'cpc_estimado_cop' => '$300', 'frecuencia_sugerida' => '2x'],
            'fases' => [],
            'recomendaciones_pixel' => [],
            'desglose_precios' => ['presupuesto_pauta_cop' => 225000, 'honorarios_gestion_cop' => 45000, 'honorarios_creativos_cop' => 30000, 'total_agencia_cop' => 75000, 'total_inversion_cop' => 300000],
        ];

        $this->mock(ClaudeMetaAdsService::class)
            ->shouldReceive('generate')
            ->once()
            ->andReturn($fakeResult);

        Livewire::test(Wizard::class)
            ->set('step', 5)
            ->set('clientName', 'Empresa ABC')
            ->set('industry', 'Moda')
            ->set('budgetCop', 300000)
            ->set('durationDays', 15)
            ->set('ageRange', '25-34')
            ->set('location', 'Cartagena')
            ->set('interests', ['Moda'])
            ->set('campaignType', 'CONVERSIONS')
            ->set('destination', 'WEB')
            ->call('generate')
            ->assertSet('generating', false)
            ->assertNotNull('result');

        $this->assertDatabaseHas('ai_meta_quotes', [
            'client_name'   => 'Empresa ABC',
            'campaign_type' => 'CONVERSIONS',
        ]);
    }
}
```

- [ ] **Step 2: Run tests to confirm they fail**

```bash
cd apps/laravel
php artisan test tests/Feature/MetaAds/WizardTest.php --verbose
```

Expected: FAIL — `Wizard class not found`

- [ ] **Step 3: Create Wizard Livewire class**

Create `apps/laravel/app/Livewire/MetaAds/Wizard.php`:

```php
<?php

namespace App\Livewire\MetaAds;

use App\Models\AiMetaQuote;
use App\Services\ClaudeMetaAdsService;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Wizard extends Component
{
    public int    $step         = 1;
    public string $clientName   = '';
    public string $industry     = '';
    public int    $budgetCop    = 0;
    public int    $durationDays = 30;
    public string $ageRange     = '';
    public string $location     = '';
    public array  $interests    = [];
    public string $campaignType = '';
    public string $destination  = '';
    public string $whatsappNumber = '';
    public bool   $generating   = false;

    #[Locked]
    public ?int   $quoteId      = null;
    public ?array $result       = null;

    #[Locked]
    public int $companyId = 0;

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

    public function mount(): void
    {
        $this->companyId = auth()->user()->companies()->first()?->id ?? 0;
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
        $this->validate($this->stepRules[5]);

        $this->generating = true;

        try {
            set_time_limit(120);

            $service = app(ClaudeMetaAdsService::class);

            $data = $service->generate([
                'client_name'     => $this->clientName,
                'industry'        => $this->industry,
                'budget_cop'      => $this->budgetCop,
                'duration_days'   => $this->durationDays,
                'age_range'       => $this->ageRange,
                'location'        => $this->location,
                'interests'       => $this->interests,
                'campaign_type'   => $this->campaignType,
                'destination'     => $this->destination,
                'whatsapp_number' => $this->whatsappNumber,
            ]);

            $quote = AiMetaQuote::create([
                'company_id'       => $this->companyId,
                'client_name'      => $this->clientName,
                'industry'         => $this->industry,
                'budget_cop'       => $this->budgetCop,
                'duration_days'    => $this->durationDays,
                'daily_budget_cop' => intval($this->budgetCop / $this->durationDays),
                'age_range'        => $this->ageRange,
                'location'         => $this->location,
                'interests'        => $this->interests,
                'campaign_type'    => $this->campaignType,
                'destination'      => $this->destination,
                'whatsapp_number'  => $this->whatsappNumber ?: null,
                'ai_result'        => $data,
            ]);

            $this->quoteId = $quote->id;
            $this->result  = $data;

        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Error: ' . $e->getMessage());
        } finally {
            $this->generating = false;
        }
    }

    public function resetWizard(): void
    {
        $this->step           = 1;
        $this->clientName     = '';
        $this->industry       = '';
        $this->budgetCop      = 0;
        $this->durationDays   = 30;
        $this->ageRange       = '';
        $this->location       = '';
        $this->interests      = [];
        $this->campaignType   = '';
        $this->destination    = '';
        $this->whatsappNumber = '';
        $this->generating     = false;
        $this->quoteId        = null;
        $this->result         = null;
    }

    public function render()
    {
        return view('livewire.meta-ads.wizard');
    }
}
```

- [ ] **Step 4: Run tests — confirm they pass**

```bash
cd apps/laravel
php artisan test tests/Feature/MetaAds/WizardTest.php --verbose
```

Expected: All tests PASS (the view doesn't exist yet — tests that render will fail; continue to next step)

> **Note:** If tests fail because the blade view is missing, create a temporary stub at `resources/views/livewire/meta-ads/wizard.blade.php` with `<div></div>` to pass tests, then replace in Task 5.

```bash
mkdir -p apps/laravel/resources/views/livewire/meta-ads
echo "<div></div>" > apps/laravel/resources/views/livewire/meta-ads/wizard.blade.php
```

Then re-run tests.

- [ ] **Step 5: Commit**

```bash
git -C "apps/laravel" add app/Livewire/MetaAds/Wizard.php tests/Feature/MetaAds/WizardTest.php resources/views/livewire/meta-ads/wizard.blade.php
git -C "apps/laravel" commit -m "feat: add MetaAds Wizard Livewire component with feature tests"
```

---

## Task 5: Wizard Blade View

**Files:**
- Modify: `resources/views/livewire/meta-ads/wizard.blade.php`

- [ ] **Step 1: Write the complete wizard blade view**

Replace the stub in `apps/laravel/resources/views/livewire/meta-ads/wizard.blade.php` with:

```blade
<div>
<style>
    .wizard-shell {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 32px;
        align-items: start;
    }
    @media (max-width: 768px) {
        .wizard-shell { grid-template-columns: 1fr; }
        .wizard-sidebar { display: none; }
    }
    .wizard-sidebar {
        background: rgba(20,20,20,0.95);
        border: 1px solid var(--border-subtle);
        border-radius: 16px;
        padding: 24px 16px;
        position: sticky;
        top: 32px;
    }
    .wizard-step-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 10px;
        margin-bottom: 4px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-muted);
        transition: all 0.2s ease;
    }
    .wizard-step-item.active {
        background: rgba(230,57,70,0.08);
        border: 1px solid rgba(230,57,70,0.2);
        color: var(--red-primary);
    }
    .wizard-step-item.done { color: var(--text-secondary); }
    .wizard-step-num {
        width: 24px; height: 24px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700;
        flex-shrink: 0;
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--border-subtle);
        color: var(--text-muted);
    }
    .wizard-step-item.active .wizard-step-num {
        background: var(--red-primary); border-color: var(--red-primary); color: white;
    }
    .wizard-step-item.done .wizard-step-num {
        background: rgba(16,185,129,0.15); border-color: rgba(16,185,129,0.3); color: #10B981;
    }
    .wizard-card {
        background: rgba(20,20,20,0.95);
        border: 1px solid var(--border-subtle);
        border-radius: 16px;
        padding: 32px;
    }
    .wizard-step-label {
        font-size: 11px; color: var(--red-primary);
        text-transform: uppercase; letter-spacing: 0.12em; font-weight: 700;
        margin-bottom: 8px;
    }
    .wizard-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 22px; font-weight: 700; color: var(--text-primary);
        margin-bottom: 6px;
    }
    .wizard-subtitle { font-size: 13px; color: var(--text-muted); margin-bottom: 28px; }
    .chip-grid { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
    .chip {
        padding: 8px 16px; border-radius: 999px;
        border: 1px solid var(--border-default);
        background: rgba(255,255,255,0.03);
        color: var(--text-secondary); font-size: 13px; font-weight: 500;
        cursor: pointer; transition: all 0.2s ease; user-select: none;
    }
    .chip:hover { border-color: rgba(230,57,70,0.4); color: var(--text-primary); }
    .chip.selected {
        background: rgba(230,57,70,0.12);
        border-color: rgba(230,57,70,0.5);
        color: var(--red-primary); font-weight: 600;
    }
    .wizard-nav { display: flex; justify-content: space-between; align-items: center; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border-subtle); }
    .daily-calc { font-size: 12px; color: var(--text-muted); margin-top: 8px; }
    .daily-calc span { color: var(--red-primary); font-weight: 600; }

    /* Result view */
    .result-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 28px; flex-wrap: wrap; }
    .result-badge { padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; background: rgba(230,57,70,0.12); border: 1px solid rgba(230,57,70,0.3); color: var(--red-primary); }
    .result-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    @media (max-width: 640px) { .result-grid { grid-template-columns: 1fr; } }
    .result-card { background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle); border-radius: 14px; padding: 20px; }
    .result-section-label { font-size: 10px; color: var(--red-primary); text-transform: uppercase; letter-spacing: 0.12em; font-weight: 700; margin-bottom: 12px; }
    .adset-row { background: rgba(255,255,255,0.03); border-radius: 8px; padding: 10px 14px; margin-bottom: 6px; }
    .phase-bar { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
    .phase-track { flex: 1; height: 6px; background: rgba(255,255,255,0.06); border-radius: 3px; overflow: hidden; }
    .phase-fill { height: 100%; background: var(--red-primary); border-radius: 3px; }
    .pricing-table { width: 100%; margin-top: 16px; }
    .pricing-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border-subtle); font-size: 14px; color: var(--text-secondary); }
    .pricing-row.total { font-weight: 700; color: var(--red-primary); font-size: 16px; border-bottom: none; padding-top: 14px; }
    .metric-chip { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 8px; font-size: 12px; font-weight: 500; background: rgba(255,255,255,0.04); border: 1px solid var(--border-subtle); color: var(--text-secondary); margin-right: 6px; margin-bottom: 6px; }
    .generating-overlay { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 320px; gap: 16px; }
    .spinner { width: 48px; height: 48px; border: 3px solid rgba(230,57,70,0.2); border-top-color: var(--red-primary); border-radius: 50%; animation: spin 0.8s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>

{{-- HEADER --}}
<div class="crud-header">
    <div>
        <h1 class="crud-title">IA Meta Ads</h1>
        <p class="crud-subtitle">Genera cotizaciones de campaña con inteligencia artificial</p>
    </div>
    <a wire:navigate href="{{ route('meta-ads.history') }}" class="btn btn-secondary">
        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Ver historial
    </a>
</div>

{{-- GENERATING OVERLAY --}}
@if($generating)
    <div class="wizard-card">
        <div class="generating-overlay">
            <div class="spinner"></div>
            <p style="color:var(--text-secondary);font-size:15px;">Claude está diseñando tu estrategia...</p>
            <p style="color:var(--text-muted);font-size:12px;">Esto puede tomar 15–25 segundos</p>
        </div>
    </div>

{{-- RESULT VIEW --}}
@elseif($result)
    <div>
        {{-- Result header --}}
        <div class="result-header">
            <div>
                <h2 style="font-family:'Space Grotesk',sans-serif;font-size:24px;font-weight:700;color:var(--text-primary);margin:0 0 6px;">
                    {{ $clientName }}
                </h2>
                <div style="display:flex;flex-wrap:wrap;gap:6px;align-items:center;">
                    <span class="result-badge">✦ IA Generado</span>
                    <span class="metric-chip">💰 ${{ number_format($budgetCop,0,',','.') }} COP / {{ $durationDays }} días</span>
                    <span class="metric-chip">🎯 {{ $campaignType }}</span>
                    <span class="metric-chip">📍 {{ $destination }}</span>
                </div>
            </div>
            <div style="display:flex;gap:10px;flex-shrink:0;">
                <a href="{{ $quoteId ? route('meta-ads.pdf', $quoteId) : '#' }}"
                   target="_blank" class="btn btn-secondary">
                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Exportar PDF
                </a>
                <button wire:click="resetWizard" class="btn btn-primary">
                    Nueva cotización
                </button>
            </div>
        </div>

        {{-- Resumen ejecutivo --}}
        <div class="result-card" style="margin-bottom:16px;">
            <div class="result-section-label">Resumen ejecutivo</div>
            <p style="color:var(--text-secondary);font-size:14px;line-height:1.65;margin:0;">{{ $result['resumen_ejecutivo'] }}</p>
        </div>

        {{-- Grid: Estructura + Creativos --}}
        <div class="result-grid">
            {{-- Estructura campaña --}}
            <div class="result-card">
                <div class="result-section-label">Estructura de campaña</div>
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:10px;">
                    Objetivo: <strong style="color:var(--text-primary);">{{ $result['estructura_campana']['objetivo'] }}</strong>
                </div>
                @foreach($result['estructura_campana']['ad_sets'] as $adset)
                    <div class="adset-row">
                        <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:4px;">{{ $adset['nombre'] }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $adset['audiencia_descripcion'] }}</div>
                        <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">
                            Placements: {{ implode(', ', $adset['placements']) }}
                        </div>
                        <div style="font-size:12px;color:var(--red-primary);font-weight:600;margin-top:4px;">
                            ${{ number_format($adset['presupuesto_diario_cop'],0,',','.') }}/día · {{ $adset['duracion_dias'] }} días
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Creativos --}}
            <div class="result-card">
                <div class="result-section-label">Creativos</div>
                <div style="margin-bottom:10px;">
                    @foreach($result['creativos']['formatos'] as $fmt)
                        <span class="metric-chip">{{ $fmt }}</span>
                    @endforeach
                </div>
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:8px;">CTA: <strong style="color:var(--text-primary);">{{ $result['creativos']['call_to_action'] }}</strong></div>
                @foreach($result['creativos']['copies_sugeridos'] as $copy)
                    <div style="background:rgba(255,255,255,0.03);border-radius:6px;padding:8px 10px;margin-bottom:6px;font-size:12px;color:var(--text-secondary);">
                        "{{ $copy }}"
                    </div>
                @endforeach
                <p style="font-size:11px;color:var(--text-muted);margin-top:8px;">{{ $result['creativos']['recomendaciones'] }}</p>
            </div>
        </div>

        {{-- Grid: Métricas + Fases --}}
        <div class="result-grid" style="margin-bottom:16px;">
            {{-- Métricas estimadas --}}
            <div class="result-card">
                <div class="result-section-label">Métricas estimadas</div>
                @php $m = $result['metricas_estimadas']; @endphp
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div><div style="font-size:10px;color:var(--text-muted);">Alcance/día</div><div style="font-size:14px;font-weight:600;color:var(--text-primary);">{{ $m['alcance_diario'] }}</div></div>
                    <div><div style="font-size:10px;color:var(--text-muted);">CPM</div><div style="font-size:14px;font-weight:600;color:var(--text-primary);">{{ $m['cpm_estimado_cop'] }}</div></div>
                    <div><div style="font-size:10px;color:var(--text-muted);">CTR objetivo</div><div style="font-size:14px;font-weight:600;color:var(--text-primary);">{{ $m['ctr_objetivo'] }}</div></div>
                    <div><div style="font-size:10px;color:var(--text-muted);">CPC</div><div style="font-size:14px;font-weight:600;color:var(--text-primary);">{{ $m['cpc_estimado_cop'] }}</div></div>
                    <div><div style="font-size:10px;color:var(--text-muted);">Frecuencia</div><div style="font-size:14px;font-weight:600;color:var(--text-primary);">{{ $m['frecuencia_sugerida'] }}</div></div>
                </div>
            </div>

            {{-- Fases --}}
            <div class="result-card">
                <div class="result-section-label">Fases de la campaña</div>
                @foreach($result['fases'] as $fase)
                    <div class="phase-bar">
                        <div style="width:52px;font-size:11px;font-weight:700;color:var(--red-primary);">{{ $fase['fase'] }}</div>
                        <div class="phase-track">
                            <div class="phase-fill" style="width:{{ $fase['presupuesto_pct'] }}%;"></div>
                        </div>
                        <div style="font-size:11px;color:var(--text-muted);white-space:nowrap;">{{ $fase['presupuesto_pct'] }}% · {{ $fase['dias'] }}d</div>
                    </div>
                    <div style="font-size:11px;color:var(--text-muted);margin-bottom:10px;padding-left:62px;">{{ $fase['objetivo'] }}</div>
                @endforeach
            </div>
        </div>

        {{-- Pixel recommendations --}}
        @if(!empty($result['recomendaciones_pixel']))
            <div class="result-card" style="margin-bottom:16px;">
                <div class="result-section-label">Recomendaciones Pixel</div>
                @foreach($result['recomendaciones_pixel'] as $rec)
                    <div style="display:flex;align-items:center;gap:8px;padding:5px 0;font-size:13px;color:var(--text-secondary);">
                        <svg style="width:14px;height:14px;flex-shrink:0;color:var(--red-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                        </svg>
                        {{ $rec }}
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Pricing table --}}
        <div class="result-card">
            <div class="result-section-label">Desglose financiero</div>
            @php $p = $result['desglose_precios']; @endphp
            <div class="pricing-table">
                <div class="pricing-row">
                    <span>Presupuesto en pauta (Meta)</span>
                    <span>${{ number_format($p['presupuesto_pauta_cop'],0,',','.') }} COP</span>
                </div>
                <div class="pricing-row">
                    <span>Honorarios gestión de campaña</span>
                    <span>${{ number_format($p['honorarios_gestion_cop'],0,',','.') }} COP</span>
                </div>
                <div class="pricing-row">
                    <span>Honorarios creativos</span>
                    <span>${{ number_format($p['honorarios_creativos_cop'],0,',','.') }} COP</span>
                </div>
                <div class="pricing-row" style="color:var(--text-secondary);font-weight:600;border-top:1px solid var(--border-default);margin-top:4px;padding-top:12px;">
                    <span>Total agencia</span>
                    <span>${{ number_format($p['total_agencia_cop'],0,',','.') }} COP</span>
                </div>
                <div class="pricing-row total">
                    <span>TOTAL INVERSIÓN</span>
                    <span>${{ number_format($p['total_inversion_cop'],0,',','.') }} COP</span>
                </div>
            </div>
        </div>
    </div>

{{-- WIZARD STEPS --}}
@else
    <div class="wizard-shell">
        {{-- Sidebar --}}
        <aside class="wizard-sidebar">
            <div style="font-size:10px;color:var(--text-subtle);text-transform:uppercase;letter-spacing:0.15em;font-weight:700;margin-bottom:12px;padding:0 4px;">Pasos</div>
            @php
                $steps = ['Cliente', 'Presupuesto', 'Audiencia', 'Campaña', 'Destino'];
            @endphp
            @foreach($steps as $i => $label)
                @php $num = $i + 1; @endphp
                <div class="wizard-step-item {{ $step === $num ? 'active' : ($step > $num ? 'done' : '') }}">
                    <div class="wizard-step-num">
                        @if($step > $num) ✓ @else {{ $num }} @endif
                    </div>
                    {{ $label }}
                </div>
            @endforeach
        </aside>

        {{-- Content area --}}
        <div class="wizard-card">

            {{-- Step 1: Cliente --}}
            @if($step === 1)
                <div class="wizard-step-label">Paso 1 de 5</div>
                <h2 class="wizard-title">¿Para quién es la campaña?</h2>
                <p class="wizard-subtitle">Ingresa el nombre del cliente y el sector de su negocio.</p>

                <div class="form-group">
                    <label class="form-label">Nombre del cliente o empresa <span class="required">*</span></label>
                    <input wire:model="clientName" type="text" class="form-input" placeholder="Ej: Tienda Moda Bella" />
                    @error('clientName') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Industria / Sector</label>
                    <div class="chip-grid">
                        @foreach(['Restaurante','Moda','Salud','Inmobiliaria','Educación','Tecnología','Servicios','Otro'] as $opt)
                            <div wire:click="$set('industry','{{ $opt }}')"
                                 class="chip {{ $industry === $opt ? 'selected' : '' }}">{{ $opt }}</div>
                        @endforeach
                    </div>
                </div>

                <div class="wizard-nav">
                    <div></div>
                    <button wire:click="nextStep" class="btn btn-primary">Siguiente →</button>
                </div>

            {{-- Step 2: Presupuesto --}}
            @elseif($step === 2)
                <div class="wizard-step-label">Paso 2 de 5</div>
                <h2 class="wizard-title">¿Cuál es el presupuesto?</h2>
                <p class="wizard-subtitle">Mínimo $100.000 COP. El presupuesto incluye pauta + honorarios de agencia.</p>

                <div class="form-group">
                    <label class="form-label">Presupuesto total en COP <span class="required">*</span></label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-weight:600;">$</span>
                        <input wire:model.live="budgetCop" type="number" min="100000" step="10000"
                               class="form-input" style="padding-left:26px;" placeholder="300000" />
                    </div>
                    @error('budgetCop') <span class="form-error">{{ $message }}</span> @enderror
                    @if($budgetCop >= 100000 && $durationDays > 0)
                        <p class="daily-calc">Equivale a <span>${{ number_format(intval($budgetCop/$durationDays),0,',','.') }} COP/día</span></p>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-label">Duración <span class="required">*</span></label>
                    <div class="chip-grid">
                        @foreach([15,30,45,60] as $days)
                            <div wire:click="$set('durationDays',{{ $days }})"
                                 class="chip {{ $durationDays === $days ? 'selected' : '' }}">{{ $days }} días</div>
                        @endforeach
                    </div>
                    @error('durationDays') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="wizard-nav">
                    <button wire:click="prevStep" class="btn btn-secondary">← Anterior</button>
                    <button wire:click="nextStep" class="btn btn-primary">Siguiente →</button>
                </div>

            {{-- Step 3: Audiencia --}}
            @elseif($step === 3)
                <div class="wizard-step-label">Paso 3 de 5</div>
                <h2 class="wizard-title">¿A quién va dirigida?</h2>
                <p class="wizard-subtitle">Define la audiencia objetivo para la segmentación en Meta.</p>

                <div class="form-group">
                    <label class="form-label">Rango de edad <span class="required">*</span></label>
                    <div class="chip-grid">
                        @foreach(['18–24','25–34','35–44','45+'] as $range)
                            <div wire:click="$set('ageRange','{{ $range }}')"
                                 class="chip {{ $ageRange === $range ? 'selected' : '' }}">{{ $range }}</div>
                        @endforeach
                    </div>
                    @error('ageRange') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Ubicación <span class="required">*</span></label>
                    <input wire:model="location" type="text" class="form-input" placeholder="Ej: Cartagena, Colombia" />
                    @error('location') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Intereses <span class="required">*</span> <span style="font-weight:400;color:var(--text-muted)">(selecciona al menos 1)</span></label>
                    <div class="chip-grid">
                        @foreach(['Emprendimiento','Moda','Salud','Tecnología','Hogar','Gastronomía','Deportes','Viajes','Otro'] as $opt)
                            <div wire:click="
                                    @if(in_array($opt, $interests))
                                        $set('interests', array_values(array_filter($interests, fn(\$i) => \$i !== '{{ $opt }}')))
                                    @else
                                        $set('interests', array_merge($interests, ['{{ $opt }}']))
                                    @endif
                                "
                                class="chip {{ in_array($opt, $interests) ? 'selected' : '' }}">{{ $opt }}</div>
                        @endforeach
                    </div>
                    @error('interests') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="wizard-nav">
                    <button wire:click="prevStep" class="btn btn-secondary">← Anterior</button>
                    <button wire:click="nextStep" class="btn btn-primary">Siguiente →</button>
                </div>

            {{-- Step 4: Tipo campaña --}}
            @elseif($step === 4)
                <div class="wizard-step-label">Paso 4 de 5</div>
                <h2 class="wizard-title">¿Cuál es el objetivo?</h2>
                <p class="wizard-subtitle">El objetivo define la estructura y optimización de la campaña en Meta.</p>

                <div class="chip-grid" style="flex-direction:column;">
                    @foreach([
                        ['value'=>'AWARENESS',   'label'=>'Reconocimiento de marca', 'desc'=>'Llegar al mayor número de personas posible'],
                        ['value'=>'TRAFFIC',     'label'=>'Tráfico web',             'desc'=>'Dirigir personas a tu sitio web o landing'],
                        ['value'=>'LEADS',       'label'=>'Generación de leads',     'desc'=>'Capturar contactos de posibles clientes'],
                        ['value'=>'CONVERSIONS', 'label'=>'Conversión / Ventas',     'desc'=>'Ventas directas o acciones de alto valor'],
                        ['value'=>'ENGAGEMENT',  'label'=>'Interacción',             'desc'=>'Aumentar likes, comentarios y compartidos'],
                    ] as $opt)
                        <div wire:click="$set('campaignType','{{ $opt['value'] }}')"
                             class="chip" style="border-radius:10px;text-align:left;padding:12px 16px;{{ $campaignType === $opt['value'] ? 'background:rgba(230,57,70,0.12);border-color:rgba(230,57,70,0.5);color:var(--red-primary);' : '' }}">
                            <div style="font-weight:600;">{{ $opt['label'] }}</div>
                            <div style="font-size:12px;opacity:0.7;margin-top:2px;">{{ $opt['desc'] }}</div>
                        </div>
                    @endforeach
                </div>
                @error('campaignType') <span class="form-error" style="margin-top:8px;display:block;">{{ $message }}</span> @enderror

                <div class="wizard-nav">
                    <button wire:click="prevStep" class="btn btn-secondary">← Anterior</button>
                    <button wire:click="nextStep" class="btn btn-primary">Siguiente →</button>
                </div>

            {{-- Step 5: Destino --}}
            @elseif($step === 5)
                <div class="wizard-step-label">Paso 5 de 5</div>
                <h2 class="wizard-title">¿Hacia dónde va el tráfico?</h2>
                <p class="wizard-subtitle">Define si los anuncios dirigen a WhatsApp, sitio web o ambos.</p>

                <div class="chip-grid" style="flex-direction:column;margin-bottom:24px;">
                    @foreach([
                        ['value'=>'WEB',       'label'=>'Solo Sitio Web',    'desc'=>'El tráfico va a una URL o landing page'],
                        ['value'=>'WHATSAPP',  'label'=>'Solo WhatsApp',     'desc'=>'Botón directo a conversación de WhatsApp'],
                        ['value'=>'BOTH',      'label'=>'Web + WhatsApp',    'desc'=>'Combinación de ambos destinos'],
                    ] as $opt)
                        <div wire:click="$set('destination','{{ $opt['value'] }}')"
                             class="chip" style="border-radius:10px;text-align:left;padding:12px 16px;{{ $destination === $opt['value'] ? 'background:rgba(230,57,70,0.12);border-color:rgba(230,57,70,0.5);color:var(--red-primary);' : '' }}">
                            <div style="font-weight:600;">{{ $opt['label'] }}</div>
                            <div style="font-size:12px;opacity:0.7;margin-top:2px;">{{ $opt['desc'] }}</div>
                        </div>
                    @endforeach
                </div>
                @error('destination') <span class="form-error">{{ $message }}</span> @enderror

                @if(in_array($destination, ['WHATSAPP','BOTH']))
                    <div class="form-group">
                        <label class="form-label">Número WhatsApp</label>
                        <input wire:model="whatsappNumber" type="text" class="form-input" placeholder="+573001234567" />
                    </div>
                @endif

                <div class="wizard-nav">
                    <button wire:click="prevStep" class="btn btn-secondary">← Anterior</button>
                    <button wire:click="generate" wire:loading.attr="disabled" class="btn btn-primary"
                            style="gap:8px;">
                        <span wire:loading wire:target="generate" class="spinner" style="width:16px;height:16px;border-width:2px;"></span>
                        ✦ Generar estrategia con IA
                    </button>
                </div>
            @endif

        </div>{{-- end wizard-card --}}
    </div>{{-- end wizard-shell --}}
@endif
</div>
```

- [ ] **Step 2: Run the full feature test suite again**

```bash
cd apps/laravel
php artisan test tests/Feature/MetaAds/WizardTest.php --verbose
```

Expected: All tests PASS

- [ ] **Step 3: Commit**

```bash
git -C "apps/laravel" add resources/views/livewire/meta-ads/wizard.blade.php
git -C "apps/laravel" commit -m "feat: add wizard blade view with all 5 steps and result display"
```

---

## Task 6: MetaAds\History Livewire + View + Tests

**Files:**
- Create: `app/Livewire/MetaAds/History.php`
- Create: `resources/views/livewire/meta-ads/history.blade.php`
- Create: `tests/Feature/MetaAds/HistoryTest.php`

- [ ] **Step 1: Write failing tests**

Create `apps/laravel/tests/Feature/MetaAds/HistoryTest.php`:

```php
<?php

namespace Tests\Feature\MetaAds;

use App\Livewire\MetaAds\History;
use App\Models\AiMetaQuote;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HistoryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Company $company;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user    = User::factory()->create();
        $this->company = Company::factory()->create();
        $this->user->companies()->attach($this->company->id);
    }

    public function test_history_page_requires_auth(): void
    {
        $this->get(route('meta-ads.history'))->assertRedirect(route('login'));
    }

    public function test_history_shows_saved_quotes(): void
    {
        AiMetaQuote::factory()->create([
            'company_id'   => $this->company->id,
            'client_name'  => 'Cliente Test',
            'campaign_type' => 'CONVERSIONS',
        ]);

        $this->actingAs($this->user)
            ->get(route('meta-ads.history'))
            ->assertOk()
            ->assertSeeLivewire(History::class);

        Livewire::actingAs($this->user)
            ->test(History::class)
            ->assertSee('Cliente Test');
    }

    public function test_delete_removes_quote(): void
    {
        $quote = AiMetaQuote::factory()->create([
            'company_id' => $this->company->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(History::class)
            ->call('delete', $quote->id);

        $this->assertDatabaseMissing('ai_meta_quotes', ['id' => $quote->id]);
    }
}
```

- [ ] **Step 2: Create AiMetaQuote factory**

```bash
cd apps/laravel
php artisan make:factory AiMetaQuoteFactory --model=AiMetaQuote
```

Open the generated `database/factories/AiMetaQuoteFactory.php` and replace with:

```php
<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class AiMetaQuoteFactory extends Factory
{
    public function definition(): array
    {
        $budget   = $this->faker->numberBetween(100000, 1200000);
        $duration = $this->faker->randomElement([15, 30, 45, 60]);

        return [
            'company_id'       => Company::factory(),
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
```

- [ ] **Step 3: Run tests to confirm fail**

```bash
cd apps/laravel
php artisan test tests/Feature/MetaAds/HistoryTest.php --verbose
```

Expected: FAIL — `History class not found`

- [ ] **Step 4: Create History Livewire class**

Create `apps/laravel/app/Livewire/MetaAds/History.php`:

```php
<?php

namespace App\Livewire\MetaAds;

use App\Models\AiMetaQuote;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    #[Locked]
    public int $companyId = 0;

    public string $search = '';

    public function mount(): void
    {
        $this->companyId = auth()->user()->companies()->first()?->id ?? 0;
    }

    public function delete(int $id): void
    {
        AiMetaQuote::where('company_id', $this->companyId)->findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Cotización eliminada');
    }

    public function render()
    {
        $quotes = AiMetaQuote::query()
            ->where('company_id', $this->companyId)
            ->when($this->search, fn($q) => $q->where('client_name', 'like', "%{$this->search}%"))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.meta-ads.history', ['quotes' => $quotes]);
    }
}
```

- [ ] **Step 5: Create History blade view**

Create `apps/laravel/resources/views/livewire/meta-ads/history.blade.php`:

```blade
<div>
    <div class="crud-header">
        <div>
            <h1 class="crud-title">Historial IA Meta Ads</h1>
            <p class="crud-subtitle">Cotizaciones generadas con inteligencia artificial</p>
        </div>
        <a wire:navigate href="{{ route('meta-ads.wizard') }}" class="btn btn-primary">
            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva cotización
        </a>
    </div>

    <div class="crud-toolbar">
        <div class="crud-search-wrapper">
            <svg class="crud-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar cliente..." class="crud-search-input"/>
        </div>
    </div>

    <div class="crud-table-wrapper">
        <table class="crud-table">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Campaña</th>
                    <th>Presupuesto</th>
                    <th>Duración</th>
                    <th>Fecha</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotes as $quote)
                    <tr>
                        <td>
                            <div class="crud-row-name">{{ $quote->client_name }}</div>
                            <div class="crud-row-meta">{{ $quote->industry }}</div>
                        </td>
                        <td><span class="badge badge-pending">{{ $quote->campaign_type }}</span></td>
                        <td style="font-weight:600;font-family:'Space Grotesk',sans-serif;">{{ $quote->budget_formatted }}</td>
                        <td class="muted">{{ $quote->duration_days }} días</td>
                        <td class="muted">{{ $quote->created_at->format('d/m/Y') }}</td>
                        <td style="text-align:right;white-space:nowrap;">
                            <a href="{{ route('meta-ads.pdf', $quote->id) }}" target="_blank" class="action-btn" title="Exportar PDF">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </a>
                            <button wire:click="delete({{ $quote->id }})"
                                    wire:confirm="¿Eliminar esta cotización? Esta acción no se puede deshacer."
                                    class="action-btn danger" title="Eliminar">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state-cell">
                            <div class="empty-state-icon">
                                <svg style="width:28px;height:28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <div class="empty-state-title">Sin cotizaciones generadas</div>
                            <div class="empty-state-desc">Usa el wizard para crear tu primera estrategia con IA</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($quotes->hasPages())
        <div class="crud-pagination">{{ $quotes->links() }}</div>
    @endif
</div>
```

- [ ] **Step 6: Run all History tests**

```bash
cd apps/laravel
php artisan test tests/Feature/MetaAds/HistoryTest.php --verbose
```

Expected: All 3 tests PASS

- [ ] **Step 7: Commit**

```bash
git -C "apps/laravel" add app/Livewire/MetaAds/History.php resources/views/livewire/meta-ads/history.blade.php tests/Feature/MetaAds/HistoryTest.php database/factories/AiMetaQuoteFactory.php
git -C "apps/laravel" commit -m "feat: add MetaAds History component and view with factory"
```

---

## Task 7: PDF Controller + Template + Feature Test

**Files:**
- Create: `app/Http/Controllers/MetaAdsController.php`
- Create: `resources/views/pdf/meta-ads-quote.blade.php`

- [ ] **Step 1: Write failing test**

Create `apps/laravel/tests/Feature/MetaAds/PdfTest.php`:

```php
<?php

namespace Tests\Feature\MetaAds;

use App\Models\AiMetaQuote;
use App\Models\Company;
use App\Models\User;
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
        $user    = User::factory()->create();
        $company = Company::factory()->create();
        $user->companies()->attach($company->id);

        $quote = AiMetaQuote::factory()->create(['company_id' => $company->id]);

        $response = $this->actingAs($user)->get(route('meta-ads.pdf', $quote->id));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
```

- [ ] **Step 2: Run test to confirm fail**

```bash
cd apps/laravel
php artisan test tests/Feature/MetaAds/PdfTest.php --verbose
```

Expected: FAIL — route not found

- [ ] **Step 3: Create MetaAdsController**

Create `apps/laravel/app/Http/Controllers/MetaAdsController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\AiMetaQuote;
use Barryvdh\DomPDF\Facade\Pdf;

class MetaAdsController extends Controller
{
    public function pdf(int $id)
    {
        $companyId = auth()->user()->companies()->first()?->id ?? 0;
        $quote     = AiMetaQuote::where('company_id', $companyId)->findOrFail($id);

        $pdf = Pdf::loadView('pdf.meta-ads-quote', ['quote' => $quote])
            ->setPaper('a4', 'portrait');

        return $pdf->download("cotizacion-meta-ads-{$quote->client_name}-{$quote->id}.pdf");
    }
}
```

- [ ] **Step 4: Create PDF blade template**

Create `apps/laravel/resources/views/pdf/meta-ads-quote.blade.php`:

```blade
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    * { font-family: 'DejaVu Sans', Arial, sans-serif; box-sizing: border-box; margin: 0; padding: 0; }
    body { background: #fff; color: #111; font-size: 13px; line-height: 1.5; }
    .page { padding: 40px 48px; }

    /* Header */
    .pdf-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; padding-bottom: 20px; border-bottom: 3px solid #E63946; }
    .pdf-logo { font-size: 22px; font-weight: 700; color: #E63946; letter-spacing: -0.5px; }
    .pdf-logo span { color: #111; }
    .pdf-meta { text-align: right; font-size: 11px; color: #666; }
    .pdf-meta strong { color: #111; font-size: 13px; }

    /* Badges row */
    .badges { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 24px; }
    .badge { padding: 4px 12px; border-radius: 20px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge-red   { background: #fff0f1; color: #E63946; border: 1px solid #fecdd0; }
    .badge-green { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .badge-blue  { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }

    /* Section titles */
    .section-title { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #E63946; margin-bottom: 10px; }

    /* Cards */
    .card { border: 1px solid #e5e5e5; border-radius: 10px; padding: 16px; margin-bottom: 14px; }
    .card-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }

    /* Resumen */
    .resumen { background: #fff8f8; border: 1px solid #fecdd0; border-radius: 10px; padding: 16px; margin-bottom: 20px; }
    .resumen p { font-size: 13px; color: #333; line-height: 1.6; }

    /* Metrics grid */
    .metric-box { text-align: center; padding: 10px; border: 1px solid #e5e5e5; border-radius: 8px; }
    .metric-value { font-size: 15px; font-weight: 700; color: #E63946; }
    .metric-label { font-size: 9px; color: #888; text-transform: uppercase; margin-top: 2px; }

    /* Ad sets */
    .adset { background: #f9f9f9; border-radius: 8px; padding: 10px 14px; margin-bottom: 8px; }
    .adset-name { font-weight: 700; font-size: 12px; color: #111; }
    .adset-detail { font-size: 11px; color: #666; margin-top: 3px; }

    /* Phases */
    .phase-row { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
    .phase-label { width: 50px; font-size: 11px; font-weight: 700; color: #E63946; }
    .phase-bar-wrap { flex: 1; height: 8px; background: #f0f0f0; border-radius: 4px; overflow: hidden; }
    .phase-bar-fill { height: 100%; background: #E63946; border-radius: 4px; }
    .phase-pct { font-size: 10px; color: #888; white-space: nowrap; }

    /* Pricing table */
    .price-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .price-table td { padding: 9px 12px; border-bottom: 1px solid #f0f0f0; font-size: 13px; }
    .price-table .label { color: #444; }
    .price-table .amount { text-align: right; font-weight: 600; color: #111; }
    .price-table .total-row td { background: #fff0f1; font-weight: 700; color: #E63946; font-size: 15px; border-bottom: none; border-radius: 0 0 8px 8px; }

    /* Pixel */
    .pixel-item { display: flex; align-items: center; gap: 8px; padding: 5px 0; font-size: 12px; color: #444; border-bottom: 1px solid #f5f5f5; }
    .pixel-dot { width: 6px; height: 6px; border-radius: 50%; background: #E63946; flex-shrink: 0; }

    /* Footer */
    .pdf-footer { margin-top: 32px; padding-top: 16px; border-top: 1px solid #e5e5e5; display: flex; justify-content: space-between; font-size: 10px; color: #aaa; }

    /* Signature */
    .signature-area { margin-top: 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
    .sig-line { border-top: 1px solid #ccc; padding-top: 6px; font-size: 11px; color: #888; }

    /* Page break */
    .page-break { page-break-after: always; }
</style>
</head>
<body>

{{-- ════════════════════ PAGE 1 ════════════════════ --}}
<div class="page">
    <div class="pdf-header">
        <div>
            <div class="pdf-logo">KAMO<span> Agency</span></div>
            <div style="font-size:10px;color:#888;margin-top:4px;">Propuesta de Campaña Meta Ads</div>
        </div>
        <div class="pdf-meta">
            <strong>{{ $quote->client_name }}</strong><br>
            {{ $quote->industry }}<br>
            {{ $quote->created_at->format('d \d\e F \d\e Y') }}<br>
            <span style="color:#E63946;font-weight:600;">Ref. #{{ str_pad($quote->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>
    </div>

    <div class="badges">
        <span class="badge badge-red">💰 ${{ number_format($quote->budget_cop,0,',','.') }} COP · {{ $quote->duration_days }} días</span>
        <span class="badge badge-green">🎯 {{ $quote->campaign_type }}</span>
        <span class="badge badge-blue">📍 {{ $quote->destination }}</span>
        @if($quote->location)
            <span class="badge" style="background:#f8f8f8;color:#555;border:1px solid #e0e0e0;">📍 {{ $quote->location }}</span>
        @endif
    </div>

    <div class="section-title">Resumen ejecutivo</div>
    <div class="resumen">
        <p>{{ $quote->ai_result['resumen_ejecutivo'] }}</p>
    </div>

    <div class="section-title">Métricas estimadas</div>
    @php $m = $quote->ai_result['metricas_estimadas']; @endphp
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:10px;margin-bottom:20px;">
        <div class="metric-box"><div class="metric-value">{{ $m['alcance_diario'] }}</div><div class="metric-label">Alcance/día</div></div>
        <div class="metric-box"><div class="metric-value">{{ $m['cpm_estimado_cop'] }}</div><div class="metric-label">CPM</div></div>
        <div class="metric-box"><div class="metric-value">{{ $m['ctr_objetivo'] }}</div><div class="metric-label">CTR objetivo</div></div>
        <div class="metric-box"><div class="metric-value">{{ $m['cpc_estimado_cop'] }}</div><div class="metric-label">CPC</div></div>
        <div class="metric-box"><div class="metric-value">{{ $m['frecuencia_sugerida'] }}</div><div class="metric-label">Frecuencia</div></div>
    </div>

    <div class="pdf-footer">
        <span>Kamo Agency · kamo.agency</span>
        <span>Generado con IA · {{ now()->format('d/m/Y H:i') }}</span>
    </div>
</div>

<div class="page-break"></div>

{{-- ════════════════════ PAGE 2 ════════════════════ --}}
<div class="page">
    <div class="pdf-header">
        <div class="pdf-logo">KAMO<span> Agency</span></div>
        <div class="pdf-meta"><strong>{{ $quote->client_name }}</strong> · Pág. 2</div>
    </div>

    <div class="card-grid">
        {{-- Ad Sets --}}
        <div>
            <div class="section-title">Estructura de campaña</div>
            <div style="font-size:11px;color:#666;margin-bottom:8px;">
                Objetivo: <strong style="color:#E63946;">{{ $quote->ai_result['estructura_campana']['objetivo'] }}</strong> ·
                {{ $quote->ai_result['estructura_campana']['nombre_campana'] }}
            </div>
            @foreach($quote->ai_result['estructura_campana']['ad_sets'] as $adset)
                <div class="adset">
                    <div class="adset-name">{{ $adset['nombre'] }}</div>
                    <div class="adset-detail">{{ $adset['audiencia_descripcion'] }}</div>
                    <div class="adset-detail">Placements: {{ implode(', ', $adset['placements']) }}</div>
                    <div class="adset-detail" style="color:#E63946;font-weight:600;margin-top:4px;">
                        ${{ number_format($adset['presupuesto_diario_cop'],0,',','.') }}/día · {{ $adset['duracion_dias'] }} días · {{ $adset['optimizacion'] }}
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Creativos --}}
        <div>
            <div class="section-title">Creativos</div>
            <div style="margin-bottom:8px;">
                @foreach($quote->ai_result['creativos']['formatos'] as $fmt)
                    <span style="display:inline-block;padding:3px 8px;background:#f0f0f0;border-radius:4px;font-size:10px;margin:2px;">{{ $fmt }}</span>
                @endforeach
            </div>
            <div style="font-size:11px;color:#666;margin-bottom:8px;">CTA: <strong style="color:#E63946;">{{ $quote->ai_result['creativos']['call_to_action'] }}</strong></div>
            @foreach($quote->ai_result['creativos']['copies_sugeridos'] as $copy)
                <div style="background:#f9f9f9;border-left:3px solid #E63946;padding:6px 10px;margin-bottom:6px;font-size:11px;color:#333;border-radius:0 6px 6px 0;">
                    "{{ $copy }}"
                </div>
            @endforeach
            <div style="font-size:11px;color:#888;margin-top:8px;">{{ $quote->ai_result['creativos']['recomendaciones'] }}</div>
        </div>
    </div>

    {{-- Fases --}}
    <div class="card" style="margin-top:4px;">
        <div class="section-title">Fases de la campaña (TOFU / MOFU / BOFU)</div>
        @foreach($quote->ai_result['fases'] as $fase)
            <div class="phase-row">
                <div class="phase-label">{{ $fase['fase'] }}</div>
                <div class="phase-bar-wrap">
                    <div class="phase-bar-fill" style="width:{{ $fase['presupuesto_pct'] }}%;"></div>
                </div>
                <div class="phase-pct">{{ $fase['presupuesto_pct'] }}% · {{ $fase['dias'] }}d · {{ $fase['objetivo'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="pdf-footer">
        <span>Kamo Agency · kamo.agency</span>
        <span>{{ $quote->client_name }} · Pág. 2/3</span>
    </div>
</div>

<div class="page-break"></div>

{{-- ════════════════════ PAGE 3 ════════════════════ --}}
<div class="page">
    <div class="pdf-header">
        <div class="pdf-logo">KAMO<span> Agency</span></div>
        <div class="pdf-meta"><strong>{{ $quote->client_name }}</strong> · Pág. 3</div>
    </div>

    {{-- Pixel --}}
    @if(!empty($quote->ai_result['recomendaciones_pixel']))
        <div class="section-title">Recomendaciones Meta Pixel</div>
        <div class="card" style="margin-bottom:20px;">
            @foreach($quote->ai_result['recomendaciones_pixel'] as $rec)
                <div class="pixel-item">
                    <div class="pixel-dot"></div>
                    {{ $rec }}
                </div>
            @endforeach
        </div>
    @endif

    {{-- Pricing --}}
    <div class="section-title">Desglose financiero</div>
    @php $p = $quote->ai_result['desglose_precios']; @endphp
    <table class="price-table">
        <tr>
            <td class="label">Presupuesto en pauta (Meta Ads)</td>
            <td class="amount">${{ number_format($p['presupuesto_pauta_cop'],0,',','.') }} COP</td>
        </tr>
        <tr>
            <td class="label">Honorarios gestión de campaña</td>
            <td class="amount">${{ number_format($p['honorarios_gestion_cop'],0,',','.') }} COP</td>
        </tr>
        <tr>
            <td class="label">Honorarios producción creativos</td>
            <td class="amount">${{ number_format($p['honorarios_creativos_cop'],0,',','.') }} COP</td>
        </tr>
        <tr>
            <td class="label" style="font-weight:600;">Total honorarios agencia</td>
            <td class="amount" style="font-weight:600;">${{ number_format($p['total_agencia_cop'],0,',','.') }} COP</td>
        </tr>
        <tr class="total-row">
            <td class="label">TOTAL INVERSIÓN</td>
            <td class="amount">${{ number_format($p['total_inversion_cop'],0,',','.') }} COP</td>
        </tr>
    </table>

    {{-- Signature area --}}
    <div class="signature-area">
        <div>
            <div class="sig-line">Firma cliente — {{ $quote->client_name }}</div>
        </div>
        <div>
            <div class="sig-line">Firma Kamo Agency — Responsable</div>
        </div>
    </div>

    <div class="pdf-footer">
        <span>Kamo Agency · kamo.agency · Propuesta válida por 30 días</span>
        <span>Ref. #{{ str_pad($quote->id, 4, '0', STR_PAD_LEFT) }} · {{ $quote->created_at->format('d/m/Y') }}</span>
    </div>
</div>

</body>
</html>
```

- [ ] **Step 5: Run PDF tests**

```bash
cd apps/laravel
php artisan test tests/Feature/MetaAds/PdfTest.php --verbose
```

Expected: Both tests PASS

- [ ] **Step 6: Commit**

```bash
git -C "apps/laravel" add app/Http/Controllers/MetaAdsController.php resources/views/pdf/meta-ads-quote.blade.php tests/Feature/MetaAds/PdfTest.php
git -C "apps/laravel" commit -m "feat: add PDF export controller and template for Meta Ads quotes"
```

---

## Task 8: Routes + Sidebar Navigation

**Files:**
- Modify: `routes/web.php`
- Modify: `resources/views/components/layouts/app.blade.php`

- [ ] **Step 1: Add routes to web.php**

Open `apps/laravel/routes/web.php`. After the existing services routes block (line ~79), add:

```php
    // ── IA Meta Ads ──────────────────────────────────────────────────────────────
    Route::get('/servicios/meta-ads-ia',           \App\Livewire\MetaAds\Wizard::class)->name('meta-ads.wizard');
    Route::get('/servicios/meta-ads-ia/historial', \App\Livewire\MetaAds\History::class)->name('meta-ads.history');
    Route::get('/servicios/meta-ads-ia/{id}/pdf',  [\App\Http\Controllers\MetaAdsController::class, 'pdf'])->name('meta-ads.pdf');
```

- [ ] **Step 2: Add sidebar sub-link under Servicios**

Open `apps/laravel/resources/views/components/layouts/app.blade.php`.

Find this block (around line 1192):
```blade
                <a wire:navigate href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
```

Replace it with:
```blade
                <a wire:navigate href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.index') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Servicios
                </a>
                <a wire:navigate href="{{ route('meta-ads.wizard') }}"
                   class="nav-sublink {{ request()->routeIs('meta-ads.*') ? 'active' : '' }}">
                    ✦ IA Meta Ads
                </a>
```

- [ ] **Step 3: Verify routes are registered**

```bash
cd apps/laravel
php artisan route:list | grep meta-ads
```

Expected output:
```
GET  servicios/meta-ads-ia              meta-ads.wizard
GET  servicios/meta-ads-ia/historial    meta-ads.history
GET  servicios/meta-ads-ia/{id}/pdf     meta-ads.pdf
```

- [ ] **Step 4: Run the full test suite**

```bash
cd apps/laravel
php artisan test --verbose
```

Expected: All tests PASS (no regressions)

- [ ] **Step 5: Commit**

```bash
git -C "apps/laravel" add routes/web.php resources/views/components/layouts/app.blade.php
git -C "apps/laravel" commit -m "feat: wire up Meta Ads AI routes and sidebar navigation"
```

---

## Self-Review Checklist

- [x] **Spec coverage:** All spec sections covered — wizard 5 steps, Claude API with caching, DB persistence, history table, 3-page PDF, sidebar sub-link
- [x] **No placeholders:** All steps have complete code. No TBD or TODO.
- [x] **Type consistency:** `AiMetaQuote` used consistently across model, factory, controller, and both Livewire classes. `ClaudeMetaAdsService` injected via container in Wizard and bound in AppServiceProvider.
- [x] **Factory needed for tests:** `AiMetaQuoteFactory` created in Task 6, used in both History and PDF tests.
- [x] **`Company::factory()`:** Used in tests — confirm `CompanyFactory` exists before running. If not: `php artisan make:factory CompanyFactory --model=Company` and define basic fields.
