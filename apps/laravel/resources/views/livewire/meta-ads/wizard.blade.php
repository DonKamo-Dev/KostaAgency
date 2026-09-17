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
        color: var(--text-secondary); font-family: inherit; font-size: 13px; font-weight: 500;
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
    .client-selector { margin-bottom: 20px; }
    .client-selector-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700; margin-bottom: 8px; }
    .client-select {
        width: 100%; padding: 10px 14px; border-radius: 10px;
        border: 1px solid var(--border-subtle);
        background: rgba(255,255,255,0.03);
        color: var(--text-secondary); font-size: 13px;
        cursor: pointer; outline: none;
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23888' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 36px;
        transition: border-color 0.2s ease;
    }
    .client-select:hover, .client-select:focus { border-color: rgba(230,57,70,0.4); }
    .client-select option { background: #1a1a1a; color: #ccc; }
    .other-input { margin-top: 10px; animation: fadeIn 0.2s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }
</style>

{{-- HEADER --}}
<div class="crud-header">
    <div>
        <h1 class="crud-title">IA Campañas Digitales</h1>
        <p class="crud-subtitle">Genera cotizaciones de pauta digital con inteligencia artificial</p>
    </div>
    <a wire:navigate href="{{ route('meta-ads.history') }}" class="btn btn-secondary">
        <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Ver historial
    </a>
</div>

{{-- STATE 1: GENERATING --}}
@if($generating)
    <div class="wizard-card" wire:poll.3s="checkGeneration" role="status" aria-live="polite">
        <div class="generating-overlay">
            <div class="spinner"></div>
            <p style="color:var(--text-secondary);font-size:15px;">Claude está diseñando tu estrategia...</p>
            <p style="color:var(--text-muted);font-size:12px;">Esto puede tomar 15–25 segundos</p>
        </div>
    </div>

{{-- STATE 2: RESULT --}}
@elseif($result)
    <div>
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
            <div style="display:flex;gap:10px;flex-shrink:0;flex-wrap:wrap;">
                @if($quoteId)
                <button wire:click="saveQuote" wire:loading.attr="disabled" wire:target="saveQuote" class="btn btn-secondary" style="display:inline-flex;align-items:center;gap:6px;">
                    <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span wire:loading wire:target="saveQuote" style="font-size:11px;">Guardando...</span>
                    <span wire:loading.remove wire:target="saveQuote">Guardar cambios</span>
                </button>
                <a href="{{ route('meta-ads.pdf', $quoteId) }}" target="_blank" class="btn btn-secondary">
                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Exportar PDF
                </a>
                @endif
                <button wire:click="resetWizard" class="btn btn-primary">Nueva cotización</button>
            </div>
        </div>

        <div class="result-card" style="margin-bottom:16px;">
            <div class="result-section-label">Resumen ejecutivo</div>
            <p style="color:var(--text-secondary);font-size:14px;line-height:1.65;margin:0;">{{ $result['resumen_ejecutivo'] }}</p>
        </div>

        <div class="result-grid">
            <div class="result-card">
                <div class="result-section-label">Estructura de campaña</div>
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:10px;">
                    Objetivo: <strong style="color:var(--text-primary);">{{ $result['estructura_campana']['objetivo'] }}</strong>
                </div>
                @foreach($result['estructura_campana']['ad_sets'] as $adset)
                    <div class="adset-row">
                        <div style="font-size:13px;font-weight:600;color:var(--text-primary);margin-bottom:4px;">{{ $adset['nombre'] }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $adset['audiencia_descripcion'] }}</div>
                        <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">Placements: {{ implode(', ', $adset['placements']) }}</div>
                        <div style="font-size:12px;color:var(--red-primary);font-weight:600;margin-top:4px;">
                            ${{ number_format($adset['presupuesto_diario_cop'],0,',','.') }}/día · {{ $adset['duracion_dias'] }} días
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="result-card">
                <div class="result-section-label">Creativos</div>
                <div style="margin-bottom:10px;">
                    @foreach($result['creativos']['formatos'] as $fmt)
                        <span class="metric-chip">{{ $fmt }}</span>
                    @endforeach
                </div>
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:8px;">CTA: <strong style="color:var(--text-primary);">{{ $result['creativos']['call_to_action'] }}</strong></div>
                @foreach($result['creativos']['copies_sugeridos'] as $copy)
                    <div style="background:rgba(255,255,255,0.03);border-radius:6px;padding:8px 10px;margin-bottom:6px;font-size:12px;color:var(--text-secondary);">"{{ $copy }}"</div>
                @endforeach
                <p style="font-size:11px;color:var(--text-muted);margin-top:8px;">{{ $result['creativos']['recomendaciones'] }}</p>
            </div>
        </div>

        <div class="result-grid" style="margin-bottom:16px;">
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

            <div class="result-card">
                <div class="result-section-label">Fases de la campaña</div>
                @foreach($result['fases'] as $fase)
                    <div class="phase-bar">
                        <div style="width:52px;font-size:11px;font-weight:700;color:var(--red-primary);">{{ $fase['fase'] }}</div>
                        <div class="phase-track"><div class="phase-fill" style="width:{{ $fase['presupuesto_pct'] }}%;"></div></div>
                        <div style="font-size:11px;color:var(--text-muted);white-space:nowrap;">{{ $fase['presupuesto_pct'] }}% · {{ $fase['dias'] }}d</div>
                    </div>
                    <div style="font-size:11px;color:var(--text-muted);margin-bottom:10px;padding-left:62px;">{{ $fase['objetivo'] }}</div>
                @endforeach
            </div>
        </div>

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

        {{-- ── ESTRUCTURA DE PROPUESTA ─────────────────────────────────────── --}}
        @if(!empty($result['proposal_structure']))
        @php $ps = $result['proposal_structure']; @endphp
        <div class="result-card" style="margin-bottom:16px;">
            <div class="result-section-label" style="display:flex;align-items:center;justify-content:space-between;">
                <span>Estructura de propuesta</span>
                <span style="font-size:10px;color:var(--text-muted);font-weight:400;text-transform:none;letter-spacing:0;">Edita y guarda cambios para actualizar el PDF</span>
            </div>

            {{-- Tabla de campañas --}}
            <div style="margin-bottom:16px;">
                <div style="display:grid;grid-template-columns:1fr 130px 80px 80px 32px;gap:6px;margin-bottom:6px;padding:0 4px;">
                    <span style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.08em;">Campaña</span>
                    <span style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.08em;text-align:right;">Presupuesto</span>
                    <span style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.08em;text-align:center;">Camp.</span>
                    <span style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.08em;text-align:center;">Anuncios</span>
                    <span></span>
                </div>
                @foreach($ps['campaigns'] as $ci => $camp)
                <div style="display:grid;grid-template-columns:1fr 130px 80px 80px 32px;gap:6px;align-items:center;margin-bottom:5px;">
                    <input wire:model.blur="result.proposal_structure.campaigns.{{ $ci }}.name"
                           type="text" class="form-input" style="padding:6px 10px;font-size:13px;"
                           placeholder="Nombre de la campaña" value="{{ $camp['name'] }}" />
                    <div style="position:relative;">
                        <span style="position:absolute;left:8px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:12px;">$</span>
                        <input wire:model.blur="result.proposal_structure.campaigns.{{ $ci }}.budget"
                               type="number" class="form-input" style="padding:6px 8px 6px 20px;font-size:13px;text-align:right;"
                               value="{{ $camp['budget'] }}" min="0" step="1000" />
                    </div>
                    <input wire:model.blur="result.proposal_structure.campaigns.{{ $ci }}.campaigns_count"
                           type="number" class="form-input" style="padding:6px 8px;font-size:13px;text-align:center;"
                           value="{{ $camp['campaigns_count'] }}" min="1" />
                    <input wire:model.blur="result.proposal_structure.campaigns.{{ $ci }}.ads_count"
                           type="number" class="form-input" style="padding:6px 8px;font-size:13px;text-align:center;"
                           value="{{ $camp['ads_count'] }}" min="1" />
                    <button wire:click="removeProposalCampaign({{ $ci }})" type="button"
                            style="background:none;border:none;color:var(--text-muted);cursor:pointer;padding:4px;border-radius:4px;line-height:1;"
                            title="Eliminar fila">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                @endforeach
                <button wire:click="addProposalCampaign" type="button"
                        style="margin-top:6px;background:none;border:1px dashed var(--border-subtle);color:var(--text-muted);cursor:pointer;padding:5px 14px;border-radius:8px;font-size:12px;width:100%;">
                    + Agregar campaña
                </button>
            </div>

            {{-- Beneficios --}}
            <div style="border-top:1px solid var(--border-subtle);padding-top:14px;">
                <div style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:8px;">Beneficios / Puntos clave</div>
                @foreach($ps['benefits'] as $bi => $benefit)
                <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:6px;">
                    <span style="color:var(--red-primary);font-size:13px;line-height:2;flex-shrink:0;">{{ $bi + 1 }}.</span>
                    <input wire:model.blur="result.proposal_structure.benefits.{{ $bi }}"
                           type="text" class="form-input" style="padding:6px 10px;font-size:13px;flex:1;"
                           value="{{ $benefit }}" placeholder="Escribe un punto clave..." />
                    <button wire:click="removeProposalBenefit({{ $bi }})" type="button"
                            style="background:none;border:none;color:var(--text-muted);cursor:pointer;padding:4px;flex-shrink:0;margin-top:2px;">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                @endforeach
                <button wire:click="addProposalBenefit" type="button"
                        style="margin-top:4px;background:none;border:1px dashed var(--border-subtle);color:var(--text-muted);cursor:pointer;padding:5px 14px;border-radius:8px;font-size:12px;width:100%;">
                    + Agregar punto
                </button>
            </div>
        </div>
        @endif

        {{-- ── DESGLOSE FINANCIERO ──────────────────────────────────────────── --}}
        <div class="result-card">
            <div class="result-section-label" style="display:flex;align-items:center;gap:8px;">
                Desglose financiero
                <span style="font-size:10px;color:var(--text-muted);font-weight:400;text-transform:none;letter-spacing:0;">— edita los valores y los totales se recalculan</span>
            </div>
            @php $p = $result['desglose_precios']; @endphp
            <style>
                .price-input {
                    background: transparent; border: none; border-bottom: 1px dashed rgba(255,255,255,0.12);
                    color: var(--text-secondary); font-size: 14px; text-align: right;
                    width: 140px; padding: 2px 4px; outline: none;
                    transition: border-color 0.2s ease;
                }
                .price-input:hover, .price-input:focus {
                    border-bottom-color: var(--red-primary); color: var(--text-primary);
                }
            </style>
            <div class="pricing-table">
                <div class="pricing-row">
                    <span>Presupuesto en pauta (Meta)</span>
                    <div style="display:flex;align-items:center;gap:4px;">
                        <span style="color:var(--text-muted);">$</span>
                        <input type="number" wire:model.blur="result.desglose_precios.presupuesto_pauta_cop"
                               class="price-input" value="{{ $p['presupuesto_pauta_cop'] }}" min="0" step="1000" />
                        <span style="color:var(--text-muted);">COP</span>
                    </div>
                </div>
                <div class="pricing-row">
                    <span>Honorarios gestión de campaña</span>
                    <div style="display:flex;align-items:center;gap:4px;">
                        <span style="color:var(--text-muted);">$</span>
                        <input type="number" wire:model.blur="result.desglose_precios.honorarios_gestion_cop"
                               class="price-input" value="{{ $p['honorarios_gestion_cop'] }}" min="0" step="1000" />
                        <span style="color:var(--text-muted);">COP</span>
                    </div>
                </div>
                <div class="pricing-row">
                    <span>Honorarios creativos</span>
                    <div style="display:flex;align-items:center;gap:4px;">
                        <span style="color:var(--text-muted);">$</span>
                        <input type="number" wire:model.blur="result.desglose_precios.honorarios_creativos_cop"
                               class="price-input" value="{{ $p['honorarios_creativos_cop'] }}" min="0" step="1000" />
                        <span style="color:var(--text-muted);">COP</span>
                    </div>
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

{{-- STATE 3: WIZARD STEPS --}}
@else
    <div class="wizard-shell">
        <aside class="wizard-sidebar">
            <div style="font-size:10px;color:var(--text-subtle);text-transform:uppercase;letter-spacing:0.15em;font-weight:700;margin-bottom:12px;padding:0 4px;">Pasos</div>
            @php $steps = ['Cliente', 'Presupuesto', 'Audiencia', 'Campaña', 'Destino']; @endphp
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

        <div class="wizard-card">
            @if($step === 1)
                <div class="wizard-step-label">Paso 1 de 5</div>
                <h2 class="wizard-title">¿Para quién es la campaña?</h2>
                <p class="wizard-subtitle">Ingresa el nombre del cliente, sector y plataforma de pauta.</p>

                {{-- Selector de plataforma --}}
                <div class="form-group">
                    <label class="form-label">Plataforma de pauta <span class="required">*</span></label>
                    <div class="chip-grid">
                        @foreach([
                            ['value'=>'META',   'label'=>'Meta Ads',         'icon'=>'📘'],
                            ['value'=>'GOOGLE', 'label'=>'Google Ads',       'icon'=>'🔍'],
                            ['value'=>'BOTH',   'label'=>'Google + Meta',    'icon'=>'⚡'],
                        ] as $opt)
                            <button type="button" wire:click="setPlatform('{{ $opt['value'] }}')"
                                  class="chip {{ $platform === $opt['value'] ? 'selected' : '' }}"
                                  style="padding:10px 20px;font-size:13px;">
                                {{ $opt['icon'] }} {{ $opt['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Selector de clientes existentes --}}
                @if(count($clients) > 0)
                    <div class="client-selector">
                        <div class="client-selector-label">Seleccionar cliente existente</div>
                        <select wire:model.live="selectedClientId" class="client-select">
                            <option value="0">— Elegir de tus clientes —</option>
                            @foreach($clients as $client)
                                <option value="{{ $client['id'] }}">{{ $client['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Nombre del cliente <span class="required">*</span></label>
                    <input wire:model="clientName" type="text" class="form-input" placeholder="Ej: Tienda Moda Bella" />
                    @error('clientName') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Industria / Sector</label>
                    <div class="chip-grid">
                        @foreach(['Restaurante','Moda','Salud','Inmobiliaria','Educación','Tecnología','Servicios','Otro'] as $opt)
                            <button type="button" wire:click="setIndustry('{{ $opt }}')" class="chip {{ $industry === $opt ? 'selected' : '' }}">{{ $opt }}</button>
                        @endforeach
                    </div>
                    @if($industry === 'Otro')
                        <div class="other-input">
                            <input wire:model.live="industryOther" type="text" class="form-input" placeholder="¿Cuál es el sector? Ej: Logística, Turismo..." />
                        </div>
                    @endif
                </div>
                <div class="wizard-nav">
                    <div></div>
                    <button wire:click="nextStep" class="btn btn-primary">Siguiente →</button>
                </div>

            @elseif($step === 2)
                <div class="wizard-step-label">Paso 2 de 5</div>
                <h2 class="wizard-title">¿Cuál es el presupuesto?</h2>
                <p class="wizard-subtitle">Mínimo $100.000 COP por 15 días.</p>
                <div class="form-group">
                    <label class="form-label">Presupuesto total en COP <span class="required">*</span></label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-weight:600;z-index:1;">$</span>
                        <input wire:model.live="budgetCop" type="number" min="100000" step="10000"
                               class="form-input" style="padding-left:26px;" placeholder="300000" />
                    </div>
                    @error('budgetCop') <span class="form-error">{{ $message }}</span> @enderror
                    @if($budgetCop >= 100000 && $durationDays > 0)
                        <p class="daily-calc">
                            <span style="color:var(--text-muted);">$</span><span>{{ number_format($budgetCop, 0, ',', '.') }} COP</span>
                            &nbsp;·&nbsp; equivale a <span>${{ number_format(intval($budgetCop / $durationDays), 0, ',', '.') }} COP/día</span>
                        </p>
                    @endif
                </div>
                <div class="form-group">
                    <label class="form-label">Duración <span class="required">*</span></label>
                    <div class="chip-grid">
                        @foreach([15,30,45,60] as $days)
                            <button type="button" wire:click="setDurationDays({{ $days }})" class="chip {{ $durationDays === $days ? 'selected' : '' }}">{{ $days }} días</button>
                        @endforeach
                    </div>
                    @error('durationDays') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <div class="wizard-nav">
                    <button wire:click="prevStep" wire:loading.attr="disabled" wire:target="prevStep" class="btn btn-secondary">← Anterior</button>
                    <button wire:click="nextStep" class="btn btn-primary">Siguiente →</button>
                </div>

            @elseif($step === 3)
                <div class="wizard-step-label">Paso 3 de 5</div>
                <h2 class="wizard-title">¿A quién va dirigida?</h2>
                <p class="wizard-subtitle">Define la audiencia objetivo para Meta Ads.</p>
                <div class="form-group">
                    <label class="form-label">Rango de edad <span class="required">*</span></label>
                    <div class="chip-grid">
                        @foreach(['18–24','25–34','35–44','45+'] as $range)
                            <button type="button" wire:click="setAgeRange('{{ $range }}')" class="chip {{ $ageRange === $range ? 'selected' : '' }}">{{ $range }}</button>
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
                    <label class="form-label">Intereses <span class="required">*</span></label>
                    <div class="chip-grid">
                        @foreach(['Emprendimiento','Moda','Salud','Tecnología','Hogar','Gastronomía','Deportes','Viajes','Otro'] as $opt)
                            <button type="button" wire:click="toggleInterest('{{ $opt }}')"
                                    class="chip {{ in_array($opt, $interests) ? 'selected' : '' }}">{{ $opt }}</button>
                        @endforeach
                    </div>
                    @if(in_array('Otro', $interests))
                        <div class="other-input">
                            <input wire:model.live="interestOther" type="text" class="form-input" placeholder="¿Qué otro interés? Ej: Fotografía, Fitness..." />
                        </div>
                    @endif
                    @error('interests') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <div class="wizard-nav">
                    <button wire:click="prevStep" wire:loading.attr="disabled" wire:target="prevStep" class="btn btn-secondary">← Anterior</button>
                    <button wire:click="nextStep" class="btn btn-primary">Siguiente →</button>
                </div>

            @elseif($step === 4)
                <div class="wizard-step-label">Paso 4 de 5</div>
                <h2 class="wizard-title">¿Cuál es el objetivo?</h2>
                <p class="wizard-subtitle">El objetivo define la estructura y optimización en Meta.</p>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    @foreach([
                        ['value'=>'AWARENESS',   'label'=>'Reconocimiento de marca', 'desc'=>'Llegar al mayor número de personas posible'],
                        ['value'=>'TRAFFIC',     'label'=>'Tráfico web',             'desc'=>'Dirigir personas a tu sitio web o landing'],
                        ['value'=>'LEADS',       'label'=>'Generación de leads',     'desc'=>'Capturar contactos de posibles clientes'],
                        ['value'=>'CONVERSIONS', 'label'=>'Conversión / Ventas',     'desc'=>'Ventas directas o acciones de alto valor'],
                        ['value'=>'ENGAGEMENT',  'label'=>'Interacción',             'desc'=>'Aumentar likes, comentarios y compartidos'],
                    ] as $opt)
                        <label style="display:block;cursor:pointer;margin:0;">
                            <input type="radio" wire:model.live="campaignType" value="{{ $opt['value'] }}" style="position:absolute;opacity:0;pointer-events:none;">
                            <div class="chip {{ $campaignType === $opt['value'] ? 'selected' : '' }}"
                                 style="border-radius:10px;text-align:left;padding:12px 16px;">
                                <div style="font-weight:600;">{{ $opt['label'] }}</div>
                                <div style="font-size:12px;opacity:0.7;margin-top:2px;">{{ $opt['desc'] }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('campaignType') <span class="form-error" style="margin-top:8px;display:block;">{{ $message }}</span> @enderror
                <div class="wizard-nav">
                    <button wire:click="prevStep" wire:loading.attr="disabled" wire:target="prevStep" class="btn btn-secondary">← Anterior</button>
                    <button wire:click="nextStep" class="btn btn-primary">Siguiente →</button>
                </div>

            @elseif($step === 5)
                <div class="wizard-step-label">Paso 5 de 5</div>
                <h2 class="wizard-title">¿Hacia dónde va el tráfico?</h2>
                <p class="wizard-subtitle">Define si los anuncios van a WhatsApp, web o ambos.</p>
                <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:24px;">
                    @foreach([
                        ['value'=>'WEB',      'label'=>'Solo Sitio Web',  'desc'=>'El tráfico va a una URL o landing page'],
                        ['value'=>'WHATSAPP', 'label'=>'Solo WhatsApp',   'desc'=>'Botón directo a conversación de WhatsApp'],
                        ['value'=>'BOTH',     'label'=>'Web + WhatsApp',  'desc'=>'Combinación de ambos destinos'],
                    ] as $opt)
                        <label style="display:block;cursor:pointer;margin:0;">
                            <input type="radio" wire:model.live="destination" value="{{ $opt['value'] }}" style="position:absolute;opacity:0;pointer-events:none;">
                            <div class="chip {{ $destination === $opt['value'] ? 'selected' : '' }}"
                                 style="border-radius:10px;text-align:left;padding:12px 16px;">
                                <div style="font-weight:600;">{{ $opt['label'] }}</div>
                                <div style="font-size:12px;opacity:0.7;margin-top:2px;">{{ $opt['desc'] }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('destination') <span class="form-error">{{ $message }}</span> @enderror
                @if(in_array($destination, ['WHATSAPP','BOTH']))
                    <div class="form-group">
                        <label class="form-label">Número WhatsApp</label>
                        {{-- .blur para evitar round-trips por cada tecla que deshabilitarían el botón Generar --}}
                        <input wire:model.blur="whatsappNumber" type="text" class="form-input" placeholder="+573001234567" />
                    </div>
                @endif
                @if($generateError)
                    <div style="background:rgba(230,57,70,0.08);border:1px solid rgba(230,57,70,0.3);border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#f87171;word-break:break-word;">
                        {{ $generateError }}
                    </div>
                @endif
                <div class="wizard-nav">
                    <button wire:click="prevStep" wire:loading.attr="disabled" wire:target="prevStep" class="btn btn-secondary">← Anterior</button>
                    <button wire:click="generate" wire:loading.attr="disabled" wire:target="generate" class="btn btn-primary" style="gap:10px;display:inline-flex;align-items:center;">
                        <span wire:loading.remove wire:target="generate">✦ Generar estrategia con IA</span>
                        <span wire:loading wire:target="generate" style="display:inline-flex;align-items:center;gap:8px;">
                            <thinking-orb state="solving" size="18"></thinking-orb>
                            <span>Generando con IA...</span>
                        </span>
                    </button>
                </div>
            @endif
        </div>
    </div>
@endif

    {{-- Thinking Orb AI Loading Fullscreen Overlay --}}
    <div wire:loading.flex wire:target="generate" style="display:none;position:fixed;inset:0;background:rgba(10,10,10,0.88);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);z-index:9999;align-items:center;justify-content:center;flex-direction:column;gap:20px;">
        <div style="background:rgba(20,20,20,0.92);border:1px solid rgba(230,57,70,0.35);border-radius:24px;padding:36px 44px;display:flex;flex-direction:column;align-items:center;gap:20px;box-shadow:0 24px 64px rgba(0,0,0,0.85),inset 0 0 50px rgba(230,57,70,0.12);max-width:460px;text-align:center;">
            <thinking-orb state="solving" size="64" large></thinking-orb>
            <div>
                <div style="font-family:'Space Grotesk',sans-serif;font-size:20px;font-weight:700;color:var(--text-primary);margin-bottom:6px;">Generando Estrategia IA</div>
                <div class="t-shimmer" data-text="Analizando audiencia, redactando copys y estructurando presupuesto..." style="font-size:13px;line-height:1.5;">Analizando audiencia, redactando copys y estructurando presupuesto...</div>
            </div>
        </div>
    </div>
</div>
