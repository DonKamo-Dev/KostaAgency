<div>
    @php
        $catNames = [
            'web' => 'Páginas Web',
            'ecommerce' => 'E-commerce',
            'sistema' => 'Sistema',
            'branding' => 'Branding',
            'films' => 'Films & Reels',
        ];
        $catColors = [
            'web'       => 'background:rgba(99,102,241,0.15);color:#818cf8;border-color:rgba(99,102,241,0.3);',
            'ecommerce' => 'background:rgba(245,158,11,0.15);color:#fbbf24;border-color:rgba(245,158,11,0.3);',
            'sistema'   => 'background:rgba(14,165,233,0.15);color:#38bdf8;border-color:rgba(14,165,233,0.3);',
            'branding'  => 'background:rgba(16,185,129,0.15);color:#34d399;border-color:rgba(16,185,129,0.3);',
            'films'     => 'background:rgba(244,63,94,0.15);color:#fb7185;border-color:rgba(244,63,94,0.3);',
        ];
        $catOptions = [
            'web' => [
                'label' => 'Página Web',
                'sub'   => 'Sitios web, landing pages y portales',
                'dot'   => '#818cf8',
            ],
            'ecommerce' => [
                'label' => 'E-commerce',
                'sub'   => 'Tiendas virtuales, pasarelas y catálogos',
                'dot'   => '#fbbf24',
            ],
            'sistema' => [
                'label' => 'Sistema',
                'sub'   => 'Software a medida, SaaS y paneles de control',
                'dot'   => '#38bdf8',
            ],
            'branding' => [
                'label' => 'Branding',
                'sub'   => 'Identidad visual, diseño y logotipos',
                'dot'   => '#34d399',
            ],
            'films' => [
                'label' => 'Films & Reels',
                'sub'   => 'Producción audiovisual, reels y video publicitario',
                'dot'   => '#f43f5e',
            ],
        ];
    @endphp

    <!-- Header -->
    <div class="crud-header" style="margin-bottom:28px;">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                <a href="{{ route('case-studies.index') }}" wire:navigate style="color:var(--text-muted);text-decoration:none;font-size:13px;display:inline-flex;align-items:center;gap:4px;transition:color .2s;">
                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Casos de Estudio
                </a>
                <span style="color:var(--text-subtle);font-size:12px;">/</span>
                <span style="color:var(--text-secondary);font-size:13px;font-weight:600;">{{ $editingId ? 'Editar' : 'Crear' }}</span>
            </div>
            <h1 class="crud-title">{{ $editingId ? 'Editar Caso de Estudio' : 'Nuevo Caso de Estudio' }}</h1>
            <p class="crud-subtitle">
                {{ $editingId ? 'Actualiza los datos del proyecto y visualiza el resultado en tiempo real.' : 'Completa los detalles para publicar un nuevo proyecto en tu portafolio público.' }}
            </p>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <a href="{{ route('case-studies.index') }}" wire:navigate class="btn btn-secondary">
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
            <a href="{{ route('portfolio') }}" target="_blank" rel="noopener" class="btn btn-secondary" title="Ver portafolio público">
                <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Ver portafolio
            </a>
        </div>
    </div>

    <!-- Main Grid: Form (Left) + Live Preview (Right) -->
    <div style="display:grid;grid-template-columns:1fr;gap:32px;align-items:start;" class="case-study-grid">
        <style>
            @media (min-width: 1100px) {
                .case-study-grid {
                    grid-template-columns: minmax(0, 1.45fr) minmax(360px, 1fr) !important;
                }
            }
            .form-card-section {
                background: #141414;
                border: 1px solid var(--border-default);
                border-radius: 18px;
                padding: 26px 28px;
                margin-bottom: 24px;
                box-shadow: 0 4px 24px rgba(0,0,0,0.25);
            }
            .section-badge-title {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .12em;
                color: var(--accent-red);
                margin-bottom: 18px;
            }
            .section-badge-title svg {
                width: 15px;
                height: 15px;
            }
            .preview-sticky-wrap {
                position: sticky;
                top: 24px;
            }
            .mockup-preview-card {
                background: rgba(255,255,255,0.02);
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 20px 50px rgba(0,0,0,0.5);
                transition: all .3s ease;
            }

            /* ── Smartphone Reel Mockup ── */
            .smartphone-mockup-frame {
                position: relative;
                max-width: 275px;
                margin: 0 auto;
                background: #09090b;
                border-radius: 42px;
                padding: 10px;
                border: 3.5px solid #27272a;
                box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.1), 0 25px 65px -10px rgba(0, 0, 0, 0.95), 0 0 40px rgba(244, 63, 94, 0.15);
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .smartphone-btn-vol-up {
                position: absolute;
                left: -7px;
                top: 85px;
                width: 4px;
                height: 36px;
                background: #3f3f46;
                border-radius: 3px 0 0 3px;
            }
            .smartphone-btn-vol-down {
                position: absolute;
                left: -7px;
                top: 130px;
                width: 4px;
                height: 36px;
                background: #3f3f46;
                border-radius: 3px 0 0 3px;
            }
            .smartphone-btn-power {
                position: absolute;
                right: -7px;
                top: 105px;
                width: 4px;
                height: 50px;
                background: #3f3f46;
                border-radius: 0 3px 3px 0;
            }
            .smartphone-screen {
                position: relative;
                width: 100%;
                aspect-ratio: 9 / 16;
                height: 480px;
                border-radius: 34px;
                overflow: hidden;
                background: #000;
                display: flex;
                flex-direction: column;
            }
            .smartphone-island {
                position: absolute;
                top: 8px;
                left: 50%;
                transform: translateX(-50%);
                width: 78px;
                height: 18px;
                background: #000;
                border-radius: 20px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                z-index: 30;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 7px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.6);
            }
            .smartphone-island-cam {
                width: 7px;
                height: 7px;
                border-radius: 50%;
                background: #111;
                border: 1.5px solid #1e1e24;
            }
            .smartphone-island-sensor {
                width: 5px;
                height: 5px;
                border-radius: 50%;
                background: #080811;
            }
            .smartphone-status-bar {
                position: absolute;
                top: 8px;
                left: 18px;
                right: 18px;
                height: 18px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                z-index: 25;
                pointer-events: none;
                font-size: 11px;
                font-weight: 700;
                color: #ffffff;
                text-shadow: 0 1px 4px rgba(0,0,0,0.9);
            }
            .smartphone-video-stage {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                background: #000;
            }
            .smartphone-video-el {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .smartphone-reel-overlay {
                position: absolute;
                inset: 0;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 34px 14px 14px;
                z-index: 20;
                pointer-events: none;
                background: linear-gradient(180deg, rgba(0,0,0,0.55) 0%, transparent 22%, transparent 55%, rgba(0,0,0,0.92) 100%);
            }
            .smartphone-actions-col {
                position: absolute;
                right: 8px;
                bottom: 55px;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 13px;
                z-index: 22;
            }
            .smartphone-action-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 2px;
                color: #ffffff;
                filter: drop-shadow(0 2px 4px rgba(0,0,0,0.85));
            }
            .smartphone-action-item svg {
                width: 20px;
                height: 20px;
            }
            .smartphone-action-item span {
                font-size: 9.5px;
                font-weight: 700;
            }
            .smartphone-sound-disc {
                width: 26px;
                height: 26px;
                border-radius: 50%;
                background: conic-gradient(from 0deg, #18181b, #3f3f46, #18181b);
                border: 2px solid rgba(255,255,255,0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                animation: spinPhoneDisc 3s linear infinite;
                box-shadow: 0 2px 8px rgba(0,0,0,0.8);
            }
            @keyframes spinPhoneDisc {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .smartphone-bottom-meta {
                margin-top: auto;
                max-width: 82%;
                text-align: left;
            }
            .smartphone-user-badge {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                margin-bottom: 5px;
                font-size: 11px;
                font-weight: 700;
                color: #ffffff;
                text-shadow: 0 1px 4px rgba(0,0,0,0.9);
            }
            .smartphone-reel-title {
                font-family: 'Syne', sans-serif;
                font-size: 14px;
                font-weight: 700;
                color: #ffffff;
                margin: 0 0 3px;
                line-height: 1.25;
                text-shadow: 0 2px 6px rgba(0,0,0,0.95);
            }
            .smartphone-reel-desc {
                font-size: 11px;
                color: rgba(255, 255, 255, 0.88);
                line-height: 1.35;
                margin: 0 0 7px;
                text-shadow: 0 1px 4px rgba(0,0,0,0.9);
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .smartphone-audio-row {
                display: flex;
                align-items: center;
                gap: 5px;
                font-size: 9.5px;
                color: rgba(255,255,255,0.8);
            }
            .smartphone-home-bar {
                position: absolute;
                bottom: 6px;
                left: 50%;
                transform: translateX(-50%);
                width: 80px;
                height: 3.5px;
                background: rgba(255, 255, 255, 0.7);
                border-radius: 3px;
                z-index: 25;
            }
        </style>

        <!-- FORM COLUMN -->
        <div>
            <form wire:submit="save">

                <!-- 1. Información Básica -->
                <div class="form-card-section">
                    <div class="section-badge-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Información Básica
                    </div>

                    <div class="form-group">
                        <label class="form-label">Título del proyecto <span class="required">*</span></label>
                        <input type="text" wire:model.live.debounce.250ms="titulo" class="form-input" placeholder="Ej: NovaVet Clinic"/>
                        @error('titulo') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción del caso</label>
                        <textarea wire:model.live.debounce.250ms="descripcion" rows="3" class="form-textarea" placeholder="Describe brevemente el reto, la solución entregada y el impacto generado..."></textarea>
                        @error('descripcion') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">URL del sitio o demo</label>
                            <input type="text" wire:model.live.debounce.250ms="url_demo" class="form-input" placeholder="ejemplo.com"/>
                            <span style="font-size:11px;color:var(--text-subtle);margin-top:4px;display:block;">Se mostrará en la barra simulada de navegador</span>
                            @error('url_demo') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group" style="margin-bottom:0;" x-data="{
                            open: false,
                            selected: @entangle('categoria').live,
                            options: {{ json_encode($catOptions) }},
                            get current() {
                                return this.options[this.selected] || this.options['web'] || { label: 'Seleccionar...', dot: '#818cf8', sub: '' };
                            }
                        }" @click.outside="open = false" @keydown.escape.window="open = false">
                            <label class="form-label">Categoría <span class="required">*</span></label>
                            
                            <div style="position:relative;">
                                <!-- Trigger Button -->
                                <button type="button"
                                        @click="open = !open"
                                        class="form-input custom-select-trigger"
                                        :class="{ 'is-open': open }"
                                        style="width:100%;height:44px;display:flex;align-items:center;justify-content:space-between;cursor:pointer;padding:0 14px;border-radius:12px;text-align:left;background:var(--bg-input);user-select:none;box-sizing:border-box;">
                                    <div style="display:flex;align-items:center;gap:10px;min-width:0;">
                                        <span style="width:8px;height:8px;border-radius:50%;flex-shrink:0;transition:background 0.2s;"
                                              :style="'background:' + current.dot + ';box-shadow:0 0 10px ' + current.dot + '90;'"></span>
                                        <span x-text="current.label" style="font-size:13.5px;font-weight:600;color:#FAFAFA;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></span>
                                    </div>
                                    <svg class="custom-select-arrow"
                                         :class="{ 'is-open': open }"
                                         width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <!-- Dropdown Menu Panel -->
                                <div x-show="open"
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                                     style="position:absolute;top:calc(100% + 6px);left:0;right:0;z-index:100;background:#141416;border:1px solid rgba(255,255,255,0.12);border-radius:14px;padding:6px;box-shadow:0 20px 45px rgba(0,0,0,0.85),0 0 0 1px rgba(255,255,255,0.06);backdrop-filter:blur(24px);">
                                    @foreach($catOptions as $key => $opt)
                                        <button type="button"
                                                @click="selected = '{{ $key }}'; $wire.set('categoria', '{{ $key }}'); open = false;"
                                                class="custom-select-opt"
                                                :class="selected === '{{ $key }}' ? 'is-active' : ''"
                                                style="width:100%;display:flex;align-items:center;justify-content:space-between;padding:9px 12px;border-radius:10px;border:none;background:transparent;cursor:pointer;text-align:left;margin-bottom:2px;transition:all .15s ease;">
                                            <div style="display:flex;align-items:center;gap:10px;">
                                                <span style="width:8px;height:8px;border-radius:50%;flex-shrink:0;background:{{ $opt['dot'] }};box-shadow:0 0 8px {{ $opt['dot'] }}80;"></span>
                                                <div>
                                                    <div style="font-size:13.5px;font-weight:600;color:{{ $opt['dot'] }};line-height:1.2;">
                                                        {{ $opt['label'] }}
                                                    </div>
                                                    <div style="font-size:11px;color:rgba(255,255,255,0.45);margin-top:2px;">
                                                        {{ $opt['sub'] }}
                                                    </div>
                                                </div>
                                            </div>
                                            <template x-if="selected === '{{ $key }}'">
                                                <svg style="width:15px;height:15px;color:#FFFFFF;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </template>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            @error('categoria') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if($categoria === 'films')
                    <!-- Card de anclaje para caso de Films -->
                    <div style="margin-top:20px;padding:18px 20px;background:rgba(244,63,94,0.06);border:1px solid rgba(244,63,94,0.25);border-radius:14px;animation:fadeIn 0.25s ease;">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:10px;">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:6px;background:rgba(244,63,94,0.18);color:#f43f5e;">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                </span>
                                <div>
                                    <h4 style="font-size:13px;font-weight:700;color:#FAFAFA;margin:0;">Anclar Caso de Films</h4>
                                    <p style="font-size:11px;color:rgba(255,255,255,0.5);margin:2px 0 0 0;">Vincula este reel a una empresa registrada o a un sitio web de la plataforma</p>
                                </div>
                            </div>
                            
                            <!-- Selector: Empresa vs Sitio Web -->
                            <div style="display:inline-flex;background:rgba(0,0,0,0.4);border:1px solid rgba(255,255,255,0.1);border-radius:10px;padding:3px;">
                                <button type="button" 
                                        wire:click="$set('source_type', 'client')" 
                                        style="padding:6px 14px;border-radius:8px;border:none;font-size:12px;font-weight:700;cursor:pointer;transition:all .15s ease;display:flex;align-items:center;gap:6px; {{ $source_type === 'client' ? 'background:#f43f5e;color:#FFFFFF;box-shadow:0 2px 8px rgba(244,63,94,0.4);' : 'background:transparent;color:rgba(255,255,255,0.6);' }}">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    Empresa (Cliente)
                                </button>
                                <button type="button" 
                                        wire:click="$set('source_type', 'website')" 
                                        style="padding:6px 14px;border-radius:8px;border:none;font-size:12px;font-weight:700;cursor:pointer;transition:all .15s ease;display:flex;align-items:center;gap:6px; {{ $source_type === 'website' ? 'background:#f43f5e;color:#FFFFFF;box-shadow:0 2px 8px rgba(244,63,94,0.4);' : 'background:transparent;color:rgba(255,255,255,0.6);' }}">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                    Sitio Web
                                </button>
                            </div>
                        </div>

                        @if($source_type === 'client')
                            <div style="margin-top:12px;">
                                <label class="form-label" style="font-size:12px;color:rgba(255,255,255,0.85);margin-bottom:6px;">Empresa registrada (con NIT)</label>
                                <select wire:model.live="client_id" class="form-input" style="width:100%;height:44px;background:#18181b;color:#FAFAFA;border-radius:10px;padding:0 12px;font-size:13px;border-color:rgba(244,63,94,0.3);">
                                    <option value="">-- Seleccionar empresa registrada --</option>
                                    @foreach($clients as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }} {{ $c->tax_id ? '(NIT: ' . $c->tax_id . ')' : '' }}</option>
                                    @endforeach
                                </select>
                                @if($clients->isEmpty())
                                    <span style="font-size:11px;color:#fca5a5;margin-top:4px;display:block;">No hay empresas registradas aún en el módulo de Clientes.</span>
                                @endif
                                @error('client_id') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        @elseif($source_type === 'website')
                            <div style="margin-top:12px;">
                                <label class="form-label" style="font-size:12px;color:rgba(255,255,255,0.85);margin-bottom:6px;">Sitio Web ya creado en la plataforma</label>
                                <select wire:model.live="linked_case_study_id" class="form-input" style="width:100%;height:44px;background:#18181b;color:#FAFAFA;border-radius:10px;padding:0 12px;font-size:13px;border-color:rgba(244,63,94,0.3);">
                                    <option value="">-- Seleccionar sitio web existente --</option>
                                    @foreach($existingWebsites as $w)
                                        <option value="{{ $w->id }}">{{ $w->titulo }} {{ $w->url_demo ? '(' . $w->url_demo . ')' : '' }}</option>
                                    @endforeach
                                </select>
                                @if($existingWebsites->isEmpty())
                                    <span style="font-size:11px;color:#fca5a5;margin-top:4px;display:block;">No hay sitios web creados aún en categorías web, ecommerce o sistema.</span>
                                @endif
                                @error('linked_case_study_id') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        @else
                            <div style="font-size:12px;color:rgba(255,255,255,0.5);font-style:italic;padding:6px 0;">
                                Selecciona si este film está vinculado a una <strong>Empresa (Cliente)</strong> o a un <strong>Sitio Web</strong> existente.
                            </div>
                        @endif
                        @error('source_type') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    @endif
                </div>

                <!-- 2. Métrica Destacada -->
                <div class="form-card-section">
                    <div class="section-badge-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Métrica Destacada
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Métrica o Resultado</label>
                            <input type="text" wire:model.live.debounce.250ms="metrica_valor" class="form-input" placeholder="Ej: +240%, 4.8x ROI, #1 Google"/>
                            @error('metrica_valor') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Etiqueta explicativa</label>
                            <input type="text" wire:model.live.debounce.250ms="metrica_label" class="form-input" placeholder="Ej: incremento en ventas online en 60 días"/>
                            @error('metrica_label') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- 3. Estilo Visual & Portada / Video (Dinámico según Categoría) -->
                @if($categoria === 'films')
                    <div class="form-card-section" style="border:1px solid rgba(244,63,94,0.3);background:linear-gradient(180deg, rgba(244,63,94,0.03) 0%, transparent 100%);">
                        <div class="section-badge-title" style="color:#f43f5e;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Video & Producción (Films / Reels)
                        </div>

                        <div style="font-size:12.5px;color:rgba(255,255,255,0.7);margin-bottom:18px;line-height:1.5;">
                            Para <strong>Films & Reels</strong> la plataforma reproduce directamente el video interactivo (no se requieren fotos de portada estáticas). Elige el formato y carga tu video o ingresa la URL de streaming.
                        </div>

                        <!-- Selector de Formato: Reel (9:16) vs Horizontal (16:9) -->
                        <div class="form-group" style="margin-bottom:18px;">
                            <label class="form-label">Formato de Video / Orientación <span class="required">*</span></label>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:6px;">
                                <button type="button"
                                        wire:click="$set('video_orientation', 'vertical')"
                                        style="padding:14px 16px;border-radius:12px;border:1px solid {{ $video_orientation === 'vertical' ? '#f43f5e' : 'rgba(255,255,255,0.1)' }};background:{{ $video_orientation === 'vertical' ? 'rgba(244,63,94,0.15)' : 'rgba(255,255,255,0.03)' }};cursor:pointer;display:flex;align-items:center;gap:12px;text-align:left;transition:all 0.2s;">
                                    <div style="width:36px;height:48px;border:2px solid {{ $video_orientation === 'vertical' ? '#f43f5e' : 'rgba(255,255,255,0.3)' }};border-radius:6px;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.4);flex-shrink:0;">
                                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24" style="color:{{ $video_orientation === 'vertical' ? '#f43f5e' : 'rgba(255,255,255,0.6)' }};"><path d="M14.75 2h-5.5C7.46 2 6 3.46 6 5.25v13.5C6 20.54 7.46 22 9.25 22h5.5c1.79 0 3.25-1.46 3.25-3.25V5.25C18 3.46 16.54 2 14.75 2zm-2.75 18.5a1.25 1.25 0 110-2.5 1.25 1.25 0 010 2.5z"/></svg>
                                    </div>
                                    <div>
                                        <div style="font-size:14px;font-weight:700;color:{{ $video_orientation === 'vertical' ? '#fff' : 'rgba(255,255,255,0.8)' }};">Reel / Vertical (9:16)</div>
                                        <div style="font-size:11.5px;color:rgba(255,255,255,0.5);margin-top:2px;">Reels de Instagram, TikToks, Shorts</div>
                                    </div>
                                </button>

                                <button type="button"
                                        wire:click="$set('video_orientation', 'horizontal')"
                                        style="padding:14px 16px;border-radius:12px;border:1px solid {{ $video_orientation === 'horizontal' ? '#f43f5e' : 'rgba(255,255,255,0.1)' }};background:{{ $video_orientation === 'horizontal' ? 'rgba(244,63,94,0.15)' : 'rgba(255,255,255,0.03)' }};cursor:pointer;display:flex;align-items:center;gap:12px;text-align:left;transition:all 0.2s;">
                                    <div style="width:48px;height:32px;border:2px solid {{ $video_orientation === 'horizontal' ? '#f43f5e' : 'rgba(255,255,255,0.3)' }};border-radius:6px;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.4);flex-shrink:0;">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:{{ $video_orientation === 'horizontal' ? '#f43f5e' : 'rgba(255,255,255,0.6)' }};"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <div style="font-size:14px;font-weight:700;color:{{ $video_orientation === 'horizontal' ? '#fff' : 'rgba(255,255,255,0.8)' }};">Horizontal (16:9)</div>
                                        <div style="font-size:11.5px;color:rgba(255,255,255,0.5);margin-top:2px;">Cinemático, comercial, video de marca</div>
                                    </div>
                                </button>
                            </div>
                            @error('video_orientation') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <!-- Enlace de Video / CDN / Streaming -->
                        <div class="form-group" style="margin-bottom:16px;">
                            <label class="form-label">Enlace de Video / Streaming <span class="required">*</span></label>
                            <div style="position:relative;">
                                <input type="text"
                                       wire:model.live.debounce.250ms="video_url"
                                       class="form-input"
                                       style="height:46px;padding-left:42px;font-size:13.5px;"
                                       placeholder="https://pub-...r2.dev/video.mp4 o https://videodelivery.net/..."/>
                                <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#f43f5e;display:flex;align-items:center;pointer-events:none;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </span>
                            </div>
                            <span style="font-size:11.5px;color:rgba(255,255,255,0.55);margin-top:6px;display:block;">
                                Pega la URL de tu video en <strong>Cloudflare R2</strong>, <strong>Cloudflare Stream</strong>, <strong>AWS S3</strong>, <strong>YouTube Shorts</strong>, <strong>Vimeo</strong> o archivo directo <code>.mp4</code> / <code>.webm</code>. Carga y reproduce al instante.
                            </span>
                            @error('video_url') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <!-- Duración Estimada y Tags -->
                        <div class="form-grid-2">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Duración del Reel/Video</label>
                                <input type="text" wire:model.live.debounce.250ms="video_duration" class="form-input" placeholder="Ej: 0:45, 1:15"/>
                                <span style="font-size:11px;color:var(--text-subtle);margin-top:4px;display:block;">Se mostrará como badge en el reproductor</span>
                                @error('video_duration') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Tags / Especialidad</label>
                                <input type="text" wire:model.live.debounce.250ms="tags_input" class="form-input" placeholder="Reels, Edición, Drone, Color Grading, 4K"/>
                                @error('tags_input') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Previsualización de Video en el Formulario -->
                        @if(filled($video_url))
                            <div style="margin-top:16px;padding:14px;background:rgba(0,0,0,0.5);border:1px solid rgba(244,63,94,0.3);border-radius:12px;">
                                <div style="font-size:12px;font-weight:700;color:#f43f5e;margin-bottom:8px;display:flex;align-items:center;gap:6px;">
                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    Video Conectado y Listo para Reproducción Rápida
                                </div>
                                @if(\App\Support\CaseStudyVideo::isDirectVideo($video_url))
                                    <video src="{{ $video_url }}" controls preload="metadata" playsinline style="max-height:220px;border-radius:8px;background:#000;width:auto;max-width:100%;"></video>
                                @elseif(\App\Support\CaseStudyVideo::resolveEmbedUrl($video_url))
                                    <iframe src="{{ \App\Support\CaseStudyVideo::resolveEmbedUrl($video_url) }}" style="width:100%;height:220px;border-radius:8px;border:none;" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                @else
                                    <div style="font-size:12px;color:rgba(255,255,255,0.7);">
                                        URL configurada: <a href="{{ $video_url }}" target="_blank" style="color:#f43f5e;text-decoration:underline;">{{ $video_url }}</a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @else
                    <div class="form-card-section">
                        <div class="section-badge-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Visual & Portada
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tags / Tecnologías</label>
                            <input type="text" wire:model.live.debounce.250ms="tags_input" class="form-input" placeholder="Next.js, Laravel, Shopify, UI/UX (separados por coma)"/>
                            <span style="font-size:11px;color:var(--text-subtle);margin-top:4px;display:block;">Escribe los tags separados por coma</span>
                            @error('tags_input') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-grid-2" style="margin-bottom:14px;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Color Inicial (Gradiente)</label>
                                <div style="display:flex;gap:8px;align-items:center;">
                                    <input type="color" wire:model.live="gradient_inicio" style="width:46px;height:42px;border-radius:10px;border:1px solid var(--border-default);cursor:pointer;padding:2px;background:#1a1a1a;">
                                    <input type="text" wire:model.live="gradient_inicio" class="form-input" placeholder="#6366f1" style="flex:1;font-family:monospace;font-size:13px;">
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Color Final (Gradiente)</label>
                                <div style="display:flex;gap:8px;align-items:center;">
                                    <input type="color" wire:model.live="gradient_fin" style="width:46px;height:42px;border-radius:10px;border:1px solid var(--border-default);cursor:pointer;padding:2px;background:#1a1a1a;">
                                    <input type="text" wire:model.live="gradient_fin" class="form-input" placeholder="#8b5cf6" style="flex:1;font-family:monospace;font-size:13px;">
                                </div>
                            </div>
                        </div>

                        <!-- Gradiente Preview Bar -->
                        <div style="height:36px;border-radius:8px;margin-bottom:20px;background:linear-gradient(135deg, {{ $gradient_inicio }}, {{ $gradient_fin }});border:1px solid rgba(255,255,255,0.12);"></div>

                        <!-- Imagen de Portada -->
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Imagen de Portada (Opcional)</label>

                            <div style="display:flex;flex-direction:column;gap:12px;">
                                @if($imagenActual && !$imagen_nueva)
                                    <div style="display:flex;align-items:center;gap:14px;padding:12px;background:rgba(255,255,255,0.03);border:1px solid var(--border-default);border-radius:12px;">
                                        <div style="width:80px;height:48px;border-radius:8px;overflow:hidden;border:1px solid rgba(255,255,255,0.1);flex-shrink:0;">
                                            <img src="{{ $imagenActual }}" alt="Imagen actual" style="width:100%;height:100%;object-fit:cover;">
                                        </div>
                                        <div style="font-size:12px;color:var(--text-secondary);flex:1;">
                                            <div style="font-weight:600;color:#fff;">Imagen actual guardada</div>
                                            <div style="color:var(--text-muted);">Puedes subir una nueva imagen abajo para reemplazarla.</div>
                                        </div>
                                    </div>
                                @endif

                                @if($imagen_nueva)
                                    <div style="display:flex;align-items:center;gap:14px;padding:12px;background:rgba(16,185,129,0.05);border:1px solid rgba(16,185,129,0.3);border-radius:12px;">
                                        <div style="width:80px;height:48px;border-radius:8px;overflow:hidden;border:1px solid rgba(16,185,129,0.3);flex-shrink:0;">
                                            <img src="{{ $imagen_nueva->temporaryUrl() }}" alt="Nueva imagen" style="width:100%;height:100%;object-fit:cover;">
                                        </div>
                                        <div style="font-size:12px;color:var(--text-secondary);flex:1;">
                                            <div style="font-weight:600;color:#10B981;">Nueva imagen seleccionada</div>
                                            <div style="color:var(--text-muted);">Lista para subir al guardar.</div>
                                        </div>
                                        <button type="button" wire:click="$set('imagen_nueva', null)" class="btn btn-sm btn-secondary" style="font-size:11px;padding:4px 8px;">Quitar</button>
                                    </div>
                                @endif

                                <input type="file" wire:model="imagen_nueva" accept="image/jpeg,image/png,image/webp" class="form-input" style="padding:10px;cursor:pointer;">
                                @error('imagen_nueva') <span class="form-error">{{ $message }}</span> @enderror

                                <div style="padding:10px 14px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.06);border-radius:8px;font-size:12px;color:var(--text-muted);line-height:1.6;">
                                    <strong style="color:var(--text-secondary);">Guía de formato:</strong>
                                    Recomendado <strong style="color:var(--text-secondary);">800 × 400 px</strong> (2:1). Máx <strong>2 MB</strong> en <strong>JPG, PNG o WebP</strong>.
                                    Si no subes imagen, se utilizará el degradado seleccionado.
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 4. Configuración -->
                <div class="form-card-section">
                    <div class="section-badge-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Configuración
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Orden numérico</label>
                            <input type="number" wire:model="orden" min="0" max="999" class="form-input" placeholder="0"/>
                            <span style="font-size:11px;color:var(--text-subtle);margin-top:4px;display:block;">El número más bajo aparece primero en la galería</span>
                            @error('orden') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Visibilidad pública</label>
                            <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:11px 14px;background:#1a1a1a;border:1px solid var(--border-default);border-radius:10px;">
                                <input type="checkbox" wire:model="activo" style="width:18px;height:18px;accent-color:var(--accent-red);cursor:pointer;">
                                <span style="font-size:14px;font-weight:600;color:#fff;">Visible en el portafolio</span>
                            </label>
                            <span style="font-size:11px;color:var(--text-subtle);margin-top:4px;display:block;">Desmarca para ocultarlo temporalmente</span>
                        </div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div style="display:flex;align-items:center;justify-content:flex-end;gap:12px;padding:20px 0;border-top:1px solid var(--border-default);margin-top:8px;">
                    <a href="{{ route('case-studies.index') }}" wire:navigate class="btn btn-secondary" style="height:48px;padding:0 24px;">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" style="height:48px;padding:0 28px;font-weight:700;">
                        <span wire:loading.remove wire:target="save">
                            {{ $editingId ? 'Guardar Cambios' : 'Crear Caso de Estudio' }}
                        </span>
                        <span wire:loading wire:target="save">
                            <span style="display:inline-flex;align-items:center;gap:8px;">
                                <thinking-orb state="working" size="16"></thinking-orb>
                                <span>Guardando proyecto...</span>
                            </span>
                        </span>
                    </button>
                </div>

            </form>
        </div>

        <!-- LIVE PREVIEW COLUMN -->
        <div class="preview-sticky-wrap">
            <div style="background:#141414;border:1px solid var(--border-default);border-radius:18px;padding:22px;box-shadow:0 4px 24px rgba(0,0,0,0.25);">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--text-muted);display:flex;align-items:center;gap:6px;">
                        <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#10B981;"></span>
                        Vista Previa en Vivo
                    </div>
                    <span style="font-size:11px;color:var(--text-subtle);">Portafolio Público</span>
                </div>

                @if($categoria === 'films' && $video_orientation === 'vertical')
                    <!-- SMARTPHONE REEL MOCKUP -->
                    <div class="smartphone-mockup-frame">
                        <span class="smartphone-btn-vol-up"></span>
                        <span class="smartphone-btn-vol-down"></span>
                        <span class="smartphone-btn-power"></span>

                        <div class="smartphone-screen">
                            <!-- Dynamic Island -->
                            <div class="smartphone-island">
                                <span class="smartphone-island-cam"></span>
                                <span class="smartphone-island-sensor"></span>
                            </div>

                            <!-- Status Bar -->
                            <div class="smartphone-status-bar">
                                <span>9:41</span>
                                <div style="display:flex;align-items:center;gap:4px;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3c-4.97 0-9 4.03-9 9 0 2.12.74 4.07 1.97 5.61L4.35 19.4c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0l1.9-1.9C9.17 19.64 10.53 20 12 20c4.97 0 9-4.03 9-9s-4.03-9-9-9z"/></svg>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M15.67 4H14V2h-4v2H8.33C7.6 4 7 4.6 7 5.33v15.33C7 21.4 7.6 22 8.33 22h7.33c.74 0 1.34-.6 1.34-1.33V5.33C17 4.6 16.4 4 15.67 4z"/></svg>
                                </div>
                            </div>

                            <!-- Video Stage -->
                            <div class="smartphone-video-stage">
                                @if(filled($video_url) && \App\Support\CaseStudyVideo::isDirectVideo($video_url))
                                    <video src="{{ $video_url }}" autoplay muted loop playsinline class="smartphone-video-el"></video>
                                @elseif(filled($video_url) && \App\Support\CaseStudyVideo::resolveEmbedUrl($video_url))
                                    <iframe src="{{ \App\Support\CaseStudyVideo::resolveEmbedUrl($video_url) }}" style="width:100%;height:100%;border:none;" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                @else
                                    <div style="position:absolute;inset:0;background:radial-gradient(circle at center, rgba(244,63,94,0.22) 0%, rgba(9,9,11,0.96) 80%);display:flex;align-items:center;justify-content:center;flex-direction:column;gap:12px;">
                                        <div style="width:54px;height:54px;border-radius:50%;background:rgba(244,63,94,0.18);border:2px solid #f43f5e;display:flex;align-items:center;justify-content:center;box-shadow:0 0 25px rgba(244,63,94,0.45);">
                                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" style="color:#fff;margin-left:3px;"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                        <span style="font-size:11px;font-weight:700;color:rgba(255,255,255,0.7);letter-spacing:0.04em;">
                                            {{ filled($video_url) ? 'Video Conectado' : 'Reproductor Reel 9:16' }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Reel Overlay -->
                            <div class="smartphone-reel-overlay">
                                <!-- Top Bar -->
                                <div style="display:flex;align-items:center;justify-content:space-between;width:100%;">
                                    <div style="display:flex;align-items:center;gap:6px;background:rgba(0,0,0,0.6);padding:3px 8px;border-radius:6px;border:1px solid rgba(255,255,255,0.15);backdrop-filter:blur(4px);">
                                        <span style="width:6px;height:6px;border-radius:50%;background:#f43f5e;box-shadow:0 0 6px #f43f5e;"></span>
                                        <span style="font-size:10px;font-weight:800;color:#fff;letter-spacing:0.06em;">REEL 9:16</span>
                                    </div>
                                    <span style="font-size:10px;color:rgba(255,255,255,0.85);font-family:monospace;background:rgba(0,0,0,0.6);padding:2px 7px;border-radius:4px;backdrop-filter:blur(4px);">
                                        {{ filled($video_duration) ? $video_duration : '0:30' }}
                                    </span>
                                </div>

                                <!-- Floating Actions -->
                                <div class="smartphone-actions-col">
                                    <div class="smartphone-action-item">
                                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        <span>24.5k</span>
                                    </div>
                                    <div class="smartphone-action-item">
                                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/></svg>
                                        <span>680</span>
                                    </div>
                                    <div class="smartphone-action-item">
                                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                                        <span>Share</span>
                                    </div>
                                    <div class="smartphone-sound-disc">
                                        <div style="width:10px;height:10px;border-radius:50%;background:#f43f5e;"></div>
                                    </div>
                                </div>

                                <!-- Bottom Metadata -->
                                <div class="smartphone-bottom-meta">
                                    <div class="smartphone-user-badge">
                                        <span style="width:18px;height:18px;border-radius:50%;background:#f43f5e;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:900;color:#fff;">K</span>
                                        <span>@kosta.studio</span>
                                        <svg width="12" height="12" fill="#38bdf8" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                    </div>

                                    <h4 class="smartphone-reel-title">
                                        {{ filled($titulo) ? $titulo : 'Título del Reel' }}
                                    </h4>

                                    <p class="smartphone-reel-desc">
                                        {{ filled($descripcion) ? \Illuminate\Support\Str::limit($descripcion, 85) : 'Producción audiovisual y reels de alto impacto para marcas...' }}
                                    </p>

                                    @if(filled($metrica_valor))
                                        <div style="display:inline-flex;align-items:center;gap:5px;background:rgba(244,63,94,0.3);border:1px solid rgba(244,63,94,0.5);padding:2px 8px;border-radius:6px;margin-bottom:6px;font-size:10px;color:#fff;font-weight:700;">
                                            <span>{{ $metrica_valor }}</span> {{ $metrica_label }}
                                        </div>
                                    @endif

                                    <div class="smartphone-audio-row">
                                        <svg width="11" height="11" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
                                        <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:180px;">Audio Original - Kosta Films</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Home Bar -->
                            <div class="smartphone-home-bar"></div>
                        </div>
                    </div>
                @else
                    <!-- Card Replica from /portafolio -->
                    <div class="mockup-preview-card">
                        @if($categoria === 'films')
                            <!-- Video Player Header (16:9 Cinema) -->
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:rgba(0,0,0,0.85);border-bottom:1px solid rgba(244,63,94,0.25);">
                                <div style="display:flex;align-items:center;gap:7px;">
                                    <span style="width:8px;height:8px;border-radius:50%;background:#f43f5e;box-shadow:0 0 8px #f43f5e;display:block;"></span>
                                    <span style="font-size:11px;font-weight:700;color:#f43f5e;letter-spacing:0.06em;text-transform:uppercase;">
                                        FILM 16:9 (CINEMA)
                                    </span>
                                </div>
                                <div style="font-size:11px;color:rgba(255,255,255,0.45);font-family:monospace;">
                                    {{ filled($video_duration) ? $video_duration : '1:30' }}
                                </div>
                            </div>

                            <!-- Video Screen -->
                            <div style="height:185px;position:relative;overflow:hidden;background:#09090b;display:flex;align-items:center;justify-content:center;">
                                @if(filled($video_url) && \App\Support\CaseStudyVideo::isDirectVideo($video_url))
                                    <video src="{{ $video_url }}" autoplay muted loop playsinline style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;"></video>
                                @else
                                    <div style="position:absolute;inset:0;background:radial-gradient(circle at center, rgba(244,63,94,0.18) 0%, rgba(9,9,11,0.95) 75%);"></div>
                                    <div style="z-index:2;display:flex;flex-direction:column;align-items:center;gap:8px;">
                                        <div style="width:48px;height:48px;border-radius:50%;background:rgba(244,63,94,0.2);border:2px solid #f43f5e;display:flex;align-items:center;justify-content:center;box-shadow:0 0 20px rgba(244,63,94,0.4);">
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24" style="color:#fff;margin-left:2px;"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                        <span style="font-size:11px;font-weight:600;color:rgba(255,255,255,0.7);letter-spacing:0.04em;">
                                            {{ filled($video_url) ? 'Video Enlazado' : 'Reproductor Cinemático 16:9' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Browser Chrome -->
                            <div style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:rgba(0,0,0,0.6);border-bottom:1px solid rgba(255,255,255,0.06);">
                                <div style="display:flex;gap:5px;">
                                    <span style="width:8px;height:8px;border-radius:50%;background:#ff5f57;display:block;"></span>
                                    <span style="width:8px;height:8px;border-radius:50%;background:#febc2e;display:block;"></span>
                                    <span style="width:8px;height:8px;border-radius:50%;background:#28c840;display:block;"></span>
                                </div>
                                <div style="flex:1;background:rgba(255,255,255,0.07);border-radius:4px;padding:3px 10px;font-size:11px;color:rgba(255,255,255,0.35);font-family:monospace;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ filled($url_demo) ? $url_demo : 'kosta.studio' }}
                                </div>
                            </div>

                            <!-- Screen with Gradient / Image -->
                            <div style="height:175px;position:relative;overflow:hidden;background:linear-gradient(135deg, {{ $gradient_inicio }}, {{ $gradient_fin }});">
                                @if($imagen_nueva)
                                    <img src="{{ $imagen_nueva->temporaryUrl() }}" alt="Preview" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                                @elseif($imagenActual)
                                    <img src="{{ $imagenActual }}" alt="Preview" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                                @else
                                    <!-- Mock UI wireframe -->
                                    <div style="position:absolute;top:0;left:0;right:0;height:24px;background:rgba(0,0,0,0.25);display:flex;align-items:center;padding:0 12px;gap:6px;">
                                        <div style="width:20px;height:4px;border-radius:2px;background:rgba(255,255,255,0.3);"></div>
                                        <div style="width:36px;height:4px;border-radius:2px;background:rgba(255,255,255,0.3);"></div>
                                    </div>
                                    <div style="position:absolute;top:38px;left:18px;right:18px;">
                                        <div style="height:8px;width:70%;border-radius:4px;background:rgba(255,255,255,0.7);margin-bottom:6px;"></div>
                                        <div style="height:5px;width:85%;border-radius:3px;background:rgba(255,255,255,0.3);margin-bottom:4px;"></div>
                                        <div style="height:5px;width:55%;border-radius:3px;background:rgba(255,255,255,0.3);"></div>
                                        <div style="margin-top:12px;width:56px;height:16px;border-radius:12px;background:rgba(255,255,255,0.5);"></div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Card Body -->
                        <div style="padding:20px 22px;">
                            <!-- Category Badge -->
                            <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:999px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:10px;border:1px solid transparent;{{ $catColors[(string)$categoria] ?? '' }}">
                                {{ $catNames[(string)$categoria] ?? (string)$categoria }}
                            </span>

                            <!-- Title -->
                            <h3 style="font-family:'Syne',sans-serif;font-size:17px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">
                                {{ filled($titulo) ? $titulo : 'Título del Proyecto' }}
                            </h3>

                            <!-- Description -->
                            <p style="font-size:13px;color:rgba(255,255,255,0.5);line-height:1.5;margin:0 0 14px;">
                                {{ filled($descripcion) ? \Illuminate\Support\Str::limit($descripcion, 120) : 'Breve descripción del resultado y valor entregado al cliente...' }}
                            </p>

                            <!-- Metric -->
                            @if(filled($metrica_valor))
                                <div style="display:flex;align-items:baseline;gap:6px;margin-bottom:14px;padding:8px 12px;background:rgba(255,255,255,0.03);border-radius:8px;border:1px solid rgba(255,255,255,0.06);">
                                    <span style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;color:var(--accent-red);">
                                        {{ $metrica_valor }}
                                    </span>
                                    @if(filled($metrica_label))
                                        <span style="font-size:11.5px;color:rgba(255,255,255,0.45);">
                                            {{ $metrica_label }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Tags -->
                            @php
                                $previewTags = array_values(array_filter(array_map('trim', explode(',', $tags_input))));
                            @endphp
                            @if(count($previewTags) > 0)
                                <div style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:16px;">
                                    @foreach(array_slice($previewTags, 0, 4) as $tag)
                                        <span style="padding:3px 8px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:5px;font-size:10.5px;color:rgba(255,255,255,0.5);">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Simulated CTA -->
                            <div style="display:flex;gap:8px;">
                                <span style="padding:6px 14px;border-radius:999px;font-size:11.5px;font-weight:700;background:var(--accent-red);color:#0A0A0A;display:inline-flex;align-items:center;gap:6px;">
                                    {{ $categoria === 'films' ? 'Ver film' : ($categoria === 'social' ? 'Visitar red' : ($categoria === 'sistema' ? 'Ver sistema' : 'Visitar página')) }}
                                    <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                <div style="margin-top:14px;font-size:11.5px;color:var(--text-subtle);text-align:center;">
                    Los cambios se reflejan aquí mientras escribes.
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-select-trigger {
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .custom-select-trigger.is-open {
            border-color: rgba(255, 255, 255, 0.4) !important;
            box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.15) !important;
        }
        .custom-select-arrow {
            width: 16px !important;
            height: 16px !important;
            min-width: 16px !important;
            min-height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
            color: var(--text-muted);
            transition: transform 0.2s ease, color 0.2s ease;
            flex-shrink: 0;
            display: block;
        }
        .custom-select-arrow.is-open {
            transform: rotate(180deg);
            color: #FFFFFF !important;
        }
        .custom-select-opt {
            transition: background 0.15s ease, transform 0.1s ease;
        }
        .custom-select-opt:hover {
            background: rgba(255, 255, 255, 0.07) !important;
        }
        .custom-select-opt:active {
            transform: scale(0.99);
        }
        .custom-select-opt.is-active {
            background: rgba(255, 255, 255, 0.10) !important;
        }
        .custom-select-trigger:focus,
        .custom-select-trigger:focus-visible {
            outline: none;
            border-color: rgba(255, 255, 255, 0.4) !important;
            box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.15) !important;
        }
    </style>
</div>
