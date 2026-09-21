<x-layouts.app>
{{-- Dashboard — renderizado por DashboardController, sin Livewire --}}
<style>
.dash-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:32px;flex-wrap:wrap;gap:16px}
.dash-greeting{font-family:'Space Grotesk',sans-serif;font-size:32px;font-weight:700;color:var(--text-primary);line-height:1.1}
.dash-greeting span{color:#FFFFFF;border-bottom:2px solid rgba(255,255,255,0.3);padding-bottom:2px;}
.dash-subtitle{color:var(--text-muted);font-size:15px;margin-top:6px}
.hero-kpi{background:linear-gradient(135deg,rgba(255,255,255,.05),rgba(255,255,255,.015));border:1px solid rgba(255,255,255,.14);border-radius:20px;padding:32px;position:relative;overflow:hidden;margin-bottom:24px;box-shadow:0 8px 32px rgba(0,0,0,.4)}
.hero-kpi::before{content:'';position:absolute;top:-50%;right:-20%;width:400px;height:400px;background:radial-gradient(circle,rgba(255,255,255,.1) 0%,transparent 70%);opacity:.3;pointer-events:none}
.hero-kpi-grid{display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:32px;position:relative;z-index:1}
@media(max-width:900px){.hero-kpi-grid{grid-template-columns:1fr 1fr;gap:20px}}
@media(max-width:480px){.hero-kpi-grid{grid-template-columns:1fr}}
@media(max-width:768px){.hero-kpi{padding:24px}}
.hero-kpi-divider{border-left:1px solid var(--border-default);padding-left:32px}
@media(max-width:768px){.hero-kpi-divider{border-left:none;border-top:1px solid var(--border-default);padding-left:0;padding-top:20px}}
.hero-kpi-label{font-size:12px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.1em;font-weight:600;margin-bottom:12px}
.hero-kpi-value{font-family:'Space Grotesk',sans-serif;font-size:42px;font-weight:700;color:var(--text-primary);line-height:1;margin-bottom:8px}
.hero-kpi-value-large{font-size:56px;background:linear-gradient(135deg,#FFFFFF,#A1A1AA);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.hero-kpi-trend{display:inline-flex;align-items:center;gap:4px;font-size:13px;font-weight:600;color:var(--positive);background:rgba(16,185,129,.1);padding:4px 10px;border-radius:20px}
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px}
@media(max-width:1280px){.stats-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:480px){.stats-grid{grid-template-columns:1fr}}
.stat-card{background:var(--bg-card);backdrop-filter:blur(20px);border:1px solid var(--border-subtle);border-radius:16px;padding:20px;transition:all .3s ease;position:relative;overflow:hidden}
.stat-card:hover{border-color:rgba(255,255,255,.25);transform:translateY(-2px)}
.stat-card-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:16px}
.stat-card-icon.red{background:rgba(255,255,255,.08);color:#FFFFFF;border:1px solid rgba(255,255,255,.14)}
.stat-card-icon.green{background:rgba(16,185,129,.1);color:var(--positive)}
.stat-card-icon.amber{background:rgba(245,158,11,.1);color:var(--warning)}
.stat-card-icon.blue{background:rgba(59,130,246,.1);color:#60A5FA}
.stat-card-label{font-size:13px;color:var(--text-muted);font-weight:500;margin-bottom:4px}
.stat-card-value{font-family:'Space Grotesk',sans-serif;font-size:26px;font-weight:700;color:var(--text-primary)}
.quick-actions{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:24px;}
@media(max-width:900px){.quick-actions{grid-template-columns:repeat(3,1fr);}}
@media(max-width:480px){.quick-actions{grid-template-columns:repeat(2,1fr);}}
.quick-action{background:var(--bg-card);backdrop-filter:blur(20px);border:1px solid var(--border-subtle);border-radius:14px;padding:18px;text-decoration:none;color:var(--text-primary);display:flex;align-items:center;gap:12px;transition:all .25s ease}
.quick-action:hover{border-color:rgba(255,255,255,.25);transform:translateY(-2px);background:rgba(255,255,255,.05)}
.quick-action-icon{width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,.08);color:#FFFFFF;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.12)}
.quick-action-text{font-size:13px;font-weight:600;line-height:1.3}
.main-grid{display:grid;grid-template-columns:2fr 1fr;gap:24px;margin-bottom:24px}
@media(max-width:1280px){.main-grid{grid-template-columns:1fr}}
.activity-row{display:flex;align-items:center;gap:14px;padding:14px 8px;border-bottom:1px solid var(--border-subtle);border-radius:8px;margin:0 -8px;text-decoration:none;color:inherit;transition:background .15s ease;}
.activity-row:hover{background:rgba(255,255,255,.05);border-bottom-color:transparent;}
.activity-row:last-child{border-bottom:none;}
.activity-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:rgba(255,255,255,.08);color:#FFFFFF;border:1px solid rgba(255,255,255,.12)}
.activity-info{flex:1;min-width:0}
.activity-title{font-size:14px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.activity-meta{font-size:12px;color:var(--text-muted);margin-top:2px}
.activity-amount{font-family:'Space Grotesk',sans-serif;font-size:14px;font-weight:700;color:var(--text-primary);white-space:nowrap}
.status-badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.05em}
.status-pending{background:rgba(245,158,11,.15);color:var(--warning)}
.status-paid{background:rgba(16,185,129,.15);color:var(--positive)}
.status-converted{background:rgba(96,165,250,.15);color:#60A5FA}
.status-cancelled{background:rgba(239,68,68,.15);color:var(--negative)}
.empty-state{text-align:center;padding:48px 20px;color:var(--text-muted)}
.view-all{display:flex;align-items:center;justify-content:center;gap:6px;margin-top:16px;color:#FFFFFF;font-size:13px;font-weight:600;text-decoration:none;padding-top:16px;border-top:1px solid var(--border-subtle);transition:gap .2s ease}
.view-all:hover{gap:10px;color:#E4E4E7;}
.alert-card-red:hover { border-color: rgba(239,68,68,0.5) !important; }
.alert-card-amber:hover { border-color: rgba(245,158,11,0.5) !important; }
.hero-kpi-trend-neg{display:inline-flex;align-items:center;gap:4px;font-size:13px;font-weight:600;color:var(--negative);background:rgba(239,68,68,0.1);padding:4px 10px;border-radius:20px;}
.hero-kpi-trend-neutral{display:inline-flex;align-items:center;gap:4px;font-size:13px;font-weight:600;color:var(--text-muted);background:rgba(245,245,245,0.06);padding:4px 10px;border-radius:20px;}
.stat-card-sub{font-size:11px;color:var(--text-subtle);margin-top:3px;}
.activity-filter{display:flex;gap:8px;margin-bottom:12px;}
.activity-filter-btn{padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600;border:1px solid var(--border-subtle);background:transparent;color:var(--text-muted);cursor:pointer;transition:all .2s ease;}
.activity-filter-btn.active,.activity-filter-btn:hover{border-color:rgba(255,255,255,.3);background:rgba(255,255,255,.08);color:#FFFFFF;}
.chart-period-btn{padding:5px 14px;border-radius:20px;font-size:12px;font-weight:600;border:1px solid var(--border-subtle);background:transparent;color:var(--text-muted);cursor:pointer;transition:all .2s ease;}
.chart-period-btn.active{border-color:rgba(255,255,255,.3);background:rgba(255,255,255,.08);color:#FFFFFF;}
.bottom-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;}
@media(max-width:900px){.bottom-grid{grid-template-columns:1fr;}}
.top-client-row{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border-subtle);}
.top-client-row:last-child{border-bottom:none;}
.top-client-rank{width:24px;height:24px;border-radius:50%;background:rgba(255,255,255,.08);color:#FFFFFF;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.15);}
.top-client-bar{flex:1;height:4px;background:var(--border-subtle);border-radius:4px;overflow:hidden;}
.top-client-fill{height:100%;background:#FFFFFF;border-radius:4px;}
.conversion-ring{position:relative;width:120px;height:120px;flex-shrink:0;}
.conversion-ring svg{transform:rotate(-90deg);}
.conversion-ring-label{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;}
.goal-bar-track{height:10px;background:var(--border-subtle);border-radius:10px;overflow:hidden;margin:12px 0;}
.goal-bar-fill{height:100%;border-radius:10px;background:linear-gradient(90deg,#FFFFFF,#71717A);}
</style>

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
    <a href="{{ route('quotes.create') }}" class="btn btn-primary" style="height:fit-content;">
        <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nueva Cotización
    </a>
</div>

@if($overdueCount > 0 || $pendingQuotes > 0)
<div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
    @if($overdueCount > 0)
    <a href="{{ route('invoices.index') }}" class="alert-card-red" style="flex:1;min-width:240px;display:flex;align-items:center;gap:14px;padding:14px 18px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);border-radius:14px;text-decoration:none;transition:border-color .2s ease;">
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
    <a href="{{ route('quotes.index') }}" class="alert-card-amber" style="flex:1;min-width:240px;display:flex;align-items:center;gap:14px;padding:14px 18px;background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.25);border-radius:14px;text-decoration:none;transition:border-color .2s ease;">
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

<div class="quick-actions">
    <a href="{{ route('quotes.create') }}" class="quick-action">
        <div class="quick-action-icon">
            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div class="quick-action-text">Nueva<br>Cotización</div>
    </a>
    <a href="{{ route('invoices.create') }}" class="quick-action">
        <div class="quick-action-icon">
            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <div class="quick-action-text">Nueva<br>Factura</div>
    </a>
    <a href="{{ route('clients.index', ['create' => 1]) }}" class="quick-action">
        <div class="quick-action-icon">
            <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <div class="quick-action-text">Nuevo<br>Cliente</div>
    </a>
    <a href="{{ route('expenses.index', ['create' => 1]) }}" class="quick-action">
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

    <a href="{{ route('invoices.index') }}" class="stat-card" style="text-decoration:none;color:inherit;{{ $overdueCount > 0 ? 'border-color:rgba(239,68,68,0.35);' : '' }}">
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

<div class="main-grid">
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

    <div class="card">
        <div class="card-header">
            <div class="card-title">Actividad Reciente</div>
        </div>
        <div class="activity-filter" id="activityFilter">
            <button class="activity-filter-btn active" data-filter="all">Todos</button>
            <button class="activity-filter-btn" data-filter="quote">Cotizaciones</button>
            <button class="activity-filter-btn" data-filter="invoice">Facturas</button>
            <button class="activity-filter-btn" data-filter="bill">Cuentas de Cobro</button>
        </div>
        <div id="activityList">
            @forelse($recentDocuments as $doc)
                <a href="{{ match ($doc->type) { 'quote' => route('quotes.index'), 'bill' => route('bills.index'), default => route('invoices.index') } }}"
                   class="activity-row"
                   data-type="{{ $doc->type }}">
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
                Ir a facturas
                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        @endif
    </div>
</div>

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

    {{-- Rendimiento: Conversión + Meta --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Rendimiento</div>
        </div>

        {{-- Conversion ring --}}
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
    incGradient.addColorStop(0, 'rgba(255,255,255,0.28)');
    incGradient.addColorStop(1, 'rgba(255,255,255,0.01)');

    const profGradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 320);
    profGradient.addColorStop(0, 'rgba(212,212,216,0.18)');
    profGradient.addColorStop(1, 'rgba(212,212,216,0)');

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: allMonths,
            datasets: [
                {
                    label: 'Ingresos', data: allIncome,
                    borderColor: '#FFFFFF', backgroundColor: incGradient,
                    tension: 0.4, fill: true, borderWidth: 2.5,
                    pointBackgroundColor: '#FFFFFF', pointBorderColor: '#0A0A0A',
                    pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 7,
                },
                {
                    label: 'Gastos', data: allExpenses,
                    borderColor: 'rgba(245,245,245,0.45)', backgroundColor: 'rgba(245,245,245,0.03)',
                    tension: 0.4, fill: true, borderWidth: 2, borderDash: [6, 4],
                    pointBackgroundColor: 'rgba(245,245,245,0.7)', pointBorderColor: '#0A0A0A',
                    pointBorderWidth: 2, pointRadius: 3, pointHoverRadius: 6,
                },
                {
                    label: 'Beneficio', data: allProfit,
                    borderColor: '#D4D4D8', backgroundColor: profGradient,
                    tension: 0.4, fill: false, borderWidth: 2,
                    pointBackgroundColor: '#D4D4D8', pointBorderColor: '#0A0A0A',
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
                    backgroundColor: '#141414', borderColor: 'rgba(255,255,255,.2)', borderWidth: 1,
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
            label.textContent = 'Últimos ' + months + ' meses';
            const start = allMonths.length - months;
            chart.data.labels           = allMonths.slice(start);
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
<style>
    :focus-visible { outline: 2px solid #E63946; outline-offset: 3px; }
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; scroll-behavior: auto !important; }
    }
</style>
</x-layouts.app>
