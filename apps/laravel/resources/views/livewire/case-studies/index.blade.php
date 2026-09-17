<div>
    <!-- Header -->
    @php
        $catNames = ['web' => 'Páginas Web', 'ecommerce' => 'E-commerce', 'branding' => 'Branding', 'social' => 'Redes Sociales'];
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
        <button wire:click="openCreateForm" class="btn btn-primary">
            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Caso
        </button>
    </div>

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

    <!-- Modal Create / Edit -->
    @if($showForm)
        <div class="modal-backdrop" wire:click.self="closeForm">
            <div class="modal-card modal-lg" style="max-height:92vh;">
                <div class="modal-header">
                    <h2 class="modal-title">{{ $editingId ? 'Editar Caso de Estudio' : 'Nuevo Caso de Estudio' }}</h2>
                    <button type="button" wire:click="closeForm" class="modal-close">
                        <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="modal-body" style="display:flex;flex-direction:column;gap:0;">

                        <!-- Sección: Información básica -->
                        <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-subtle);margin-bottom:14px;">Información básica</p>

                        <div class="form-group">
                            <label class="form-label">Título del proyecto <span class="required">*</span></label>
                            <input type="text" wire:model="titulo" class="form-input" placeholder="Ej: NovaVet Clinic"/>
                            @error('titulo') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Descripción</label>
                            <textarea wire:model="descripcion" rows="3" class="form-textarea" placeholder="Breve descripción del proyecto y lo que se logró..."></textarea>
                            @error('descripcion') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">URL del sitio</label>
                                <input type="text" wire:model="url_demo" class="form-input" placeholder="cliente.com"/>
                                @error('url_demo') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Categoría <span class="required">*</span></label>
                                <select wire:model="categoria" class="form-input" style="cursor:pointer;">
                                    <option value="web">Página Web</option>
                                    <option value="ecommerce">E-commerce</option>
                                    <option value="branding">Branding</option>
                                    <option value="social">Redes Sociales</option>
                                </select>
                                @error('categoria') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Sección: Métrica destacada -->
                        <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-subtle);margin:8px 0 14px;">Métrica destacada</p>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Valor</label>
                                <input type="text" wire:model="metrica_valor" class="form-input" placeholder="Ej: +120%, $45K, #1"/>
                                @error('metrica_valor') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Descripción del resultado</label>
                                <input type="text" wire:model="metrica_label" class="form-input" placeholder="Ej: visitas orgánicas en 3 meses"/>
                                @error('metrica_label') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Sección: Visual -->
                        <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-subtle);margin:8px 0 14px;">Visual</p>

                        <div class="form-group">
                            <label class="form-label">Tags / Tecnologías</label>
                            <input type="text" wire:model="tags_input" class="form-input" placeholder="Laravel, Tailwind CSS, SEO  (separados por coma)"/>
                            @error('tags_input') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Color inicial del fondo</label>
                                <div style="display:flex;gap:8px;align-items:center;">
                                    <input type="color" wire:model.live="gradient_inicio" style="width:44px;height:40px;border-radius:8px;border:1px solid var(--border-default);cursor:pointer;padding:2px;background:var(--bg-input);">
                                    <input type="text" wire:model.live="gradient_inicio" class="form-input" placeholder="#6366f1" style="flex:1;font-family:monospace;font-size:13px;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Color final del fondo</label>
                                <div style="display:flex;gap:8px;align-items:center;">
                                    <input type="color" wire:model.live="gradient_fin" style="width:44px;height:40px;border-radius:8px;border:1px solid var(--border-default);cursor:pointer;padding:2px;background:var(--bg-input);">
                                    <input type="text" wire:model.live="gradient_fin" class="form-input" placeholder="#8b5cf6" style="flex:1;font-family:monospace;font-size:13px;">
                                </div>
                            </div>
                        </div>

                        <!-- Gradient preview -->
                        <div style="height:48px;border-radius:10px;margin-bottom:20px;background:linear-gradient(135deg,{{ $gradient_inicio }},{{ $gradient_fin }});border:1px solid rgba(255,255,255,0.08);"></div>

                        <!-- Imagen -->
                        <div class="form-group">
                            <label class="form-label">Imagen de portada</label>

                            @if($imagenActual && !$imagen_nueva)
                                <div style="margin-bottom:10px;border-radius:10px;overflow:hidden;border:1px solid var(--border-subtle);max-height:120px;">
                                    <img src="{{ Storage::url($imagenActual) }}" alt="Imagen actual" style="width:100%;height:120px;object-fit:cover;">
                                </div>
                            @endif

                            @if($imagen_nueva)
                                <div style="margin-bottom:10px;border-radius:10px;overflow:hidden;border:1px solid rgba(16,185,129,0.4);max-height:120px;">
                                    <img src="{{ $imagen_nueva->temporaryUrl() }}" alt="Nueva imagen" style="width:100%;height:120px;object-fit:cover;">
                                </div>
                            @endif

                            <input type="file" wire:model="imagen_nueva" accept="image/jpeg,image/png,image/webp" class="form-input" style="padding:8px;cursor:pointer;">
                            @error('imagen_nueva') <span class="form-error">{{ $message }}</span> @enderror

                            <div style="margin-top:8px;padding:10px 14px;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.06);border-radius:8px;font-size:12px;color:var(--text-muted);line-height:1.6;">
                                <strong style="color:var(--text-secondary);">Guía de imagen:</strong>
                                Tamaño recomendado <strong style="color:var(--text-secondary);">800 × 400 px</strong> (proporción 2:1).
                                Mínimo 600×300 px. Máx <strong style="color:var(--text-secondary);">2 MB</strong>.
                                Formatos: <strong style="color:var(--text-secondary);">JPG, PNG, WebP</strong>.
                                Si no subes imagen se usará el fondo degradado.
                            </div>
                        </div>

                        <!-- Sección: Configuración -->
                        <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-subtle);margin:8px 0 14px;">Configuración</p>

                        <div class="form-grid-2" style="margin-bottom:0;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Orden de aparición</label>
                                <input type="number" wire:model="orden" min="0" max="999" class="form-input" placeholder="0"/>
                                <span style="font-size:11px;color:var(--text-subtle);margin-top:4px;display:block;">Menor número → aparece primero</span>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Estado</label>
                                <div style="display:flex;align-items:center;gap:12px;margin-top:4px;">
                                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;color:var(--text-secondary);">
                                        <input type="checkbox" wire:model="activo" style="width:16px;height:16px;accent-color:var(--red-primary);">
                                        Visible en el portafolio público
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div><!-- /modal-body -->

                    <div class="modal-footer">
                        <button type="button" wire:click="closeForm" class="btn-modal-secondary">Cancelar</button>
                        <button type="submit" class="btn-modal-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Guardar cambios' : 'Crear caso' }}</span>
                            <span wire:loading wire:target="save" style="display:inline-flex;align-items:center;gap:8px;">
                                <thinking-orb state="working" size="16"></thinking-orb>
                                <span>Guardando...</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Bulk Actions Toolbar -->
    @if(count($selectedIds) > 0)
        <div style="background:rgba(20,20,20,0.95);border:1px solid rgba(230,57,70,0.3);border-radius:14px;padding:12px 20px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;box-shadow:0 8px 30px rgba(0,0,0,0.5),inset 0 0 30px rgba(230,57,70,0.06);animation:fadeIn 0.2s ease;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(230,57,70,0.15);border:1px solid rgba(230,57,70,0.35);border-radius:999px;padding:5px 14px;font-size:13px;font-weight:700;color:var(--red-primary);">
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
                    <span wire:loading wire:target="bulkSetVisibility" style="display:inline-flex;align-items:center;gap:6px;">
                        <thinking-orb state="working" size="14"></thinking-orb>
                        <span>Actualizando...</span>
                    </span>
                </button>
                <button wire:click="bulkSetVisibility(false)" wire:loading.attr="disabled" class="btn btn-secondary" style="padding:7px 14px;font-size:12px;gap:6px;display:inline-flex;align-items:center;">
                    <svg wire:loading.remove wire:target="bulkSetVisibility" style="width:14px;height:14px;color:var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                    <span wire:loading.remove wire:target="bulkSetVisibility">Ocultar</span>
                    <span wire:loading wire:target="bulkSetVisibility" style="display:inline-flex;align-items:center;gap:6px;">
                        <thinking-orb state="working" size="14"></thinking-orb>
                        <span>Actualizando...</span>
                    </span>
                </button>
                <button wire:click="deleteSelected"
                        wire:confirm="¿Estás seguro de eliminar los {{ count($selectedIds) }} caso(s) seleccionados? Esta acción es definitiva y no se puede deshacer."
                        wire:loading.attr="disabled"
                        class="btn btn-primary" style="padding:7px 16px;font-size:12px;background:#EF4444;border-color:#DC2626;gap:6px;display:inline-flex;align-items:center;">
                    <svg wire:loading.remove wire:target="deleteSelected" style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span wire:loading.remove wire:target="deleteSelected">Borrar seleccionados</span>
                    <span wire:loading wire:target="deleteSelected" style="display:inline-flex;align-items:center;gap:6px;">
                        <thinking-orb state="solving" size="14"></thinking-orb>
                        <span>Borrando...</span>
                    </span>
                </button>
            </div>
        </div>
    @endif

    <!-- Table -->
    <div class="crud-table-wrapper">
        <table class="crud-table">
            <thead>
                <tr>
                    <th style="width:40px;text-align:center;">
                        <input type="checkbox" wire:model.live="selectAll" style="width:16px;height:16px;accent-color:var(--red-primary);cursor:pointer;" title="Seleccionar todos">
                    </th>
                    <th style="width:60px;">Vista previa</th>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Métrica</th>
                    <th style="width:60px;text-align:center;">Orden</th>
                    <th style="width:80px;text-align:center;">Estado</th>
                    <th style="width:100px;text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Livewire Loading State -->
                <tr wire:loading wire:target="search,categoriaFiltro,previousPage,nextPage,gotoPage">
                    <td colspan="8" style="text-align:center;padding:36px;color:var(--text-muted);">
                        <thinking-orb state="working" size="24" label="Cargando casos de estudio..." pill></thinking-orb>
                    </td>
                </tr>

                @forelse($studies as $study)
                    <tr wire:key="study-{{ $study->id }}" wire:loading.remove wire:target="search,categoriaFiltro,previousPage,nextPage,gotoPage" style="{{ in_array((string)$study->id, $selectedIds) ? 'background:rgba(230,57,70,0.06);' : '' }}">
                        <!-- Checkbox Selección -->
                        <td style="text-align:center;">
                            <input type="checkbox" wire:model.live="selectedIds" value="{{ (string)$study->id }}" style="width:16px;height:16px;accent-color:var(--red-primary);cursor:pointer;">
                        </td>

                        <!-- Preview -->
                        <td>
                            @if($study->imagen)
                                <div style="width:48px;height:32px;border-radius:6px;overflow:hidden;border:1px solid var(--border-subtle);">
                                    <img src="{{ Storage::url($study->imagen) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                                </div>
                            @else
                                <div style="width:48px;height:32px;border-radius:6px;background:linear-gradient(135deg,{{ $study->gradient_inicio }},{{ $study->gradient_fin }});border:1px solid rgba(255,255,255,0.08);"></div>
                            @endif
                        </td>

                        <!-- Título + URL -->
                        <td>
                            <div class="crud-row-name">{{ $study->titulo }}</div>
                            @if($study->url_demo)
                                <div class="crud-row-meta">{{ $study->url_demo }}</div>
                            @endif
                        </td>

                        <!-- Categoría -->
                        <td>
                            @php
                                $catColors = [
                                    'web'       => 'background:rgba(99,102,241,0.15);color:#818cf8;',
                                    'ecommerce' => 'background:rgba(245,158,11,0.15);color:#fbbf24;',
                                    'branding'  => 'background:rgba(16,185,129,0.15);color:#34d399;',
                                    'social'    => 'background:rgba(236,72,153,0.15);color:#f472b6;',
                                ];
                                $catNames = ['web' => 'Página Web', 'ecommerce' => 'E-commerce', 'branding' => 'Branding', 'social' => 'Redes'];
                            @endphp
                            <span class="badge" style="{{ $catColors[$study->categoria] ?? '' }}">
                                {{ $catNames[$study->categoria] ?? $study->categoria }}
                            </span>
                        </td>

                        <!-- Métrica -->
                        <td>
                            @if($study->metrica_valor)
                                <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:var(--red-primary);font-size:15px;">{{ $study->metrica_valor }}</span>
                                @if($study->metrica_label)
                                    <div class="crud-row-meta">{{ $study->metrica_label }}</div>
                                @endif
                            @else
                                <span class="muted">—</span>
                            @endif
                        </td>

                        <!-- Orden -->
                        <td class="muted" style="text-align:center;">{{ $study->orden }}</td>

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
                            <button wire:click="edit({{ $study->id }})" class="action-btn" title="Editar">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
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
                        <td colspan="8" class="empty-state-cell">
                            <div class="empty-state-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:28px;height:28px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="empty-state-title">No hay casos de estudio</div>
                            <div class="empty-state-desc">Crea tu primer caso para que aparezca en el portafolio</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($studies->hasPages())
        <div class="crud-pagination">{{ $studies->links() }}</div>
    @endif
</div>
