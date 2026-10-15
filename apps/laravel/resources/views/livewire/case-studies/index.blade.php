<div>
    <style>
        .case-select {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            margin: 0;
            border: 1px solid rgba(255, 255, 255, 0.32);
            border-radius: 4px;
            background: #080808 !important;
            display: inline-grid;
            place-content: center;
            cursor: pointer;
            vertical-align: middle;
            transition: border-color 160ms ease, box-shadow 160ms ease;
        }

        .case-select::before {
            content: '';
            width: 5px;
            height: 9px;
            border-right: 2px solid #fff;
            border-bottom: 2px solid #fff;
            transform: rotate(45deg) scale(0);
            transform-origin: center;
            transition: transform 140ms ease;
        }

        .case-select:hover { border-color: rgba(255, 255, 255, 0.62); }
        .case-select:checked { border-color: #fff; }
        .case-select:checked::before { transform: rotate(45deg) scale(1); }
        .case-select:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.14);
        }

        .case-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-top: 20px;
        }

        .case-pagination__summary {
            color: var(--text-muted);
            font-size: 13px;
        }

        .case-pagination__summary strong {
            color: var(--text-secondary);
            font-family: 'Geist Mono', 'Geist', monospace;
            font-weight: 600;
        }

        .case-pagination__controls {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .case-pagination__button {
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border: 1px solid var(--border-default);
            border-radius: 9px;
            background: #080808;
            color: var(--text-secondary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'Geist Mono', 'Geist', monospace;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: border-color 160ms ease, color 160ms ease, background 160ms ease;
        }

        .case-pagination__button:hover:not(:disabled) {
            border-color: rgba(255, 255, 255, 0.42);
            color: #fff;
            background: rgba(255, 255, 255, 0.04);
        }

        .case-pagination__button[aria-current='page'] {
            border-color: rgba(255, 255, 255, 0.72);
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .case-pagination__button:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .case-pagination__button:focus-visible {
            outline: 2px solid rgba(255, 255, 255, 0.62);
            outline-offset: 2px;
        }

        .case-pagination__button svg { width: 16px; height: 16px; }

        .usa-award-toggle {
            width: 34px;
            height: 34px;
            display: inline-grid;
            place-items: center;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.14);
            background: #111;
            color: rgba(255,255,255,.34);
            cursor: pointer;
            transition: color .18s ease, border-color .18s ease, background .18s ease, transform .18s ease;
        }
        .usa-award-toggle:hover { transform: translateY(-1px); color:#fff; border-color:rgba(248,113,113,.5); }
        .usa-award-toggle.is-active { color:#fbbf24; border-color:rgba(248,113,113,.65); background:linear-gradient(135deg,#991b1b,#450a0a); box-shadow:0 6px 18px rgba(185,28,28,.4), inset 0 1px 0 rgba(255,255,255,.2); }
        .usa-award-toggle svg { width:15px; height:15px; filter: drop-shadow(0 1px 2px rgba(0,0,0,.5)); }

        @media (max-width: 640px) {
            .case-pagination { flex-direction: column; align-items: stretch; }
            .case-pagination__summary { text-align: center; }
            .case-pagination__controls { justify-content: center; }
        }
    </style>

    <!-- Header -->
    @php
        $catNames = ['web' => 'Páginas Web', 'ecommerce' => 'E-commerce', 'sistema' => 'Sistema', 'branding' => 'Branding', 'films' => 'Films & Reels', 'social' => 'Films'];
    @endphp
    <div class="crud-header">
        <div>
            <h1 class="crud-title">
                Casos de Estudio
                @if(!empty($categoriaFiltro))
                    <span style="font-size:18px;font-weight:500;color:var(--red-primary);margin-left:8px;">— {{ $catNames[(string)$categoriaFiltro] ?? $categoriaFiltro }}</span>
                @endif
            </h1>
            <p class="crud-subtitle">Gestiona los proyectos que aparecen en tu portafolio público</p>
        </div>
        <a href="{{ route('case-studies.create') }}" wire:navigate class="btn btn-primary">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Caso
        </a>
    </div>

    @if(session('notify'))
        <div style="background:rgba(16,185,129,0.12);border:1px solid rgba(16,185,129,0.3);color:#10B981;border-radius:12px;padding:12px 18px;margin-bottom:20px;font-size:14px;font-weight:600;display:flex;align-items:center;gap:10px;">
            <svg style="width:18px;height:18px;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('notify') }}</span>
        </div>
    @endif

    <!-- Toolbar -->
    <div class="crud-toolbar">
        <div class="crud-search-wrapper">
            <svg class="crud-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por título o categoría..." class="crud-search-input"/>
        </div>
        <a href="{{ route('portfolio') }}" target="_blank" rel="noopener" class="btn btn-secondary" style="flex-shrink:0;">
            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Ver portafolio
        </a>
    </div>

    <!-- Bulk Actions Toolbar -->
    @if(count($selectedIds) > 0)
        <div style="background:rgba(20,20,20,0.95);border:1px solid rgba(255,255,255,0.2);border-radius:14px;padding:12px 20px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;box-shadow:0 8px 30px rgba(0,0,0,0.5),inset 0 0 30px rgba(255,255,255,0.03);animation:fadeIn 0.2s ease;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.25);border-radius:999px;padding:5px 14px;font-size:13px;font-weight:700;color:#FFFFFF;">
                    <span>{{ count($selectedIds) }}</span> seleccionado(s)
                </div>
                <button wire:click="clearSelection" type="button" style="background:none;border:none;color:var(--text-muted);font-size:12px;cursor:pointer;text-decoration:underline;">
                    Deseleccionar todo
                </button>
            </div>

            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                <button wire:click="bulkSetVisibility(true)" wire:loading.attr="disabled" class="btn btn-secondary" style="padding:7px 14px;font-size:12px;gap:6px;display:inline-flex;align-items:center;">
                    <svg wire:loading.remove wire:target="bulkSetVisibility" style="width:14px;height:14px;color:#10B981;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span wire:loading.remove wire:target="bulkSetVisibility">Hacer Visibles</span>
                    <span wire:loading wire:target="bulkSetVisibility">
                        <span style="display:inline-flex;align-items:center;gap:6px;">
                            <thinking-orb state="working" size="14"></thinking-orb>
                            <span>Actualizando...</span>
                        </span>
                    </span>
                </button>
                <button wire:click="bulkSetVisibility(false)" wire:loading.attr="disabled" class="btn btn-secondary" style="padding:7px 14px;font-size:12px;gap:6px;display:inline-flex;align-items:center;">
                    <svg wire:loading.remove wire:target="bulkSetVisibility" style="width:14px;height:14px;color:var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                    <span wire:loading.remove wire:target="bulkSetVisibility">Ocultar</span>
                    <span wire:loading wire:target="bulkSetVisibility">
                        <span style="display:inline-flex;align-items:center;gap:6px;">
                            <thinking-orb state="working" size="14"></thinking-orb>
                            <span>Actualizando...</span>
                        </span>
                    </span>
                </button>
                <button wire:click="deleteSelected"
                        wire:confirm="¿Estás seguro de eliminar los {{ count($selectedIds) }} caso(s) seleccionados? Esta acción es definitiva y no se puede deshacer."
                        wire:loading.attr="disabled"
                        class="btn btn-primary" style="padding:7px 16px;font-size:12px;background:#EF4444;border-color:#DC2626;gap:6px;display:inline-flex;align-items:center;">
                    <svg wire:loading.remove wire:target="deleteSelected" style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span wire:loading.remove wire:target="deleteSelected">Borrar seleccionados</span>
                    <span wire:loading wire:target="deleteSelected">
                        <span style="display:inline-flex;align-items:center;gap:6px;">
                            <thinking-orb state="solving" size="14"></thinking-orb>
                            <span>Borrando...</span>
                        </span>
                    </span>
                </button>
            </div>
        </div>
    @endif

    <!-- Table -->
    <div class="crud-table-wrapper">
        <table class="crud-table" style="min-width: 780px;">
            <thead>
                <tr>
                    <th style="width:40px;text-align:center;">
                        <input type="checkbox" wire:model.live="selectAll" class="case-select" title="Seleccionar todos" aria-label="Seleccionar todos los casos">
                    </th>
                    <th style="width:60px;">Vista previa</th>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Métrica</th>
                    <th style="width:60px;text-align:center;">Orden</th>
                    <th style="width:72px;text-align:center;">Premio</th>
                    <th style="width:80px;text-align:center;">Estado</th>
                    <th style="width:100px;text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody wire:loading.class="opacity-60" wire:target="search,categoriaFiltro,previousPage,nextPage,gotoPage" style="transition: opacity 0.15s ease;">
                @forelse($studies as $study)
                    <tr wire:key="study-{{ $study->id }}" style="{{ in_array((string)$study->id, $selectedIds) ? 'background:rgba(230,57,70,0.06);' : '' }}">
                        <!-- Checkbox Selección -->
                        <td style="text-align:center;">
                            <input type="checkbox" wire:model.live="selectedIds" value="{{ (string)$study->id }}" class="case-select" aria-label="Seleccionar {{ $study->titulo }}">
                        </td>

                        <!-- Preview -->
                        <td>
                            @if($study->categoria === 'films')
                                <div style="width:48px;height:32px;border-radius:6px;background:rgba(244,63,94,0.15);border:1px solid rgba(244,63,94,0.3);display:flex;align-items:center;justify-content:center;color:#f43f5e;">
                                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            @elseif($study->image_url)
                                <div style="width:48px;height:32px;border-radius:6px;overflow:hidden;border:1px solid var(--border-subtle);">
                                    <img src="{{ $study->image_url }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                                </div>
                            @else
                                <div style="width:48px;height:32px;border-radius:6px;background:linear-gradient(135deg,{{ $study->gradient_inicio }},{{ $study->gradient_fin }});border:1px solid rgba(255,255,255,0.08);"></div>
                            @endif
                        </td>

                        <!-- Título + URL + Enlace Empresa / Web -->
                        <td>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <div class="crud-row-name">{{ $study->titulo }}</div>
                                @if($study->categoria === 'films' && ($study->video_orientation ?? 'vertical') === 'vertical')
                                    <span style="font-size:10px;font-weight:700;padding:1px 6px;border-radius:4px;background:rgba(244,63,94,0.18);color:#f43f5e;border:1px solid rgba(244,63,94,0.3);">REEL</span>
                                @elseif($study->categoria === 'films')
                                    <span style="font-size:10px;font-weight:700;padding:1px 6px;border-radius:4px;background:rgba(244,63,94,0.18);color:#f43f5e;border:1px solid rgba(244,63,94,0.3);">16:9</span>
                                @endif
                            </div>
                            @if($study->url_demo)
                                <div class="crud-row-meta">{{ $study->url_demo }}</div>
                            @endif
                            @if($study->categoria === 'films')
                                @if($study->source_type === 'client' && $study->client)
                                    <div style="font-size:11.5px;color:#fb7185;margin-top:3px;display:flex;align-items:center;gap:4px;">
                                        <span>🏢 Empresa:</span>
                                        <strong style="color:#FAFAFA;">{{ $study->client->name }}</strong>
                                        @if($study->client->tax_id)
                                            <span style="opacity:0.75;">(NIT: {{ $study->client->tax_id }})</span>
                                        @endif
                                    </div>
                                @elseif($study->source_type === 'website' && $study->linkedCaseStudy)
                                    <div style="font-size:11.5px;color:#38bdf8;margin-top:3px;display:flex;align-items:center;gap:4px;">
                                        <span>🌐 Sitio web:</span>
                                        <strong style="color:#FAFAFA;">{{ $study->linkedCaseStudy->titulo }}</strong>
                                    </div>
                                @endif
                            @endif
                        </td>

                        <!-- Categoría -->
                        <td>
                            @php
                                $catColors = [
                                    'web'       => 'background:rgba(99,102,241,0.15);color:#818cf8;',
                                    'ecommerce' => 'background:rgba(245,158,11,0.15);color:#fbbf24;',
                                    'sistema'   => 'background:rgba(14,165,233,0.15);color:#38bdf8;',
                                    'branding'  => 'background:rgba(16,185,129,0.15);color:#34d399;',
                                    'films'     => 'background:rgba(244,63,94,0.15);color:#fb7185;',
                                    'social'    => 'background:rgba(244,63,94,0.15);color:#fb7185;',
                                ];
                                $catNames = ['web' => 'Página Web', 'ecommerce' => 'E-commerce', 'sistema' => 'Sistema', 'branding' => 'Branding', 'films' => 'Films & Reels', 'social' => 'Films'];
                            @endphp
                            <span class="badge" style="{{ $catColors[$study->categoria] ?? '' }}">
                                {{ $catNames[$study->categoria] ?? $study->categoria }}
                            </span>
                        </td>

                        <!-- Métrica -->
                        <td>
                            @if($study->metrica_valor)
                                <span style="font-family:'Geist Mono','Geist',monospace;font-weight:700;color:var(--red-primary);font-size:15px;font-variant-numeric:tabular-nums;">{{ $study->metrica_valor }}</span>
                                @if($study->metrica_label)
                                    <div class="crud-row-meta">{{ $study->metrica_label }}</div>
                                @endif
                            @else
                                <span class="muted">—</span>
                            @endif
                        </td>

                        <!-- Orden -->
                        <td class="muted" style="text-align:center;">{{ $study->orden }}</td>

                        <!-- Distintivo Proyecto USA -->
                        <td style="text-align:center;">
                            <button type="button"
                                wire:click="toggleProyectoUsa({{ $study->id }})"
                                wire:loading.attr="disabled"
                                class="usa-award-toggle {{ $study->proyecto_usa ? 'is-active' : '' }}"
                                aria-pressed="{{ $study->proyecto_usa ? 'true' : 'false' }}"
                                title="{{ $study->proyecto_usa ? 'Quitar distintivo Proyecto USA' : 'Marcar como Proyecto USA' }}">
                                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2.75 2.72 5.52 6.09.88-4.4 4.29 1.04 6.06L12 16.64 6.55 19.5l1.04-6.06-4.4-4.29 6.09-.88L12 2.75Z"/></svg>
                            </button>
                        </td>

                        <!-- Estado toggle -->
                        <td style="text-align:center;">
                            <button wire:click="toggleActivo({{ $study->id }})"
                                style="padding:4px 10px;border-radius:999px;font-size:11px;font-weight:600;border:none;cursor:pointer;transition:all .2s;
                                {{ $study->activo
                                    ? 'background:rgba(16,185,129,0.15);color:#34d399;'
                                    : 'background:rgba(255,255,255,0.06);color:var(--text-muted);' }}">
                                {{ $study->activo ? 'Visible' : 'Oculto' }}
                            </button>
                        </td>

                        <!-- Acciones -->
                        <td style="text-align:right;">
                            <a href="{{ route('case-studies.edit', $study->id) }}" wire:navigate class="action-btn" title="Editar">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <button wire:click="delete({{ $study->id }})"
                                wire:confirm="¿Eliminar '{{ $study->titulo }}'? Esta acción no se puede deshacer."
                                class="action-btn danger" title="Eliminar">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="empty-state-cell">
                            <div class="empty-state-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:28px;height:28px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="empty-state-title">No hay casos de estudio</div>
                            <div class="empty-state-desc">Crea tu primer caso para que aparezca en el portafolio</div>
                            <div style="margin-top:16px;">
                                <a href="{{ route('case-studies.create') }}" wire:navigate class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;">
                                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Crear primer caso
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($studies->hasPages())
        @php
            $pageStart = max(1, $studies->currentPage() - 1);
            $pageEnd = min($studies->lastPage(), $pageStart + 2);
            $pageStart = max(1, $pageEnd - 2);
        @endphp
        <nav class="case-pagination" aria-label="Paginación de casos de estudio">
            <div class="case-pagination__summary">
                Mostrando <strong>{{ $studies->firstItem() }}–{{ $studies->lastItem() }}</strong>
                de <strong>{{ $studies->total() }}</strong> casos
            </div>

            <div class="case-pagination__controls">
                <button
                    type="button"
                    wire:click="previousPage"
                    wire:loading.attr="disabled"
                    class="case-pagination__button"
                    aria-label="Página anterior"
                    @disabled($studies->onFirstPage())
                >
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                @for($page = $pageStart; $page <= $pageEnd; $page++)
                    <button
                        type="button"
                        wire:click="gotoPage({{ $page }})"
                        wire:loading.attr="disabled"
                        class="case-pagination__button"
                        @if($page === $studies->currentPage()) aria-current="page" @endif
                        aria-label="Ir a la página {{ $page }}"
                    >
                        {{ $page }}
                    </button>
                @endfor

                <button
                    type="button"
                    wire:click="nextPage"
                    wire:loading.attr="disabled"
                    class="case-pagination__button"
                    aria-label="Página siguiente"
                    @disabled(!$studies->hasMorePages())
                >
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </nav>
    @endif
</div>
