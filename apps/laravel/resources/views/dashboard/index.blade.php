<x-layouts.app>
{{-- Dashboard — renderizado por DashboardController, sin Livewire --}}
<style>
.dash-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:28px;flex-wrap:wrap;gap:16px}
.dash-greeting{font-family:'Google Sans Flex',-apple-system,BlinkMacSystemFont,sans-serif;font-size:32px;font-weight:700;color:var(--text-primary);line-height:1.1}
.dash-greeting span{color:#FFFFFF;border-bottom:2px solid rgba(255,255,255,0.3);padding-bottom:2px;}
.dash-subtitle{color:var(--text-muted);font-size:15px;margin-top:6px}

/* ── Section Subheaders ── */
.dash-section-head{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:14px}
.dash-section-title{display:flex;align-items:center;gap:10px;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-secondary)}
.live-pulse-dot{width:8px;height:8px;border-radius:50%;background:#10B981;box-shadow:0 0 10px #10B981;display:inline-block;animation:livePulse 2s infinite}
@keyframes livePulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.45;transform:scale(.88)}}
.range-pills{display:inline-flex;align-items:center;gap:4px;background:rgba(255,255,255,.04);border:1px solid var(--border-subtle);border-radius:999px;padding:3px}
.range-pill{padding:5px 12px;border-radius:999px;font-size:11.5px;font-weight:600;color:var(--text-muted);text-decoration:none;transition:all .2s ease;white-space:nowrap}
.range-pill:hover{color:#FFFFFF}
.range-pill.active{background:rgba(255,255,255,.14);color:#FFFFFF;box-shadow:inset 0 1px 0 rgba(255,255,255,.15)}

/* ── 1. Real Web Analytics Section ── */
.analytics-shell{background:linear-gradient(145deg,rgba(24,24,28,.92),rgba(14,14,16,.96));border:1px solid rgba(255,255,255,.13);border-radius:20px;padding:24px;margin-bottom:24px;box-shadow:0 12px 36px rgba(0,0,0,.45);max-width:100%;box-sizing:border-box;position:relative;overflow:hidden}
.analytics-kpi-strip{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;padding-bottom:22px;margin-bottom:22px;border-bottom:1px solid var(--border-subtle)}
.analytics-kpi-item{min-width:0;padding:14px 16px;background:rgba(255,255,255,.025);border:1px solid rgba(255,255,255,.07);border-radius:14px}
.analytics-kpi-label{font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;font-weight:600;margin-bottom:8px;display:flex;align-items:center;justify-content:space-between;gap:6px}
.analytics-kpi-val{font-family:'Geist Mono','Geist',monospace;font-size:clamp(22px,2.2vw,30px);font-weight:700;color:var(--text-primary);line-height:1.1;font-variant-numeric:tabular-nums}
.analytics-kpi-sub{font-size:11.5px;color:var(--text-muted);margin-top:6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}

.analytics-detail-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px}
.analytics-panel{background:rgba(0,0,0,.28);border:1px solid var(--border-subtle);border-radius:14px;padding:18px;min-width:0}
.analytics-panel-header{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:14px}
.analytics-panel-title{font-size:14px;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:8px}
.analytics-panel-sub{font-size:11.5px;color:var(--text-muted)}
.analytics-row{display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid rgba(255,255,255,.05);min-width:0}
.analytics-row:last-child{border-bottom:none}
.analytics-bar-track{height:5px;background:rgba(255,255,255,.07);border-radius:999px;overflow:hidden;margin-top:5px}
.analytics-bar-fill{height:100%;border-radius:999px;background:#FFFFFF}

/* ── 2. Financial Summary Chart + Recent Activity ── */
.main-grid{display:grid;grid-template-columns:minmax(0,2fr) minmax(0,1fr);gap:24px;margin-bottom:24px;max-width:100%}
@media(max-width:1280px){.main-grid{grid-template-columns:minmax(0,1fr);gap:16px}}
.chart-canvas-wrap{position:relative;width:100%;max-width:100%;height:320px;overflow:hidden}
.activity-row{display:flex;align-items:center;gap:12px;padding:14px 8px;border-bottom:1px solid var(--border-subtle);border-radius:8px;margin:0 -8px;text-decoration:none;color:inherit;transition:background .15s ease;min-width:0;max-width:100%;box-sizing:border-box}
.activity-row:hover{background:rgba(255,255,255,.05);border-bottom-color:transparent;}
.activity-row:last-child{border-bottom:none;}
.activity-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:rgba(255,255,255,.08);color:#FFFFFF;border:1px solid rgba(255,255,255,.12)}
.activity-info{flex:1;min-width:0;overflow:hidden}
.activity-title{font-size:14px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.activity-meta{font-size:12px;color:var(--text-muted);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.activity-amount{font-family:'Geist Mono','Geist',monospace;font-size:14px;font-weight:700;color:var(--text-primary);white-space:nowrap;font-feature-settings:'tnum' 1,'zero' 1;font-variant-numeric:tabular-nums;letter-spacing:-0.02em}
.status-badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap}
.status-pending{background:rgba(245,158,11,.15);color:var(--warning)}
.status-paid{background:rgba(16,185,129,.15);color:var(--positive)}
.status-converted{background:rgba(96,165,250,.15);color:#60A5FA}
.status-cancelled{background:rgba(239,68,68,.15);color:var(--negative)}
.empty-state{text-align:center;padding:36px 16px;color:var(--text-muted)}
.view-all{display:flex;align-items:center;justify-content:center;gap:6px;margin-top:16px;color:#FFFFFF;font-size:13px;font-weight:600;text-decoration:none;padding-top:16px;border-top:1px solid var(--border-subtle);transition:gap .2s ease}
.view-all:hover{gap:10px;color:#E4E4E7;}
.activity-filter{display:flex;gap:6px;margin-bottom:12px;overflow-x:auto;flex-wrap:nowrap;-webkit-overflow-scrolling:touch;scrollbar-width:none;padding-bottom:2px;max-width:100%}
.activity-filter::-webkit-scrollbar{display:none}
.activity-filter-btn{padding:5px 12px;border-radius:20px;font-size:12px;font-weight:600;border:1px solid var(--border-subtle);background:transparent;color:var(--text-muted);cursor:pointer;transition:all .2s ease;white-space:nowrap;flex-shrink:0}
.activity-filter-btn.active,.activity-filter-btn:hover{border-color:rgba(255,255,255,.3);background:rgba(255,255,255,.08);color:#FFFFFF;}
.chart-period-btn{padding:5px 12px;border-radius:20px;font-size:12px;font-weight:600;border:1px solid var(--border-subtle);background:transparent;color:var(--text-muted);cursor:pointer;transition:all .2s ease;white-space:nowrap}
.chart-period-btn.active{border-color:rgba(255,255,255,.3);background:rgba(255,255,255,.08);color:#FFFFFF;}

/* ── 3. Operational Document Counters (Quotes, Invoices, Bills, Expenses) ── */
.stats-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-bottom:24px;max-width:100%}
@media(max-width:1280px){.stats-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
.stat-card{background:var(--bg-card);backdrop-filter:blur(20px);border:1px solid var(--border-subtle);border-radius:16px;padding:20px;transition:all .3s ease;position:relative;overflow:hidden;min-width:0;max-width:100%;box-sizing:border-box;text-decoration:none;color:inherit;display:block}
.stat-card:hover{border-color:rgba(255,255,255,.25);transform:translateY(-2px)}
.stat-card-top{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:14px}
.stat-card-icon{width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.stat-card-icon.red{background:rgba(255,255,255,.08);color:#FFFFFF;border:1px solid rgba(255,255,255,.14)}
.stat-card-icon.green{background:rgba(16,185,129,.1);color:var(--positive)}
.stat-card-icon.amber{background:rgba(245,158,11,.1);color:var(--warning)}
.stat-card-icon.blue{background:rgba(59,130,246,.1);color:#60A5FA}
.stat-card-icon.purple{background:rgba(168,85,247,.12);color:#C084FC}
.stat-card-label{font-size:13px;color:var(--text-muted);font-weight:600;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.stat-card-value{font-family:'Geist Mono','Geist',monospace;font-size:clamp(22px,2vw,28px);font-weight:700;color:var(--text-primary);font-feature-settings:'tnum' 1,'zero' 1;font-variant-numeric:tabular-nums;letter-spacing:-0.02em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.stat-card-sub{font-size:11.5px;color:var(--text-subtle);margin-top:5px;font-family:'Geist Mono','Geist',monospace;font-variant-numeric:tabular-nums;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}

/* ── 4. Financial Hero KPI Block (Facturado, Cobrado, Por Cobrar, Neto) ── */
.hero-kpi{background:linear-gradient(135deg,rgba(255,255,255,.05),rgba(255,255,255,.015));border:1px solid rgba(255,255,255,.14);border-radius:20px;padding:28px 30px;position:relative;overflow:hidden;margin-bottom:24px;box-shadow:0 8px 32px rgba(0,0,0,.4);max-width:100%;box-sizing:border-box}
.hero-kpi::before{content:'';position:absolute;top:-50%;right:-20%;width:400px;height:400px;background:radial-gradient(circle,rgba(255,255,255,.1) 0%,transparent 70%);opacity:.3;pointer-events:none}
.hero-kpi-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:24px;position:relative;z-index:1}
.hero-kpi-grid > div{min-width:0}
.hero-kpi-divider{border-left:1px solid var(--border-default);padding-left:24px}
.hero-kpi-label{font-size:11.5px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.1em;font-weight:600;margin-bottom:10px}
.hero-kpi-value{font-family:'Geist Mono','Geist',monospace;font-size:clamp(24px,2.3vw,36px);font-weight:700;color:var(--text-primary);line-height:1.05;margin-bottom:8px;font-feature-settings:'tnum' 1,'zero' 1;font-variant-numeric:tabular-nums;letter-spacing:-0.03em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.hero-kpi-value-large{font-size:clamp(26px,2.6vw,42px);background:linear-gradient(135deg,#FFFFFF,#A1A1AA);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.hero-kpi-trend{display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;color:var(--positive);background:rgba(16,185,129,.1);padding:4px 10px;border-radius:20px;max-width:100%}
.hero-kpi-trend-neg{display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;color:var(--negative);background:rgba(239,68,68,0.1);padding:4px 10px;border-radius:20px;max-width:100%}
.hero-kpi-trend-neutral{display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;color:var(--text-muted);background:rgba(245,245,245,0.06);padding:4px 10px;border-radius:20px;}

/* ── 5. Quick Actions, Top Clients & Performance ── */
.quick-actions{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:12px;margin-bottom:24px;max-width:100%}
.quick-action{background:var(--bg-card);backdrop-filter:blur(20px);border:1px solid var(--border-subtle);border-radius:14px;padding:18px;text-decoration:none;color:var(--text-primary);display:flex;align-items:center;gap:12px;transition:all .25s ease;min-width:0;box-sizing:border-box}
.quick-action:hover{border-color:rgba(255,255,255,.25);transform:translateY(-2px);background:rgba(255,255,255,.05)}
.quick-action-icon{width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,.08);color:#FFFFFF;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.12)}
.quick-action-text{font-size:13px;font-weight:600;line-height:1.3;min-width:0}

.bottom-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:24px;margin-bottom:24px;max-width:100%}
.top-client-row{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border-subtle);min-width:0;width:100%;box-sizing:border-box}
.top-client-row:last-child{border-bottom:none;}
.top-client-rank{width:24px;height:24px;border-radius:50%;background:rgba(255,255,255,.08);color:#FFFFFF;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.15);}
.top-client-bar{flex:1;height:4px;background:var(--border-subtle);border-radius:4px;overflow:hidden;}
.top-client-fill{height:100%;background:#FFFFFF;border-radius:4px;}
.conversion-summary-row{display:flex;align-items:center;gap:24px;margin-bottom:28px;padding-bottom:24px;border-bottom:1px solid var(--border-subtle);min-width:0}
.conversion-ring{position:relative;width:120px;height:120px;flex-shrink:0;}
.conversion-ring svg{transform:rotate(-90deg);width:100%;height:100%}
.conversion-ring-label{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;}
.goal-bar-track{height:10px;background:var(--border-subtle);border-radius:10px;overflow:hidden;margin:12px 0;}
.goal-bar-fill{height:100%;border-radius:10px;background:linear-gradient(90deg,#FFFFFF,#71717A);}
.alert-card-red:hover { border-color: rgba(239,68,68,0.5) !important; }
.alert-card-amber:hover { border-color: rgba(245,158,11,0.5) !important; }

/* ── Responsive Breakpoints ── */
@media(max-width:1024px){
    .analytics-kpi-strip{grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
    .analytics-detail-grid{grid-template-columns:minmax(0,1fr)}
    .hero-kpi-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:20px}
    .hero-kpi-divider{border-left:none;border-top:1px solid var(--border-default);padding-left:0;padding-top:16px}
    .quick-actions{grid-template-columns:repeat(3,minmax(0,1fr))}
    .bottom-grid{grid-template-columns:minmax(0,1fr);gap:16px}
}
@media(max-width:640px){
    .dash-header{margin-bottom:16px;}
    .dash-header .btn-primary{display:none;}
    .dash-greeting{font-size:24px;}
    .dash-subtitle{font-size:13px;}
    .analytics-shell{padding:16px 14px;border-radius:16px;margin-bottom:16px}
    .analytics-kpi-strip{grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;padding-bottom:16px;margin-bottom:16px}
    .analytics-kpi-item{padding:12px}
    .analytics-kpi-val{font-size:20px}
    .analytics-panel{padding:14px 12px}
    .stats-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;margin-bottom:16px;}
    .stat-card{padding:14px 12px;border-radius:14px;}
    .stat-card-top{margin-bottom:10px}
    .stat-card-icon{width:34px;height:34px;border-radius:10px;}
    .stat-card-icon svg{width:17px !important;height:17px !important;}
    .stat-card-label{font-size:11.5px;}
    .stat-card-value{font-size:20px;}
    .stat-card-sub{font-size:10.5px;}
    .hero-kpi{padding:16px 14px;border-radius:16px;margin-bottom:16px;}
    .hero-kpi-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;}
    .hero-kpi-grid > div:first-child{grid-column:1 / -1;}
    .hero-kpi-grid > div:nth-child(4){grid-column:1 / -1;}
    .hero-kpi-divider{padding-top:14px;}
    .hero-kpi-label{font-size:11px;margin-bottom:6px;}
    .hero-kpi-value{font-size:22px;margin-bottom:4px;}
    .hero-kpi-value-large{font-size:28px;}
    .quick-actions{grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;margin-bottom:16px;}
    .quick-actions > a:last-child{grid-column:1 / -1;justify-content:center;}
    .quick-action{padding:12px 10px;gap:8px;}
    .quick-action-icon{width:34px;height:34px;}
    .quick-action-text{font-size:12px;}
    .chart-canvas-wrap{height:220px;}
    .activity-row{margin:0;padding:12px 2px;gap:10px;}
    .activity-icon{width:34px;height:34px;}
    .activity-title{font-size:13px;}
    .activity-meta{font-size:11px;}
    .activity-amount{font-size:13px;}
    .conversion-summary-row{gap:14px;margin-bottom:18px;padding-bottom:18px;}
    .conversion-ring{width:92px;height:92px;}
}
</style>

{{-- ══════════════════════════════════════════════════════════════════════════
     CABECERA
══════════════════════════════════════════════════════════════════════════ --}}
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
    <a href="{{ route('invoices.index') }}" class="alert-card-red" style="flex:1 1 240px;min-width:0;display:flex;align-items:center;gap:14px;padding:14px 16px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);border-radius:14px;text-decoration:none;transition:border-color .2s ease;box-sizing:border-box;">
        <div style="width:38px;height:38px;border-radius:10px;background:rgba(239,68,68,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:18px;height:18px;color:var(--negative);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div style="min-width:0;">
            <div style="font-size:13px;font-weight:700;color:var(--negative);">{{ $overdueCount }} {{ $overdueCount === 1 ? 'Factura Vencida' : 'Facturas Vencidas' }}</div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">${{ number_format($overdueAmount, 0) }} pendiente de cobro · Ver facturas →</div>
        </div>
    </a>
    @endif
    @if($pendingQuotes > 0)
    <a href="{{ route('quotes.index') }}" class="alert-card-amber" style="flex:1 1 240px;min-width:0;display:flex;align-items:center;gap:14px;padding:14px 16px;background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.25);border-radius:14px;text-decoration:none;transition:border-color .2s ease;box-sizing:border-box;">
        <div style="width:38px;height:38px;border-radius:10px;background:rgba(245,158,11,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg style="width:18px;height:18px;color:var(--warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div style="min-width:0;">
            <div style="font-size:13px;font-weight:700;color:var(--warning);">{{ $pendingQuotes }} {{ $pendingQuotes === 1 ? 'Cotización Pendiente' : 'Cotizaciones Pendientes' }}</div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">${{ number_format($pendingQuotesValue, 0) }} en espera · Ver cotizaciones →</div>
        </div>
    </a>
    @endif
</div>
@endif

{{-- ══════════════════════════════════════════════════════════════════════════
     1️⃣ SECCIÓN 1 (ARRIBA): AUDIENCIA, UBICACIÓN Y REDES SOCIALES (REAL)
══════════════════════════════════════════════════════════════════════════ --}}
<div class="analytics-shell">
    <div class="dash-section-head" style="margin-bottom:18px;">
        <div class="dash-section-title">
            <span class="live-pulse-dot"></span>
            <span>Audiencia Web, Países/Ciudades y Redes Sociales (Tiempo Real)</span>
        </div>
        <div class="range-pills">
            <a href="{{ route('dashboard', ['range' => 'today']) }}" class="range-pill {{ $analyticsRange === 'today' ? 'active' : '' }}">Hoy</a>
            <a href="{{ route('dashboard', ['range' => '7d']) }}" class="range-pill {{ $analyticsRange === '7d' ? 'active' : '' }}">7 Días</a>
            <a href="{{ route('dashboard', ['range' => '30d']) }}" class="range-pill {{ $analyticsRange === '30d' ? 'active' : '' }}">30 Días</a>
            <a href="{{ route('dashboard', ['range' => 'all']) }}" class="range-pill {{ $analyticsRange === 'all' ? 'active' : '' }}">Todo</a>
        </div>
    </div>

    {{-- 4 KPIs de Tráfico --}}
    <div class="analytics-kpi-strip">
        <div class="analytics-kpi-item">
            <div class="analytics-kpi-label">
                <span>Visitantes Únicos</span>
                <svg style="width:15px;height:15px;color:#60A5FA;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="analytics-kpi-val">{{ number_format($uniqueVisitors) }}</div>
            <div class="analytics-kpi-sub">{{ number_format($todayVisitors) }} {{ $todayVisitors === 1 ? 'visitante hoy' : 'visitantes hoy' }}</div>
        </div>

        <div class="analytics-kpi-item">
            <div class="analytics-kpi-label">
                <span>Vistas de Página</span>
                <svg style="width:15px;height:15px;color:#10B981;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <div class="analytics-kpi-val">{{ number_format($totalPageviews) }}</div>
            <div class="analytics-kpi-sub">Páginas públicas vistas</div>
        </div>

        <div class="analytics-kpi-item">
            <div class="analytics-kpi-label">
                <span>Desde Redes Sociales</span>
                <svg style="width:15px;height:15px;color:#F43F5E;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
            </div>
            <div class="analytics-kpi-val">{{ number_format($socialVisits) }}</div>
            <div class="analytics-kpi-sub">{{ $socialSharePct }}% del tráfico total</div>
        </div>

        <div class="analytics-kpi-item">
            <div class="analytics-kpi-label">
                <span>Dispositivos & Leads</span>
                <svg style="width:15px;height:15px;color:#FBBF24;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="analytics-kpi-val">{{ $mobileSharePct }}% <span style="font-size:13px;font-weight:500;color:var(--text-muted);">Móvil</span></div>
            <div class="analytics-kpi-sub">{{ $totalLeadsCount }} prospectos web capturados</div>
        </div>
    </div>

    {{-- Desglose: Países/Ciudades vs Redes Sociales/Canales --}}
    <div class="analytics-detail-grid">
        {{-- Panel Izquierdo: Países y Ciudades --}}
        <div class="analytics-panel">
            <div class="analytics-panel-header">
                <div class="analytics-panel-title">
                    <span>🌍</span>
                    <span>Países y Ciudades de Origen</span>
                </div>
                <div class="analytics-panel-sub">Geolocalización Real</div>
            </div>

            @forelse($topLocations as $loc)
                <div class="analytics-row">
                    <div style="font-size:18px;line-height:1;flex-shrink:0;">{{ $loc->flag }}</div>
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;justify-content:space-between;align-items:baseline;gap:8px;">
                            <div style="font-size:13px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $loc->label }}
                            </div>
                            <div style="font-family:'Geist Mono','Geist',monospace;font-size:12px;font-weight:700;color:var(--text-primary);white-space:nowrap;flex-shrink:0;">
                                {{ number_format($loc->visits) }} <span style="font-weight:400;color:var(--text-muted);">({{ $loc->pct }}%)</span>
                            </div>
                        </div>
                        <div class="analytics-bar-track">
                            <div class="analytics-bar-fill" style="width:{{ max(4, $loc->pct) }}%;"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="padding:28px 12px;">
                    <div style="font-size:13px;font-weight:600;color:var(--text-secondary);">Esperando las primeras visitas públicas</div>
                    <div style="font-size:11.5px;margin-top:4px;">En cuanto alguien visite el sitio web, portafolio o perfil Kamo, verás aquí su país y ciudad real.</div>
                </div>
            @endforelse
        </div>

        {{-- Panel Derecho: Redes Sociales, Canales y Páginas --}}
        <div class="analytics-panel">
            <div class="analytics-panel-header">
                <div class="analytics-panel-title">
                    <span>📱</span>
                    <span>Redes Sociales y Fuentes de Tráfico</span>
                </div>
                <div class="analytics-panel-sub">Detección UTM / Referer / App</div>
            </div>

            @forelse($trafficSources as $src)
                <div class="analytics-row">
                    <span style="width:10px;height:10px;border-radius:50%;background:{{ $src->color }};display:inline-block;flex-shrink:0;box-shadow:0 0 8px {{ $src->color }}66;"></span>
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;justify-content:space-between;align-items:baseline;gap:8px;">
                            <div style="font-size:13px;font-weight:600;color:var(--text-primary);display:flex;align-items:center;gap:6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                <span>{{ $src->label }}</span>
                                @if($src->is_social)
                                    <span style="font-size:9.5px;padding:1px 6px;border-radius:999px;background:rgba(244,63,94,.14);color:#FB7185;font-weight:700;text-transform:uppercase;">Red Social</span>
                                @endif
                            </div>
                            <div style="font-family:'Geist Mono','Geist',monospace;font-size:12px;font-weight:700;color:var(--text-primary);white-space:nowrap;flex-shrink:0;">
                                {{ number_format($src->visits) }} <span style="font-weight:400;color:var(--text-muted);">({{ $src->pct }}%)</span>
                            </div>
                        </div>
                        <div class="analytics-bar-track">
                            <div class="analytics-bar-fill" style="width:{{ max(4, $src->pct) }}%;background:{{ $src->color }};"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="padding:28px 12px;">
                    <div style="font-size:13px;font-weight:600;color:var(--text-secondary);">Sin registros de canales en este período</div>
                    <div style="font-size:11.5px;margin-top:4px;">Detecta automáticamente Instagram, TikTok, Facebook, WhatsApp, LinkedIn, Google y tráfico directo.</div>
                </div>
            @endforelse

            @if(count($topPages) > 0)
                <div style="margin-top:14px;padding-top:12px;border-top:1px dashed var(--border-subtle);">
                    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:var(--text-muted);font-weight:700;margin-bottom:8px;">Páginas más visitadas</div>
                    <div style="display:flex;flex-wrap:wrap;gap:6px;">
                        @foreach($topPages as $pg)
                            <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:8px;background:rgba(255,255,255,.04);border:1px solid var(--border-subtle);font-size:11.5px;color:var(--text-secondary);">
                                <span>{{ $pg->label }}</span>
                                <strong style="font-family:'Geist Mono','Geist',monospace;color:#FFFFFF;">{{ $pg->visits }}</strong>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     2️⃣ SECCIÓN 2: RESUMEN FINANCIERO + ACTIVIDAD RECIENTE
══════════════════════════════════════════════════════════════════════════ --}}
<div class="main-grid">
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Resumen Financiero</div>
                <div style="font-size:13px;color:var(--text-muted);margin-top:4px;" id="chartPeriodLabel">Últimos 6 meses</div>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap;width:auto;max-width:100%;">
                <div style="display:flex;gap:10px;font-size:11.5px;flex-wrap:wrap;">
                    <div style="display:flex;align-items:center;gap:5px;">
                        <span style="width:9px;height:9px;border-radius:50%;background:var(--red-primary);display:inline-block;flex-shrink:0;"></span>
                        <span style="color:var(--text-muted);">Ingresos</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:5px;">
                        <span style="width:9px;height:9px;border-radius:50%;background:rgba(245,245,245,0.55);display:inline-block;flex-shrink:0;"></span>
                        <span style="color:var(--text-muted);">Gastos</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:5px;">
                        <span style="width:9px;height:9px;border-radius:50%;background:#10b981;display:inline-block;flex-shrink:0;"></span>
                        <span style="color:var(--text-muted);">Beneficio</span>
                    </div>
                </div>
                <div style="display:flex;gap:6px;">
                    <button class="chart-period-btn active" data-months="6">6M</button>
                    <button class="chart-period-btn" data-months="3">3M</button>
                </div>
            </div>
        </div>
        <div class="chart-canvas-wrap"><canvas id="dashboardChart"></canvas></div>
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

{{-- ══════════════════════════════════════════════════════════════════════════
     3️⃣ SECCIÓN 3: CONTADORES OPERATIVOS (COTIZACIONES, FACTURAS, CUENTAS DE COBRO, GASTOS)
══════════════════════════════════════════════════════════════════════════ --}}
<div class="stats-grid">
    {{-- 1. Cotizaciones --}}
    <a href="{{ route('quotes.index') }}" class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon amber">
                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            @if($pendingQuotes > 0)
                <span class="status-badge status-pending">{{ $pendingQuotes }} pend.</span>
            @endif
        </div>
        <div class="stat-card-label">Cotizaciones</div>
        <div class="stat-card-value">{{ $quotesTotalCount }}</div>
        <div class="stat-card-sub">
            {{ $pendingQuotes > 0 ? '$'.number_format($pendingQuotesValue, 0).' en espera' : 'Todas gestionadas ✓' }}
        </div>
    </a>

    {{-- 2. Facturas --}}
    <a href="{{ route('invoices.index') }}" class="stat-card" style="{{ $overdueCount > 0 ? 'border-color:rgba(239,68,68,0.35);' : '' }}">
        <div class="stat-card-top">
            <div class="stat-card-icon {{ $overdueCount > 0 ? 'red' : 'blue' }}">
                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-4-7 4V5a2 2 0 012-2h10a2 2 0 012 2v16z"/>
                </svg>
            </div>
            @if($overdueCount > 0)
                <span class="status-badge status-cancelled">{{ $overdueCount }} venc.</span>
            @else
                <span class="status-badge status-paid">{{ $paidInvoicesCount }} pag.</span>
            @endif
        </div>
        <div class="stat-card-label">Facturas</div>
        <div class="stat-card-value">{{ $invoicesTotalCount }}</div>
        <div class="stat-card-sub">
            {{ $overdueCount > 0 ? '$'.number_format($overdueAmount, 0).' vencido' : 'Sin vencimientos ✓' }}
        </div>
    </a>

    {{-- 3. Cuentas de Cobro --}}
    <a href="{{ route('bills.index') }}" class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon purple">
                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            @if($pendingBillsCount > 0)
                <span class="status-badge status-pending">{{ $pendingBillsCount }} pend.</span>
            @endif
        </div>
        <div class="stat-card-label">Cuentas de Cobro</div>
        <div class="stat-card-value">{{ $billsTotalCount }}</div>
        <div class="stat-card-sub">
            {{ $pendingBillsCount > 0 ? '$'.number_format($pendingBillsAmount, 0).' por cobrar' : 'Al día ✓' }}
        </div>
    </a>

    {{-- 4. Gastos --}}
    <a href="{{ route('expenses.index') }}" class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon red">
                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                </svg>
            </div>
            <span style="font-size:11px;color:var(--text-muted);font-family:'Geist Mono','Geist',monospace;">{{ $expensesTotalCount }} reg.</span>
        </div>
        <div class="stat-card-label">Gastos · {{ now()->locale('es')->isoFormat('MMMM') }}</div>
        <div class="stat-card-value">${{ number_format($thisMonthExpenses, 0) }}</div>
        <div class="stat-card-sub">Histórico: ${{ number_format($totalExpenses, 0) }}</div>
    </a>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     4️⃣ SECCIÓN 4: CONSOLIDADO FINANCIERO (FACTURADO, COBRADO, POR COBRAR, NETO)
══════════════════════════════════════════════════════════════════════════ --}}
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

{{-- ══════════════════════════════════════════════════════════════════════════
     5️⃣ SECCIÓN 5: ACCIONES RÁPIDAS, TOP CLIENTES Y RENDIMIENTO
══════════════════════════════════════════════════════════════════════════ --}}
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

<div class="bottom-grid">

    {{-- Top Clientes --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Top Clientes ({{ $totalClients }} registrados)</div>
            <div style="font-size:12px;color:var(--text-muted);">Por ingresos totales</div>
        </div>
        @php $maxRevenue = $topClients->max('revenue') ?: 1; @endphp
        @forelse($topClients as $i => $client)
            <div class="top-client-row">
                <div class="top-client-rank">{{ $i + 1 }}</div>
                <div style="flex:1;min-width:0;overflow:hidden;">
                    <div style="font-size:13px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $client->name }}</div>
                    <div class="top-client-bar" style="margin-top:6px;">
                        <div class="top-client-fill" style="width:{{ round(($client->revenue / $maxRevenue) * 100) }}%;"></div>
                    </div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <div style="font-family:'Geist Mono','Geist',monospace;font-size:14px;font-weight:700;color:var(--text-primary);font-feature-settings:'tnum' 1,'zero' 1;font-variant-numeric:tabular-nums;white-space:nowrap;">${{ number_format($client->revenue, 0) }}</div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:2px;white-space:nowrap;">{{ $client->invoice_count }} {{ $client->invoice_count === 1 ? 'factura' : 'facturas' }}</div>
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
        <div class="conversion-summary-row">
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
                    <div style="font-family:'Geist Mono','Geist',monospace;font-size:20px;font-weight:700;color:var(--text-primary);font-feature-settings:'tnum' 1,'zero' 1;font-variant-numeric:tabular-nums;letter-spacing:-0.02em;">{{ $conversionRate }}%</div>
                </div>
            </div>
            <div style="min-width:0;flex:1;">
                <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Tasa de Conversión</div>
                <div style="font-size:12px;color:var(--text-muted);line-height:1.5;">
                    {{ $convertedQuotes }} de {{ $totalQuotes }} cotizaciones convertidas en facturas
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
            <div style="display:flex;justify-content:space-between;align-items:baseline;flex-wrap:wrap;gap:6px;margin-bottom:4px;">
                <div style="font-size:13px;font-weight:600;color:var(--text-primary);">Meta · {{ now()->locale('es')->isoFormat('MMMM') }}</div>
                <div style="font-size:12px;color:var(--text-muted);font-family:'Geist Mono','Geist',monospace;">${{ number_format($thisMonthIncome, 0) }} / ${{ number_format($monthGoal, 0) }}</div>
            </div>
            <div class="goal-bar-track">
                <div class="goal-bar-fill" style="width:{{ $goalPct }}%;"></div>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:6px;font-size:12px;color:var(--text-muted);">
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
                     ticks: { color: 'rgba(245,245,245,.4)', font: { family: "'Geist Mono', monospace", size: 11 },
                              callback: v => v >= 1000 ? '$' + (v/1000).toFixed(1) + 'K' : '$' + v } },
                x: { grid: { display: false },
                     ticks: { color: 'rgba(245,245,245,.4)', font: { family: 'Google Sans Flex', size: 11 } } }
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
