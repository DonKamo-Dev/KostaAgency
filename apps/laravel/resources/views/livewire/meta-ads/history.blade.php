<div>
    <div class="crud-header">
        <div>
            <h1 class="crud-title">Historial de Cotizaciones IA</h1>
            <p class="crud-subtitle">Propuestas de pauta digital generadas con inteligencia artificial</p>
        </div>
        <a wire:navigate href="{{ \Illuminate\Support\Facades\Route::has('meta-ads.wizard') ? route('meta-ads.wizard') : '#' }}" class="btn btn-primary">
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
                        <td>
                            <span class="badge badge-pending">{{ $quote->campaign_type }}</span>
                            @php $platformLabels = ['META'=>'Meta','GOOGLE'=>'Google','BOTH'=>'Google+Meta']; @endphp
                            <span class="badge" style="background:rgba(59,130,246,0.1);color:#3b82f6;border:1px solid rgba(59,130,246,0.2);font-size:10px;padding:2px 7px;border-radius:20px;margin-left:3px;">
                                {{ $platformLabels[$quote->platform] ?? $quote->platform }}
                            </span>
                        </td>
                        <td style="font-weight:600;font-family:'Space Grotesk',sans-serif;">{{ $quote->budget_formatted }}</td>
                        <td class="muted">{{ $quote->duration_days }} días</td>
                        <td class="muted">{{ $quote->created_at->format('d/m/Y') }}</td>
                        <td style="text-align:right;white-space:nowrap;">
                            <a wire:navigate href="{{ route('meta-ads.view', $quote->id) }}" class="action-btn" title="Ver / Editar">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <a href="{{ \Illuminate\Support\Facades\Route::has('meta-ads.pdf') ? route('meta-ads.pdf', $quote->id) : '#' }}" target="_blank" class="action-btn" title="Exportar PDF">
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
