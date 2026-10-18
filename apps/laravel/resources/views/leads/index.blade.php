<x-layouts.app>
    <style>
        .leads-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; flex-wrap: wrap; gap: 16px; }
        .leads-title { font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif; font-size: 28px; font-weight: 700; color: var(--text-primary); }
        .leads-subtitle { color: var(--text-muted); font-size: 14px; margin-top: 4px; }

        .stats-strip { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 24px; }
        .stat-box { background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 14px; padding: 18px; transition: all 0.2s ease; display: block; text-decoration: none; cursor: pointer; }
        .stat-box:hover { border-color: rgba(255, 255, 255, 0.25); transform: translateY(-2px); background: rgba(255, 255, 255, 0.04); }
        .stat-box.active { border-color: #FFFFFF !important; background: rgba(255, 255, 255, 0.08) !important; box-shadow: 0 4px 20px rgba(255, 255, 255, 0.12); }
        .stat-box-label { font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px; }
        .stat-box-val { font-family: 'Geist Mono', monospace; font-size: 28px; font-weight: 700; color: var(--text-primary); }

        .filters-bar { background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 14px; padding: 16px; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 12px; align-items: center; }
        .filter-input { background: rgba(0,0,0,0.4); border: 1px solid var(--border-default); border-radius: 10px; padding: 8px 14px; color: #fff; font-size: 13px; outline: none; }
        .filter-select { background: #18181b; border: 1px solid var(--border-default); border-radius: 10px; padding: 8px 14px; color: #fff; font-size: 13px; outline: none; cursor: pointer; }
        .btn-filter { background: #FFFFFF; color: #000; font-weight: 700; font-size: 13px; padding: 8px 18px; border-radius: 10px; border: none; cursor: pointer; }
        .btn-filter-clear { color: var(--text-muted); font-size: 13px; text-decoration: none; padding: 8px 12px; }

        .leads-card { background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 16px; overflow-x: auto; -webkit-overflow-scrolling: touch; max-width: 100%; }
        .leads-table { width: 100%; min-width: 760px; border-collapse: collapse; text-align: left; }
        .leads-table th { padding: 14px 18px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); font-weight: 600; border-bottom: 1px solid var(--border-subtle); background: rgba(255,255,255,0.02); }
        .leads-table td { padding: 16px 18px; border-bottom: 1px solid var(--border-subtle); font-size: 13px; color: var(--text-secondary); vertical-align: top; }
        .leads-table tr:hover td { background: rgba(255,255,255,0.02); }
        .lead-name { font-weight: 700; color: #fff; font-size: 14px; margin-bottom: 3px; }
        .lead-meta { font-size: 12px; color: var(--text-muted); }

        .badge-type { display: inline-block; padding: 3px 9px; border-radius: 6px; font-size: 11px; font-weight: 600; background: rgba(255,255,255,0.08); color: #fff; border: 1px solid rgba(255,255,255,0.12); }
        .badge-source { display: inline-block; padding: 3px 9px; border-radius: 6px; font-size: 11px; font-weight: 600; background: rgba(96,165,250,0.12); color: #93c5fd; border: 1px solid rgba(96,165,250,0.25); }

        .wa-btn { display: inline-flex; align-items: center; gap: 6px; background: rgba(37,211,102,0.15); color: #25D366; border: 1px solid rgba(37,211,102,0.3); padding: 5px 10px; border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none; transition: background 0.2s ease; }
        .wa-btn:hover { background: rgba(37,211,102,0.25); }
        .email-btn { display: inline-flex; align-items: center; gap: 5px; color: var(--text-muted); text-decoration: none; font-size: 12px; }
        .email-btn:hover { color: #fff; }

        .lead-message { max-width: 280px; font-size: 12px; line-height: 1.4; color: var(--text-muted); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .status-select-inline { background: #131316; border: 1px solid var(--border-default); border-radius: 8px; color: #fff; font-size: 12px; padding: 5px 9px; cursor: pointer; outline: none; font-weight: 600; }

        /* Action Buttons */
        .btn-action-quote {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(192, 132, 252, 0.14);
            color: #c084fc;
            border: 1px solid rgba(192, 132, 252, 0.35);
            padding: 6px 11px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-action-quote:hover {
            background: rgba(192, 132, 252, 0.25);
            border-color: rgba(192, 132, 252, 0.6);
            transform: translateY(-1px);
        }

        .btn-action-client {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(52, 211, 153, 0.14);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, 0.35);
            padding: 6px 11px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-action-client:hover {
            background: rgba(52, 211, 153, 0.25);
            border-color: rgba(52, 211, 153, 0.6);
            transform: translateY(-1px);
        }

        .btn-action-delete {
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .btn-action-delete:hover {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        @media (max-width: 640px) {
            .leads-title { font-size: 22px; }
            .stats-strip {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }
            .stats-strip .stat-box:first-child {
                grid-column: span 2;
            }
            .filters-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-input, .filter-select, .btn-filter {
                width: 100% !important;
                min-width: 0 !important;
            }
        }
    </style>

    <div class="leads-header">
        <div>
            <h1 class="leads-title">Prospectos & Solicitudes Web</h1>
            <p class="leads-subtitle">Monitoreo de leads capturados a través del sitio web con todos sus detalles de contacto y canal.</p>
        </div>
    </div>

    @if (session('status'))
        <div style="background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.3); color: #34d399; padding: 12px 16px; border-radius: 12px; margin-bottom: 20px; font-size: 13px; font-weight: 600;">
            ✓ {{ session('status') }}
        </div>
    @endif

    <!-- INTERACTIVE KPI STRIP (Filtros por pestaña) -->
    <div class="stats-strip">
        <a href="{{ route('leads.index') }}" class="stat-box {{ !request('estado') ? 'active' : '' }}" title="Ver todos los prospectos">
            <div class="stat-box-label">Total Leads</div>
            <div class="stat-box-val">{{ $stats['total'] }}</div>
        </a>
        <a href="{{ route('leads.index', ['estado' => 'nuevo']) }}" class="stat-box {{ request('estado') === 'nuevo' ? 'active' : '' }}" style="border-color: rgba(230,57,70,0.35);" title="Filtrar por Nuevos sin atender">
            <div class="stat-box-label" style="color: #ff6b77;">Nuevos (Sin atender)</div>
            <div class="stat-box-val" style="color: #ff6b77;">{{ $stats['nuevos'] }}</div>
        </a>
        <a href="{{ route('leads.index', ['estado' => 'contactado']) }}" class="stat-box {{ request('estado') === 'contactado' ? 'active' : '' }}" style="border-color: rgba(245,158,11,0.35);" title="Filtrar por Contactados">
            <div class="stat-box-label" style="color: #fbbf24;">En Contacto</div>
            <div class="stat-box-val" style="color: #fbbf24;">{{ $stats['contactados'] }}</div>
        </a>
        <a href="{{ route('leads.index', ['estado' => 'cotizado']) }}" class="stat-box {{ request('estado') === 'cotizado' ? 'active' : '' }}" style="border-color: rgba(192,132,252,0.35);" title="Filtrar por Cotizados">
            <div class="stat-box-label" style="color: #c084fc;">Cotizados</div>
            <div class="stat-box-val" style="color: #c084fc;">{{ $stats['cotizados'] }}</div>
        </a>
        <a href="{{ route('leads.index', ['estado' => 'ganado']) }}" class="stat-box {{ request('estado') === 'ganado' ? 'active' : '' }}" style="border-color: rgba(52,211,153,0.35);" title="Filtrar por Ganados">
            <div class="stat-box-label" style="color: #34d399;">Ganados / Clientes</div>
            <div class="stat-box-val" style="color: #34d399;">{{ $stats['ganados'] }}</div>
        </a>
    </div>

    <!-- FILTERS -->
    <form method="GET" action="{{ route('leads.index') }}" class="filters-bar">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, email, teléfono..." class="filter-input" style="flex: 1; min-width: 220px;">

        <select name="estado" class="filter-select" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            <option value="nuevo" @selected(request('estado') === 'nuevo')>🔴 Nuevos</option>
            <option value="contactado" @selected(request('estado') === 'contactado')>🟡 Contactados</option>
            <option value="cotizado" @selected(request('estado') === 'cotizado')>🟣 Cotizados</option>
            <option value="ganado" @selected(request('estado') === 'ganado')>🟢 Ganados</option>
            <option value="descartado" @selected(request('estado') === 'descartado')>⚪ Descartados</option>
        </select>

        <select name="tipo_cliente" class="filter-select" onchange="this.form.submit()">
            <option value="">Todos los perfiles</option>
            <option value="Empresa" @selected(strtolower(request('tipo_cliente')) === 'empresa')>Empresa</option>
            <option value="Negocio" @selected(strtolower(request('tipo_cliente')) === 'negocio')>Negocio</option>
            <option value="Persona" @selected(strtolower(request('tipo_cliente')) === 'persona')>Persona</option>
        </select>

        <button type="submit" class="btn-filter">Filtrar</button>
        @if(request()->hasAny(['search', 'estado', 'tipo_cliente']))
            <a href="{{ route('leads.index') }}" class="btn-filter-clear">Limpiar filtros</a>
        @endif
    </form>

    <!-- LEADS TABLE -->
    <div class="leads-card">
        @if($leads->isEmpty())
            <div style="padding: 64px 20px; text-align: center; color: var(--text-muted);">
                <svg style="width: 44px; height: 44px; margin: 0 auto 12px; color: var(--text-subtle);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p style="font-weight: 600; font-size: 15px; color: #fff;">No se encontraron prospectos con este filtro</p>
                <p style="font-size: 13px; margin-top: 4px;">Intenta cambiar el estado o limpiar los filtros de búsqueda.</p>
            </div>
        @else
            <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="leads-table">
                    <thead>
                        <tr>
                            <th>Prospecto</th>
                            <th>Perfil / Canal</th>
                            <th>Servicio & Presupuesto</th>
                            <th>Mensaje</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leads as $lead)
                            <tr>
                                <td>
                                    <div class="lead-name">{{ $lead->nombre }}</div>
                                    <div class="lead-meta" style="margin-bottom: 6px;">
                                        <a href="mailto:{{ $lead->email }}" class="email-btn">
                                            ✉ {{ $lead->email }}
                                        </a>
                                    </div>
                                    @if($lead->telefono)
                                        <div>
                                            @php
                                                $waDigits = preg_replace('/\D+/', '', $lead->telefono);
                                                $waText = urlencode("Hola {$lead->nombre}, te escribimos de Kosta Studio & Films con relación a tu proyecto de {$lead->servicio}. ¿Cómo estás?");
                                            @endphp
                                            <a href="https://wa.me/{{ $waDigits }}?text={{ $waText }}" target="_blank" rel="noopener" class="wa-btn" title="Chatear en WhatsApp con mensaje de bienvenida">
                                                <svg style="width: 14px; height: 14px;" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                                {{ $lead->telefono }}
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div style="margin-bottom: 6px;">
                                        <span class="badge-type">{{ $lead->tipo_cliente ?: 'No indicado' }}</span>
                                    </div>
                                    @if($lead->como_nos_conocio)
                                        <div>
                                            <span class="badge-source" title="Origen del prospecto">Vía {{ $lead->como_nos_conocio }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #fff;">{{ $lead->servicio }}</div>
                                    <div class="lead-meta" style="margin-top: 3px;">
                                        {{ $lead->presupuesto ?: 'Presupuesto sin definir' }}
                                    </div>
                                </td>
                                <td>
                                    @if($lead->mensaje)
                                        <div class="lead-message" title="{{ $lead->mensaje }}">
                                            {{ $lead->mensaje }}
                                        </div>
                                    @else
                                        <span class="lead-meta">—</span>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('leads.update-status', $lead) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="estado" class="status-select-inline" onchange="this.form.submit()" title="Cambiar estado del lead">
                                            <option value="nuevo" @selected($lead->estado === 'nuevo')>🔴 Nuevo</option>
                                            <option value="contactado" @selected($lead->estado === 'contactado')>🟡 En Contacto</option>
                                            <option value="cotizado" @selected($lead->estado === 'cotizado')>🟣 Cotizado</option>
                                            <option value="ganado" @selected($lead->estado === 'ganado')>🟢 Ganado</option>
                                            <option value="descartado" @selected($lead->estado === 'descartado')>⚪ Descartado</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <div style="font-size: 12px; color: var(--text-secondary);">
                                        {{ $lead->created_at?->format('d/m/Y') }}
                                    </div>
                                    <div class="lead-meta">
                                        {{ $lead->created_at?->format('H:i') }}
                                    </div>
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <div style="display: inline-flex; align-items: center; gap: 8px;">
                                        <!-- Cotizar (Crea cliente y abre cotización pre-llenada) -->
                                        <form method="POST" action="{{ route('leads.create-quote', $lead) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn-action-quote" title="Crear Cotización para este prospecto">
                                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                <span>Cotizar</span>
                                            </button>
                                        </form>

                                        <!-- Convertir en Cliente -->
                                        <form method="POST" action="{{ route('leads.convert-client', $lead) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn-action-client" title="Registrar directamente como Cliente ganado">
                                                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                                <span>A Cliente</span>
                                            </button>
                                        </form>

                                        <!-- Eliminar -->
                                        <form method="POST" action="{{ route('leads.destroy', $lead) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este prospecto?')" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-delete" title="Eliminar prospecto">
                                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($leads->hasPages())
                <div style="padding: 16px 20px; border-top: 1px solid var(--border-subtle);">
                    {{ $leads->links() }}
                </div>
            @endif
        @endif
    </div>
</x-layouts.app>
