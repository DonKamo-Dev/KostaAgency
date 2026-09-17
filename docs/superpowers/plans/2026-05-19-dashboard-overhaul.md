# Dashboard Overhaul Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Rebuild the Kamo dashboard into a high-signal operations center with real financial intelligence, alerts, top-client visibility, and conversion tracking.

**Architecture:** Single controller (`DashboardController`) passes enriched data to one Blade view (`resources/views/dashboard/index.blade.php`). No Livewire — the route `GET /dashboard` maps to the controller directly. All new metrics are computed in PHP, chart interactivity handled in vanilla JS with Chart.js 4.

**Tech Stack:** Laravel 11, Blade, Chart.js 4.4, Tailwind (via existing CSS vars), vanilla JS

---

## File Map

| File | Action | Responsibility |
|------|--------|---------------|
| `app/Http/Controllers/DashboardController.php` | Modify | Add 9 new computed metrics |
| `resources/views/dashboard/index.blade.php` | Rewrite | Full UI overhaul — all 8 sections |

---

## Task 1: Enrich DashboardController with new metrics

**Files:**
- Modify: `app/Http/Controllers/DashboardController.php`

New variables to pass to the view:

| Variable | Formula |
|----------|---------|
| `$outstandingAmount` | `totalInvoiced − totalCollected` |
| `$pendingQuotesValue` | `SUM(total) WHERE type=quote AND status=pending` |
| `$overdueCount` | `COUNT WHERE type=invoice AND status NOT IN (paid,cancelled) AND due_date < today` |
| `$overdueAmount` | `SUM(total−paid) WHERE overdue` |
| `$conversionRate` | `converted quotes / (total quotes − cancelled) * 100` |
| `$topClients` | Top 5 clients by `SUM(total)` on non-cancelled invoices |
| `$thisMonthIncome` | `SUM(total) WHERE type=invoice AND MONTH(date)=current` |
| `$lastMonthIncome` | same, previous month |
| `$incomeGrowthPct` | `(thisMonth − lastMonth) / lastMonth * 100` (null-safe) |
| `$thisMonthExpenses` | `SUM(amount) WHERE MONTH(date)=current` |
| `$monthGoal` | Fixed at 5000 for now (hardcoded, easy to make configurable later) |
| `$profitData` | Array of `incomeData[i] − expenseData[i]` for chart 3rd line |

- [ ] **Step 1: Replace the controller body**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Expense;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $cid   = auth()->user()->companies()->first()?->id ?? 0;
        $since = now()->subMonths(5)->startOfMonth();
        $now   = now();

        // ── Core KPIs ──────────────────────────────────────────────
        $totalInvoiced = Document::where('company_id', $cid)
            ->where('type', 'invoice')->where('status', '!=', 'cancelled')
            ->sum('total') ?? 0;

        $totalCollected = Document::where('company_id', $cid)
            ->where('type', 'invoice')->sum('paid') ?? 0;

        $totalExpenses = Expense::where('company_id', $cid)->sum('amount') ?? 0;

        $netProfit          = $totalCollected - $totalExpenses;
        $outstandingAmount  = max(0, $totalInvoiced - $totalCollected);

        // ── Pending quotes ─────────────────────────────────────────
        $pendingQuotes = Document::where('company_id', $cid)
            ->where('type', 'quote')->where('status', 'pending')->count();

        $pendingQuotesValue = Document::where('company_id', $cid)
            ->where('type', 'quote')->where('status', 'pending')
            ->sum('total') ?? 0;

        // ── Overdue invoices ───────────────────────────────────────
        $overdueQuery = Document::where('company_id', $cid)
            ->where('type', 'invoice')
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->whereNotNull('due_date')
            ->where('due_date', '<', $now->toDateString());

        $overdueCount  = (clone $overdueQuery)->count();
        $overdueAmount = (clone $overdueQuery)->selectRaw('SUM(total - paid) as amt')->value('amt') ?? 0;

        // ── Conversion rate ────────────────────────────────────────
        $totalQuotes     = Document::where('company_id', $cid)
            ->where('type', 'quote')->whereNotIn('status', ['cancelled'])->count();
        $convertedQuotes = Document::where('company_id', $cid)
            ->where('type', 'quote')->where('status', 'converted')->count();
        $conversionRate  = $totalQuotes > 0
            ? round(($convertedQuotes / $totalQuotes) * 100, 1)
            : 0;

        // ── Month-over-month income ────────────────────────────────
        $thisMonthIncome = Document::where('company_id', $cid)
            ->where('type', 'invoice')->where('status', '!=', 'cancelled')
            ->whereYear('date', $now->year)->whereMonth('date', $now->month)
            ->sum('total') ?? 0;

        $lastMonthIncome = Document::where('company_id', $cid)
            ->where('type', 'invoice')->where('status', '!=', 'cancelled')
            ->whereYear('date', $now->copy()->subMonth()->year)
            ->whereMonth('date', $now->copy()->subMonth()->month)
            ->sum('total') ?? 0;

        $incomeGrowthPct = $lastMonthIncome > 0
            ? round((($thisMonthIncome - $lastMonthIncome) / $lastMonthIncome) * 100, 1)
            : null;

        $thisMonthExpenses = Expense::where('company_id', $cid)
            ->whereYear('date', $now->year)->whereMonth('date', $now->month)
            ->sum('amount') ?? 0;

        // ── Top clients ────────────────────────────────────────────
        $topClients = Document::where('documents.company_id', $cid)
            ->where('type', 'invoice')->where('status', '!=', 'cancelled')
            ->join('clients', 'clients.id', '=', 'documents.client_id')
            ->selectRaw('clients.name, SUM(documents.total) as revenue, COUNT(*) as invoice_count')
            ->groupBy('clients.id', 'clients.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        // ── Chart data (6 months) ──────────────────────────────────
        $incomeByMonth = Document::where('company_id', $cid)
            ->where('type', 'invoice')->where('status', '!=', 'cancelled')
            ->where('date', '>=', $since)
            ->selectRaw('YEAR(date) as year, MONTH(date) as month, SUM(total) as total')
            ->groupBy('year', 'month')->get()
            ->keyBy(fn($r) => "{$r->year}-{$r->month}");

        $expensesByMonth = Expense::where('company_id', $cid)
            ->where('date', '>=', $since)
            ->selectRaw('YEAR(date) as year, MONTH(date) as month, SUM(amount) as total')
            ->groupBy('year', 'month')->get()
            ->keyBy(fn($r) => "{$r->year}-{$r->month}");

        $months = $incomeData = $expenseData = $profitData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date          = now()->subMonths($i);
            $months[]      = $date->locale('es')->isoFormat('MMM');
            $key           = "{$date->year}-{$date->month}";
            $inc           = (float) ($incomeByMonth[$key]->total   ?? 0);
            $exp           = (float) ($expensesByMonth[$key]->total ?? 0);
            $incomeData[]  = $inc;
            $expenseData[] = $exp;
            $profitData[]  = round($inc - $exp, 2);
        }

        // ── Recent activity ────────────────────────────────────────
        $totalClients  = Client::where('company_id', $cid)->count();
        $totalServices = Service::where('company_id', $cid)->count();

        $recentDocuments = Document::with('client')
            ->where('company_id', $cid)
            ->orderBy('created_at', 'desc')
            ->limit(8)->get();

        $monthGoal = 5000; // configurable target

        return view('dashboard.index', compact(
            'totalInvoiced', 'totalCollected', 'totalExpenses', 'netProfit',
            'outstandingAmount', 'pendingQuotes', 'pendingQuotesValue',
            'overdueCount', 'overdueAmount',
            'conversionRate', 'convertedQuotes', 'totalQuotes',
            'thisMonthIncome', 'lastMonthIncome', 'incomeGrowthPct',
            'thisMonthExpenses', 'topClients', 'monthGoal',
            'totalClients', 'totalServices', 'recentDocuments',
            'months', 'incomeData', 'expenseData', 'profitData'
        ));
    }
}
```

- [ ] **Step 2: Verify the page loads (no 500 errors)**

Visit `http://localhost:8000/dashboard` — should render without errors. The view still uses old variables so the display may be partial/broken, which is expected.

---

## Task 2: Header + Alerts Banner

**Files:**
- Modify: `resources/views/dashboard/index.blade.php` — replace `<div class="dash-header">` block and add alerts after it.

- [ ] **Step 1: Replace the header block**

Find and replace the entire `<div class="dash-header">…</div>` with:

```html
<div class="dash-header">
    <div>
        <h1 class="dash-greeting">
            {{ now()->hour < 12 ? 'Buenos días' : (now()->hour < 19 ? 'Buenas tardes' : 'Buenas noches') }},
            <span>{{ explode(' ', auth()->user()->name)[0] ?? 'Admin' }}</span>
        </h1>
        <p class="dash-subtitle">
            @if($overdueCount > 0 || $pendingQuotes > 0)
                @if($overdueCount > 0)
                    <span style="color:var(--negative);">{{ $overdueCount }} {{ $overdueCount === 1 ? 'factura vencida' : 'facturas vencidas' }}</span>
                    @if($pendingQuotes > 0) · @endif
                @endif
                @if($pendingQuotes > 0)
                    <span style="color:var(--warning);">{{ $pendingQuotes }} {{ $pendingQuotes === 1 ? 'cotización pendiente' : 'cotizaciones pendientes' }}</span>
                @endif
            @else
                Todo al día · {{ now()->locale('es')->isoFormat('dddd D [de] MMMM') }}
            @endif
        </p>
    </div>
    <a href="{{ route('quotes.index') }}" class="btn btn-primary" style="height:fit-content;">
        <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nueva Cotización
    </a>
</div>
```

- [ ] **Step 2: Add alerts banner immediately after the header (before hero-kpi)**

```html
@if($overdueCount > 0 || $pendingQuotes > 0)
<div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
    @if($overdueCount > 0)
    <a href="{{ route('invoices.index') }}" style="flex:1;min-width:240px;display:flex;align-items:center;gap:14px;padding:14px 18px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);border-radius:14px;text-decoration:none;transition:border-color .2s ease;" onmouseover="this.style.borderColor='rgba(239,68,68,0.5)'" onmouseout="this.style.borderColor='rgba(239,68,68,0.25)'">
        <div style="width:38px;height:38px;border-radius:10px;background:rgba(239,68,68,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:18px;height:18px;color:var(--negative);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:13px;font-weight:700;color:var(--negative);">{{ $overdueCount }} {{ $overdueCount === 1 ? 'Factura Vencida' : 'Facturas Vencidas' }}</div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">${{ number_format($overdueAmount, 0) }} pendiente de cobro · Ver facturas →</div>
        </div>
    </a>
    @endif
    @if($pendingQuotes > 0)
    <a href="{{ route('quotes.index') }}" style="flex:1;min-width:240px;display:flex;align-items:center;gap:14px;padding:14px 18px;background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.25);border-radius:14px;text-decoration:none;transition:border-color .2s ease;" onmouseover="this.style.borderColor='rgba(245,158,11,0.5)'" onmouseout="this.style.borderColor='rgba(245,158,11,0.25)'">
        <div style="width:38px;height:38px;border-radius:10px;background:rgba(245,158,11,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:18px;height:18px;color:var(--warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <div style="font-size:13px;font-weight:700;color:var(--warning);">{{ $pendingQuotes }} {{ $pendingQuotes === 1 ? 'Cotización Pendiente' : 'Cotizaciones Pendientes' }}</div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">${{ number_format($pendingQuotesValue, 0) }} en espera · Ver cotizaciones →</div>
        </div>
    </a>
    @endif
</div>
@endif
```

---

## Task 3: Hero KPI — 4 columns + real trend + Por cobrar

**Files:**
- Modify: `resources/views/dashboard/index.blade.php` — replace `<div class="hero-kpi">` block.

- [ ] **Step 1: Add 4-column grid CSS** (inside the `<style>` tag at top of file)

```css
.hero-kpi-grid { grid-template-columns: 1fr 1fr 1fr 1fr; }
@media (max-width: 900px) { .hero-kpi-grid { grid-template-columns: 1fr 1fr; gap: 20px; } }
@media (max-width: 480px) { .hero-kpi-grid { grid-template-columns: 1fr; } }
.hero-kpi-trend-neg { display:inline-flex;align-items:center;gap:4px;font-size:13px;font-weight:600;color:var(--negative);background:rgba(239,68,68,0.1);padding:4px 10px;border-radius:20px; }
.hero-kpi-trend-neutral { display:inline-flex;align-items:center;gap:4px;font-size:13px;font-weight:600;color:var(--text-muted);background:rgba(245,245,245,0.06);padding:4px 10px;border-radius:20px; }
```

- [ ] **Step 2: Replace the hero-kpi block**

```html
<div class="hero-kpi">
    <div class="hero-kpi-grid">
        {{-- Col 1: Facturado total (este mes) --}}
        <div>
            <div class="hero-kpi-label">Facturado · {{ now()->locale('es')->isoFormat('MMMM') }}</div>
            <div class="hero-kpi-value hero-kpi-value-large">${{ number_format($thisMonthIncome, 0) }}</div>
            @if($incomeGrowthPct !== null)
                @if($incomeGrowthPct >= 0)
                    <span class="hero-kpi-trend">
                        <svg style="width:12px;height:12px;" fill="currentColor" viewBox="0 0 24 24"><path d="M7 14l5-5 5 5z"/></svg>
                        +{{ $incomeGrowthPct }}% vs. mes anterior
                    </span>
                @else
                    <span class="hero-kpi-trend-neg">
                        <svg style="width:12px;height:12px;" fill="currentColor" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>
                        {{ $incomeGrowthPct }}% vs. mes anterior
                    </span>
                @endif
            @else
                <span class="hero-kpi-trend-neutral">Primer mes</span>
            @endif
        </div>

        {{-- Col 2: Cobrado --}}
        <div class="hero-kpi-divider">
            <div class="hero-kpi-label">Cobrado · Todo el tiempo</div>
            <div class="hero-kpi-value">${{ number_format($totalCollected, 0) }}</div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">
                {{ $totalInvoiced > 0 ? round(($totalCollected / $totalInvoiced) * 100, 1) : 0 }}% del total facturado
            </div>
        </div>

        {{-- Col 3: Por cobrar --}}
        <div class="hero-kpi-divider">
            <div class="hero-kpi-label">Por Cobrar</div>
            <div class="hero-kpi-value" style="color: {{ $outstandingAmount > 0 ? 'var(--warning)' : 'var(--positive)' }};">
                ${{ number_format($outstandingAmount, 0) }}
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">
                {{ $outstandingAmount > 0 ? 'Pendiente de ingreso' : 'Todo cobrado ✓' }}
            </div>
        </div>

        {{-- Col 4: Beneficio neto --}}
        <div class="hero-kpi-divider">
            <div class="hero-kpi-label">Beneficio Neto</div>
            <div class="hero-kpi-value" style="color: {{ $netProfit >= 0 ? 'var(--positive)' : 'var(--negative)' }};">
                ${{ number_format($netProfit, 0) }}
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">
                Cobrado − Gastos totales
            </div>
        </div>
    </div>
</div>
```

---

## Task 4: Stats Grid — clickable cards + period label + pending value

**Files:**
- Modify: `resources/views/dashboard/index.blade.php` — replace `<div class="stats-grid">` block.

- [ ] **Step 1: Add stat-card link CSS** (inside existing `<style>` tag)

```css
.stat-card a.stat-card-inner { display:block; text-decoration:none; color:inherit; }
.stat-card-sub { font-size:11px; color:var(--text-subtle); margin-top:3px; }
```

- [ ] **Step 2: Replace the stats-grid block**

```html
<div class="stats-grid">
    <a href="{{ route('expenses.index') }}" class="stat-card" style="text-decoration:none;color:inherit;">
        <div class="stat-card-icon red">
            <svg style="width:22px;height:22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
            </svg>
        </div>
        <div class="stat-card-label">Gastos · {{ now()->locale('es')->isoFormat('MMMM') }}</div>
        <div class="stat-card-value">${{ number_format($thisMonthExpenses, 0) }}</div>
        <div class="stat-card-sub">Total histórico: ${{ number_format($totalExpenses, 0) }}</div>
    </a>

    <a href="{{ route('quotes.index') }}" class="stat-card" style="text-decoration:none;color:inherit;">
        <div class="stat-card-icon amber">
            <svg style="width:22px;height:22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-card-label">Cotizaciones Pendientes</div>
        <div class="stat-card-value">{{ $pendingQuotes }}</div>
        <div class="stat-card-sub">${{ number_format($pendingQuotesValue, 0) }} en espera</div>
    </a>

    <a href="{{ route('clients.index') }}" class="stat-card" style="text-decoration:none;color:inherit;">
        <div class="stat-card-icon blue">
            <svg style="width:22px;height:22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div class="stat-card-label">Total Clientes</div>
        <div class="stat-card-value">{{ $totalClients }}</div>
        <div class="stat-card-sub">Ver directorio →</div>
    </a>

    <a href="{{ route('invoices.index') }}" class="stat-card" style="text-decoration:none;color:inherit;" style="{{ $overdueCount > 0 ? 'border-color:rgba(239,68,68,0.35);' : '' }}">
        <div class="stat-card-icon {{ $overdueCount > 0 ? 'red' : 'green' }}">
            <svg style="width:22px;height:22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-4-7 4V5a2 2 0 012-2h10a2 2 0 012 2v16z"/>
            </svg>
        </div>
        <div class="stat-card-label">Facturas Vencidas</div>
        <div class="stat-card-value" style="{{ $overdueCount > 0 ? 'color:var(--negative);' : '' }}">{{ $overdueCount }}</div>
        <div class="stat-card-sub">
            {{ $overdueCount > 0 ? '$'.number_format($overdueAmount, 0).' por cobrar' : 'Sin vencimientos ✓' }}
        </div>
    </a>
</div>
```

---

## Task 5: Quick Actions — 5 tiles + correct icons

**Files:**
- Modify: `resources/views/dashboard/index.blade.php` — replace `<div class="quick-actions">` block.

- [ ] **Step 1: Update quick-actions CSS for 5 cols**

```css
.quick-actions { grid-template-columns: repeat(5, 1fr); }
@media (max-width: 900px) { .quick-actions { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 480px) { .quick-actions { grid-template-columns: repeat(2, 1fr); } }
```

- [ ] **Step 2: Replace the quick-actions block**

```html
<div class="quick-actions">
    <a href="{{ route('quotes.index') }}" class="quick-action">
        <div class="quick-action-icon">
            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div class="quick-action-text">Nueva<br>Cotización</div>
    </a>
    <a href="{{ route('invoices.index') }}" class="quick-action">
        <div class="quick-action-icon">
            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <div class="quick-action-text">Nueva<br>Factura</div>
    </a>
    <a href="{{ route('clients.index') }}" class="quick-action">
        <div class="quick-action-icon">
            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <div class="quick-action-text">Nuevo<br>Cliente</div>
    </a>
    <a href="{{ route('expenses.index') }}" class="quick-action">
        <div class="quick-action-icon">
            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
        </div>
        <div class="quick-action-text">Registrar<br>Gasto</div>
    </a>
    <a href="{{ route('landing') }}" target="_blank" rel="noopener" class="quick-action">
        <div class="quick-action-icon">
            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </div>
        <div class="quick-action-text">Ver<br>Sitio Web</div>
    </a>
</div>
```

---

## Task 6: Activity Section — full status, clickable rows, type filter

**Files:**
- Modify: `resources/views/dashboard/index.blade.php` — replace the right card inside `<div class="main-grid">`.

- [ ] **Step 1: Add CSS for activity type filter**

```css
.activity-filter { display:flex; gap:8px; margin-bottom:12px; }
.activity-filter-btn { padding:5px 14px; border-radius:20px; font-size:12px; font-weight:600; border:1px solid var(--border-subtle); background:transparent; color:var(--text-muted); cursor:pointer; transition:all .2s ease; }
.activity-filter-btn.active, .activity-filter-btn:hover { border-color:var(--border-red); background:var(--red-soft); color:var(--red-primary); }
.activity-row { cursor:pointer; transition:background .15s ease; border-radius:8px; margin:0 -8px; padding:14px 8px; border-bottom:1px solid var(--border-subtle); }
.activity-row:hover { background:var(--red-soft); border-bottom-color:transparent; }
.activity-row:last-child { border-bottom:none; }
```

- [ ] **Step 2: Replace the right card (Actividad Reciente) inside main-grid**

```html
<div class="card">
    <div class="card-header">
        <div class="card-title">Actividad Reciente</div>
    </div>
    <div class="activity-filter" id="activityFilter">
        <button class="activity-filter-btn active" data-filter="all">Todos</button>
        <button class="activity-filter-btn" data-filter="quote">Cotizaciones</button>
        <button class="activity-filter-btn" data-filter="invoice">Facturas</button>
    </div>
    <div id="activityList">
        @forelse($recentDocuments as $doc)
            <a href="{{ $doc->type === 'quote' ? route('quotes.index') : route('invoices.index') }}"
               class="activity-row"
               data-type="{{ $doc->type }}"
               style="display:flex;align-items:center;gap:14px;text-decoration:none;color:inherit;">
                <div class="activity-icon" style="{{ $doc->type === 'invoice' ? 'background:rgba(59,130,246,0.1);color:#60A5FA;' : '' }}">
                    @if($doc->type === 'quote')
                        <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    @else
                        <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    @endif
                </div>
                <div class="activity-info">
                    <div class="activity-title">{{ $doc->doc_number }}</div>
                    <div class="activity-meta">{{ $doc->client->name ?? 'Sin cliente' }} · {{ $doc->created_at->diffForHumans() }}</div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <div class="activity-amount">${{ number_format($doc->total, 0) }}</div>
                    <span class="status-badge status-{{ $doc->status }}" style="margin-top:4px;display:inline-block;">
                        @php
                            $labels = ['pending'=>'Pendiente','paid'=>'Pagado','converted'=>'Convertido','cancelled'=>'Cancelado'];
                        @endphp
                        {{ $labels[$doc->status] ?? $doc->status }}
                    </span>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <svg style="width:48px;height:48px;margin:0 auto 12px;color:var(--text-subtle);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                <div style="font-size:14px;">Sin actividad reciente</div>
                <div style="font-size:12px;margin-top:4px;">Crea tu primera cotización</div>
            </div>
        @endforelse
    </div>
    @if(count($recentDocuments) > 0)
        <a href="{{ route('invoices.index') }}" class="view-all">
            Ver todos los documentos
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </a>
    @endif
</div>
```

- [ ] **Step 3: Add activity filter JS** (inside the `<script>` block at the bottom of the file, after the chart JS)

```js
// Activity type filter
document.querySelectorAll('#activityFilter .activity-filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('#activityFilter .activity-filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const filter = btn.dataset.filter;
        document.querySelectorAll('#activityList .activity-row').forEach(row => {
            row.style.display = (filter === 'all' || row.dataset.type === filter) ? 'flex' : 'none';
        });
    });
});
```

---

## Task 7: Chart — 3rd profit line + period selector + contrast fix

**Files:**
- Modify: `resources/views/dashboard/index.blade.php` — replace the left card inside `<div class="main-grid">` and update the chart JS.

- [ ] **Step 1: Add period selector CSS** (inside `<style>` tag)

```css
.chart-period-btn { padding:5px 14px; border-radius:20px; font-size:12px; font-weight:600; border:1px solid var(--border-subtle); background:transparent; color:var(--text-muted); cursor:pointer; transition:all .2s ease; }
.chart-period-btn.active { border-color:var(--border-red); background:var(--red-soft); color:var(--red-primary); }
```

- [ ] **Step 2: Replace the left card (Resumen Financiero) inside main-grid**

```html
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Resumen Financiero</div>
            <div style="font-size:13px;color:var(--text-muted);margin-top:4px;" id="chartPeriodLabel">Últimos 6 meses</div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <div style="display:flex;gap:12px;font-size:12px;margin-right:8px;">
                <div style="display:flex;align-items:center;gap:6px;">
                    <span style="width:10px;height:10px;border-radius:50%;background:var(--red-primary);display:inline-block;"></span>
                    <span style="color:var(--text-muted);">Ingresos</span>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <span style="width:10px;height:10px;border-radius:50%;background:rgba(245,245,245,0.55);display:inline-block;"></span>
                    <span style="color:var(--text-muted);">Gastos</span>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <span style="width:10px;height:10px;border-radius:50%;background:#10b981;display:inline-block;"></span>
                    <span style="color:var(--text-muted);">Beneficio</span>
                </div>
            </div>
            <div style="display:flex;gap:6px;">
                <button class="chart-period-btn active" data-months="6">6M</button>
                <button class="chart-period-btn" data-months="3">3M</button>
            </div>
        </div>
    </div>
    <div style="height:320px;"><canvas id="dashboardChart"></canvas></div>
</div>
```

- [ ] **Step 3: Replace the full chart JS block** (the entire `<script>` at the bottom, before `</x-layouts.app>`)

```html
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function() {
    const allMonths   = @json($months);
    const allIncome   = @json($incomeData);
    const allExpenses = @json($expenseData);
    const allProfit   = @json($profitData);

    const ctx = document.getElementById('dashboardChart');
    if (!ctx) return;

    const incGradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 320);
    incGradient.addColorStop(0, 'rgba(230,57,70,0.35)');
    incGradient.addColorStop(1, 'rgba(230,57,70,0)');

    const profGradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 320);
    profGradient.addColorStop(0, 'rgba(16,185,129,0.2)');
    profGradient.addColorStop(1, 'rgba(16,185,129,0)');

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: allMonths,
            datasets: [
                {
                    label: 'Ingresos', data: allIncome,
                    borderColor: '#E63946', backgroundColor: incGradient,
                    tension: 0.4, fill: true, borderWidth: 2.5,
                    pointBackgroundColor: '#E63946', pointBorderColor: '#0A0A0A',
                    pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 7,
                },
                {
                    label: 'Gastos', data: allExpenses,
                    borderColor: 'rgba(245,245,245,0.55)', backgroundColor: 'rgba(245,245,245,0.04)',
                    tension: 0.4, fill: true, borderWidth: 2, borderDash: [6, 4],
                    pointBackgroundColor: 'rgba(245,245,245,0.7)', pointBorderColor: '#0A0A0A',
                    pointBorderWidth: 2, pointRadius: 3, pointHoverRadius: 6,
                },
                {
                    label: 'Beneficio', data: allProfit,
                    borderColor: '#10b981', backgroundColor: profGradient,
                    tension: 0.4, fill: false, borderWidth: 2,
                    pointBackgroundColor: '#10b981', pointBorderColor: '#0A0A0A',
                    pointBorderWidth: 2, pointRadius: 3, pointHoverRadius: 6,
                },
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#141414', borderColor: 'rgba(230,57,70,.3)', borderWidth: 1,
                    padding: 12, cornerRadius: 10, titleColor: '#F5F5F5',
                    bodyColor: 'rgba(245,245,245,.7)',
                    callbacks: { label: c => ' ' + c.dataset.label + ': $' + c.parsed.y.toLocaleString() }
                }
            },
            scales: {
                y: { beginAtZero: true,
                     grid: { color: 'rgba(245,245,245,.05)', drawBorder: false },
                     ticks: { color: 'rgba(245,245,245,.4)', font: { family: 'Inter', size: 11 },
                              callback: v => v >= 1000 ? '$' + (v/1000).toFixed(1) + 'K' : '$' + v } },
                x: { grid: { display: false },
                     ticks: { color: 'rgba(245,245,245,.4)', font: { family: 'Inter', size: 11 } } }
            }
        }
    });

    // Period selector
    document.querySelectorAll('.chart-period-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.chart-period-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const months = parseInt(btn.dataset.months);
            const label  = document.getElementById('chartPeriodLabel');
            label.textContent = `Últimos ${months} meses`;
            const start = allMonths.length - months;
            chart.data.labels          = allMonths.slice(start);
            chart.data.datasets[0].data = allIncome.slice(start);
            chart.data.datasets[1].data = allExpenses.slice(start);
            chart.data.datasets[2].data = allProfit.slice(start);
            chart.update();
        });
    });

    // Activity type filter
    document.querySelectorAll('#activityFilter .activity-filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#activityFilter .activity-filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filter = btn.dataset.filter;
            document.querySelectorAll('#activityList .activity-row').forEach(row => {
                row.style.display = (filter === 'all' || row.dataset.type === filter) ? 'flex' : 'none';
            });
        });
    });
})();
</script>
```

---

## Task 8: Bottom Row — Top Clientes + Conversión + Meta Mensual

**Files:**
- Modify: `resources/views/dashboard/index.blade.php` — add a new section after `</div>` closing the main-grid.

- [ ] **Step 1: Add bottom row CSS** (inside `<style>` tag)

```css
.bottom-grid { display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px; }
@media (max-width:900px) { .bottom-grid { grid-template-columns:1fr; } }

.top-client-row { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid var(--border-subtle); }
.top-client-row:last-child { border-bottom:none; }
.top-client-rank { width:24px; height:24px; border-radius:50%; background:var(--red-soft); color:var(--red-primary); font-size:11px; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.top-client-bar { flex:1; height:4px; background:var(--border-subtle); border-radius:4px; overflow:hidden; }
.top-client-fill { height:100%; background:var(--red-primary); border-radius:4px; transition:width .6s ease; }

.conversion-ring { position:relative; width:120px; height:120px; flex-shrink:0; }
.conversion-ring svg { transform:rotate(-90deg); }
.conversion-ring-label { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; }

.goal-bar-track { height:10px; background:var(--border-subtle); border-radius:10px; overflow:hidden; margin:12px 0; }
.goal-bar-fill { height:100%; border-radius:10px; background:linear-gradient(90deg, var(--red-primary), #FF6B6B); transition:width .8s ease; }
```

- [ ] **Step 2: Add the bottom-grid section after the main-grid closing tag**

```html
{{-- Bottom row: Top Clientes + Conversión & Meta --}}
<div class="bottom-grid">

    {{-- Top Clientes --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Top Clientes</div>
            <div style="font-size:12px;color:var(--text-muted);">Por ingresos totales</div>
        </div>
        @php $maxRevenue = $topClients->max('revenue') ?: 1; @endphp
        @forelse($topClients as $i => $client)
            <div class="top-client-row">
                <div class="top-client-rank">{{ $i + 1 }}</div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $client->name }}</div>
                    <div class="top-client-bar" style="margin-top:6px;">
                        <div class="top-client-fill" style="width:{{ round(($client->revenue / $maxRevenue) * 100) }}%;"></div>
                    </div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <div style="font-family:'Space Grotesk',sans-serif;font-size:14px;font-weight:700;color:var(--text-primary);">${{ number_format($client->revenue, 0) }}</div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">{{ $client->invoice_count }} {{ $client->invoice_count === 1 ? 'factura' : 'facturas' }}</div>
                </div>
            </div>
        @empty
            <div class="empty-state" style="padding:32px 0;">
                <div style="font-size:13px;">Aún no hay clientes con facturas</div>
            </div>
        @endforelse
    </div>

    {{-- Conversión + Meta mensual --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Rendimiento</div>
        </div>

        {{-- Conversion rate ring --}}
        <div style="display:flex;align-items:center;gap:24px;margin-bottom:28px;padding-bottom:24px;border-bottom:1px solid var(--border-subtle);">
            <div class="conversion-ring">
                @php
                    $circumference = 2 * M_PI * 44;
                    $dashOffset    = $circumference * (1 - $conversionRate / 100);
                @endphp
                <svg width="120" height="120" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="44" fill="none" stroke="var(--border-subtle)" stroke-width="10"/>
                    <circle cx="60" cy="60" r="44" fill="none" stroke="var(--red-primary)" stroke-width="10"
                        stroke-dasharray="{{ round($circumference, 2) }}"
                        stroke-dashoffset="{{ round($dashOffset, 2) }}"
                        stroke-linecap="round"/>
                </svg>
                <div class="conversion-ring-label">
                    <div style="font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:700;color:var(--text-primary);">{{ $conversionRate }}%</div>
                </div>
            </div>
            <div>
                <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Tasa de Conversión</div>
                <div style="font-size:12px;color:var(--text-muted);line-height:1.5;">
                    {{ $convertedQuotes }} de {{ $totalQuotes }} cotizaciones<br>
                    convertidas en facturas
                </div>
                <div style="margin-top:10px;">
                    @if($conversionRate >= 60)
                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;color:var(--positive);background:rgba(16,185,129,0.1);padding:3px 10px;border-radius:20px;">Excelente</span>
                    @elseif($conversionRate >= 35)
                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;color:var(--warning);background:rgba(245,158,11,0.1);padding:3px 10px;border-radius:20px;">Normal</span>
                    @else
                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;color:var(--negative);background:rgba(239,68,68,0.1);padding:3px 10px;border-radius:20px;">Mejorable</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Monthly goal --}}
        @php
            $goalPct = $monthGoal > 0 ? min(100, round(($thisMonthIncome / $monthGoal) * 100)) : 0;
        @endphp
        <div>
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:4px;">
                <div style="font-size:13px;font-weight:600;color:var(--text-primary);">Meta · {{ now()->locale('es')->isoFormat('MMMM') }}</div>
                <div style="font-size:12px;color:var(--text-muted);">${{ number_format($thisMonthIncome, 0) }} / ${{ number_format($monthGoal, 0) }}</div>
            </div>
            <div class="goal-bar-track">
                <div class="goal-bar-fill" style="width:{{ $goalPct }}%;"></div>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--text-muted);">
                <span>{{ $goalPct }}% completado</span>
                @if($goalPct >= 100)
                    <span style="color:var(--positive);font-weight:600;">✓ Meta alcanzada</span>
                @else
                    <span>${{ number_format($monthGoal - $thisMonthIncome, 0) }} restantes</span>
                @endif
            </div>
        </div>
    </div>

</div>
```

---

## Self-Review Checklist

**Spec coverage:**
- ✅ Header context subtitle with overdue/pending counts
- ✅ Remove duplicate "Nueva Cotización" from header (kept only in quick-actions and header CTA button)
- ✅ Alerts banner (overdue invoices + pending quotes with values)
- ✅ Hero KPI: 4 columns, period label, real income growth trend, "Por cobrar" metric
- ✅ Stats grid: clickable cards, period label on expenses, pending quotes value, overdue highlighted
- ✅ Quick actions: 5 tiles, correct invoice icon, "Ver Sitio Web" link
- ✅ Activity: full status text, clickable rows, type filter
- ✅ Chart: 3rd profit line, period selector (3M/6M), contrast fix on gastos line
- ✅ Top clients widget
- ✅ Conversion rate ring
- ✅ Monthly goal progress bar

**Placeholder scan:** No TBDs, all code is complete.

**Type consistency:** 
- `$profitData` defined in Task 1, used in Task 7 ✅
- `$overdueCount`/`$overdueAmount` defined in Task 1, used in Tasks 2 and 4 ✅
- `$pendingQuotesValue` defined in Task 1, used in Tasks 2 and 4 ✅
- `$topClients` defined in Task 1, used in Task 8 ✅
- `$conversionRate`, `$convertedQuotes`, `$totalQuotes` defined in Task 1, used in Task 8 ✅
- `$thisMonthIncome`, `$incomeGrowthPct` defined in Task 1, used in Task 3 ✅
- `$monthGoal` defined in Task 1, used in Task 8 ✅
