<div>
    @php
        $catNames = [
            'web' => 'Páginas Web',
            'ecommerce' => 'E-commerce',
            'branding' => 'Branding',
            'social' => 'Redes Sociales',
        ];
        $catColors = [
            'web'       => 'background:rgba(99,102,241,0.15);color:#818cf8;border-color:rgba(99,102,241,0.3);',
            'ecommerce' => 'background:rgba(245,158,11,0.15);color:#fbbf24;border-color:rgba(245,158,11,0.3);',
            'branding'  => 'background:rgba(16,185,129,0.15);color:#34d399;border-color:rgba(16,185,129,0.3);',
            'social'    => 'background:rgba(236,72,153,0.15);color:#f472b6;border-color:rgba(236,72,153,0.3);',
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

                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Categoría <span class="required">*</span></label>
                            <select wire:model.live="categoria" class="form-input" style="cursor:pointer;">
                                <option value="web">Página Web</option>
                                <option value="ecommerce">E-commerce</option>
                                <option value="branding">Branding</option>
                                <option value="social">Redes Sociales</option>
                            </select>
                            @error('categoria') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
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

                <!-- 3. Estilo Visual & Portada -->
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
                                        <img src="{{ Storage::url($imagenActual) }}" alt="Imagen actual" style="width:100%;height:100%;object-fit:cover;">
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
                        <span wire:loading wire:target="save" style="display:inline-flex;align-items:center;gap:8px;">
                            <thinking-orb state="working" size="16"></thinking-orb>
                            <span>Guardando proyecto...</span>
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

                <!-- Card Replica from /portafolio -->
                <div class="mockup-preview-card">
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
                            <img src="{{ Storage::url($imagenActual) }}" alt="Preview" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
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
                                {{ $categoria === 'social' ? 'Visitar red' : 'Visitar página' }}
                                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </span>
                        </div>
                    </div>
                </div>

                <div style="margin-top:14px;font-size:11.5px;color:var(--text-subtle);text-align:center;">
                    Los cambios se reflejan aquí mientras escribes.
                </div>
            </div>
        </div>
    </div>
</div>
