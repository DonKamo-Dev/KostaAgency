<x-layouts.app>
    <style>
        .auto-page { max-width: 1380px; margin: 0 auto; padding: 20px; }
        .auto-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px; }
        .auto-title { font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif; font-size: 24px; font-weight: 700; color: var(--text-primary); letter-spacing: -0.02em; margin: 0; }
        .auto-subtitle { color: var(--text-muted); font-size: 13.5px; margin-top: 3px; }

        /* Sub-navigation segmented bar */
        .subnav-bar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 22px; border-bottom: 1px solid var(--border-subtle); padding-bottom: 14px; flex-wrap: wrap; }
        .subnav-tabs { display: inline-flex; gap: 6px; background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle); padding: 4px; border-radius: 12px; flex-wrap: wrap; }
        .subnav-btn { background: transparent; border: none; padding: 9px 16px; border-radius: 9px; font-size: 13px; font-weight: 600; color: var(--text-muted); cursor: pointer; transition: all 0.18s ease; display: inline-flex; align-items: center; gap: 8px; }
        .subnav-btn:hover { color: #fff; background: rgba(255,255,255,0.04); }
        .subnav-btn.active { background: rgba(255,255,255,0.1); color: #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.3); }
        .subnav-dot { width: 7px; height: 7px; border-radius: 50%; }
        .subnav-dot.on { background: #25D366; box-shadow: 0 0 8px rgba(37,211,102,0.5); }
        .subnav-dot.off { background: #71717a; }

        .auto-grid { display: grid; grid-template-columns: 1fr; gap: 22px; align-items: start; }
        @media (min-width: 1024px) {
            .auto-grid { grid-template-columns: 1.08fr 0.92fr; }
        }

        .auto-card { background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 18px; padding: 24px; position: relative; }
        .auto-card-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; padding-bottom: 16px; margin-bottom: 18px; border-bottom: 1px solid var(--border-subtle); }
        .auto-card-title { font-size: 16px; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 8px; margin: 0; }
        .auto-card-desc { font-size: 12.5px; color: var(--text-muted); margin: 4px 0 0 0; line-height: 1.45; }

        .form-group { margin-bottom: 15px; }
        .form-label { display: block; font-size: 11.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--text-secondary); margin-bottom: 6px; }
        .form-input { width: 100%; background: rgba(0,0,0,0.45); border: 1px solid var(--border-default); border-radius: 10px; padding: 10px 13px; color: #fff; font-size: 13.5px; transition: all 0.18s ease; outline: none; box-sizing: border-box; font-family: inherit; }
        .form-input:focus { border-color: rgba(37, 211, 102, 0.55); background: rgba(0,0,0,0.7); box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.1); }
        .form-hint { font-size: 11.5px; color: var(--text-muted); margin-top: 5px; }

        .switch-pill { display: inline-flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.03); border: 1px solid var(--border-subtle); padding: 6px 12px; border-radius: 999px; cursor: pointer; user-select: none; flex-shrink: 0; transition: border-color 0.2s ease; }
        .switch-pill:hover { border-color: rgba(255,255,255,0.2); }
        .switch-pill-label { font-size: 12px; font-weight: 600; color: #e4e4e7; }
        .switch-track { position: relative; display: inline-block; width: 36px; height: 20px; background: rgba(255,255,255,0.18); border-radius: 999px; transition: background 0.2s ease; flex-shrink: 0; }
        .switch-track.on { background: #25D366; }
        .switch-thumb { position: absolute; height: 14px; width: 14px; left: 3px; top: 3px; background: #ffffff; border-radius: 50%; transition: transform 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.4); }
        .switch-track.on .switch-thumb { transform: translateX(16px); }

        /* Collapsible Advanced Options */
        .advanced-details { background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 12px 14px; margin-top: 16px; }
        .advanced-summary { font-size: 12.5px; font-weight: 600; color: var(--text-secondary); cursor: pointer; user-select: none; display: flex; align-items: center; justify-content: space-between; list-style: none; }
        .advanced-summary::-webkit-details-marker { display: none; }

        .card-footer-bar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-subtle); }

        .btn-primary { background: #FFFFFF; color: #000; font-weight: 700; font-size: 13px; padding: 10px 20px; border-radius: 10px; border: none; cursor: pointer; transition: all 0.18s ease; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary:hover { background: #e4e4e7; transform: translateY(-1px); }

        .btn-wa { background: rgba(37, 211, 102, 0.16); color: #25D366; border: 1px solid rgba(37, 211, 102, 0.35); font-weight: 700; font-size: 13px; padding: 10px 18px; border-radius: 10px; cursor: pointer; transition: all 0.18s ease; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-wa:hover { background: rgba(37, 211, 102, 0.25); transform: translateY(-1px); }

        /* Realistic WhatsApp Chat Shell */
        .wa-chat-shell { border: 1px solid var(--border-subtle); border-radius: 18px; overflow: hidden; background: #0b141a; box-shadow: 0 12px 32px rgba(0,0,0,0.45); position: sticky; top: 20px; }
        .wa-chat-topbar { background: #202c33; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.06); }
        .wa-avatar { width: 34px; height: 34px; border-radius: 50%; background: #25D366; color: #0b141a; font-weight: 800; font-size: 14px; display: flex; align-items: center; justify-content: center; }
        .wa-chat-body { padding: 20px; background: radial-gradient(circle at center, rgba(37,211,102,0.03) 0%, #0b141a 100%); }
        .wa-bubble { background: #005c4b; color: #e9edef; border-radius: 12px 0 12px 12px; padding: 14px 16px; font-size: 12.5px; line-height: 1.55; white-space: pre-wrap; word-break: break-word; box-shadow: 0 2px 6px rgba(0,0,0,0.35); position: relative; }
        .wa-bubble-meta { display: flex; align-items: center; justify-content: flex-end; gap: 4px; font-size: 10.5px; color: rgba(233,237,239,0.65); margin-top: 6px; }
        .wa-chat-footer { background: #111b21; padding: 14px 16px; border-top: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }

        .alert-box { padding: 12px 16px; border-radius: 12px; margin-bottom: 18px; font-size: 13px; display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .alert-box.success { background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; }
        .alert-box.error { background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; }

        .logs-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
        .logs-table th { padding: 10px 12px; text-align: left; color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 10.5px; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-subtle); }
        .logs-table td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.04); color: var(--text-secondary); vertical-align: middle; }
    </style>

    <div class="auto-page"
         x-data="{
            activeTab: '{{ request('tab') === 'logs' ? 'logs' : 'config' }}',
            waEnabled: {{ $settings['automation_whatsapp_enabled'] ? 'true' : 'false' }},
            waPhone: @js(old('automation_whatsapp_number', $settings['automation_whatsapp_number']))
         }">

        <!-- Top Header + Channel Switcher -->
        <div class="auto-header">
            <div>
                <h1 class="auto-title">Automatizaciones de WhatsApp</h1>
                <p class="auto-subtitle">Recibe alertas instantáneas en tu celular con <strong>Meta WhatsApp Cloud API</strong>.</p>
            </div>
            @include('automations.nav-tabs')
        </div>

        <!-- Flash Messages -->
        @if(session('status'))
            <div class="alert-box success">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
                @if(session('open_whatsapp_url'))
                    <a href="{{ session('open_whatsapp_url') }}" target="_blank" class="btn-wa" style="padding: 6px 14px; font-size: 12px;">Abrir WhatsApp Ahora ↗</a>
                @endif
            </div>
        @endif

        @if(session('error'))
            <div class="alert-box error">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Clean Sub-Navigation Tabs -->
        <div class="subnav-bar">
            <div class="subnav-tabs">
                <button type="button" class="subnav-btn" :class="{ 'active': activeTab === 'config' }" @click="activeTab = 'config'">
                    <span class="subnav-dot" :class="waEnabled ? 'on' : 'off'"></span>
                    <span>1. Conexión Meta & Vista Previa</span>
                </button>

                <button type="button" class="subnav-btn" :class="{ 'active': activeTab === 'logs' }" @click="activeTab = 'logs'">
                    <svg style="width: 15px; height: 15px; opacity: 0.8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>2. Historial de Alertas & Variables Render</span>
                </button>
            </div>

            <div style="font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                <span style="width: 7px; height: 7px; border-radius: 50%; background: {{ $whatsappInfo['has_meta'] ? '#10B981' : '#F59E0B' }};"></span>
                <span>Meta Cloud API: <strong style="color: #fff;">{{ $whatsappInfo['has_meta'] ? 'Conectado' : 'Pendiente Credenciales' }}</strong></span>
            </div>
        </div>

        <!-- ==================== PESTAÑA 1: CONFIGURACIÓN & PREVIEW ==================== -->
        <div class="auto-grid" x-show="activeTab === 'config'">
            <div>
                <form action="{{ route('automations.whatsapp.update') }}" method="POST">
                    @csrf
                    <div class="auto-card">
                        <div class="auto-card-header">
                            <div>
                                <div class="auto-card-title">
                                    <svg class="w-5 h-5" style="color: #25D366;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    <span>Credenciales de Meta WhatsApp</span>
                                </div>
                                <p class="auto-card-desc">Conecta tu aplicación de Meta for Developers para despachar leads en tiempo real.</p>
                            </div>

                            <label class="switch-pill" title="Activar o pausar alertas de WhatsApp">
                                <input type="checkbox" name="automation_whatsapp_enabled" value="1" x-model="waEnabled" style="display: none;">
                                <span class="switch-pill-label" x-text="waEnabled ? 'Activo' : 'Pausado'"></span>
                                <span class="switch-track" :class="{ 'on': waEnabled }">
                                    <span class="switch-thumb"></span>
                                </span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="automation_whatsapp_number">Número Receptor (Con código de país, sin +)</label>
                            <input type="text" id="automation_whatsapp_number" name="automation_whatsapp_number" x-model="waPhone" class="form-input" required placeholder="573113894136">
                            <div class="form-hint">Ejemplo Colombia: <code>573113894136</code></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="whatsapp_meta_phone_number_id">Meta Phone Number ID</label>
                            <input type="text" id="whatsapp_meta_phone_number_id" name="whatsapp_meta_phone_number_id" class="form-input" value="{{ old('whatsapp_meta_phone_number_id', $settings['whatsapp_meta_phone_number_id']) }}" placeholder="105934529384729">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="whatsapp_meta_access_token">Meta Access Token (Permanente)</label>
                            <div style="position: relative;">
                                <input type="password" id="whatsapp_meta_access_token" name="whatsapp_meta_access_token" class="form-input" value="{{ old('whatsapp_meta_access_token', $settings['whatsapp_meta_access_token']) }}" placeholder="EAAGm0PX4ZCBO...">
                                <button type="button" onclick="const f=document.getElementById('whatsapp_meta_access_token'); f.type = f.type==='password'?'text':'password';" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #a1a1aa; cursor: pointer; font-size: 11px; text-transform: uppercase; font-family: monospace;">Mostrar</button>
                            </div>
                        </div>

                        <!-- Opciones avanzadas en acordeón limpio -->
                        <details class="advanced-details">
                            <summary class="advanced-summary">
                                <span>⚙️ Opciones avanzadas (WABA ID & Webhook externo Make/n8n)</span>
                                <span style="font-size: 11px; color: var(--text-muted);">Mostrar ▾</span>
                            </summary>
                            <div style="margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--border-subtle);">
                                <div class="form-group">
                                    <label class="form-label" for="whatsapp_meta_business_account_id">WhatsApp Business Account ID (WABA ID - Opcional)</label>
                                    <input type="text" id="whatsapp_meta_business_account_id" name="whatsapp_meta_business_account_id" class="form-input" value="{{ old('whatsapp_meta_business_account_id', $settings['whatsapp_meta_business_account_id']) }}" placeholder="102938475610293">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" for="automation_whatsapp_webhook_url">Webhook Adicional (Make, Zapier o n8n)</label>
                                    <input type="url" id="automation_whatsapp_webhook_url" name="automation_whatsapp_webhook_url" class="form-input" value="{{ old('automation_whatsapp_webhook_url', $settings['automation_whatsapp_webhook_url']) }}" placeholder="https://hook.make.com/...">
                                </div>
                            </div>
                        </details>

                        <div class="card-footer-bar">
                            <span style="font-size: 12px; color: var(--text-muted);">Conexión directa vía Graph API v21.0</span>
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Guardar Configuración</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Columna Derecha: Simulador de Chat WhatsApp + Botón de Prueba Integrado -->
            <div>
                <div class="wa-chat-shell">
                    <div class="wa-chat-topbar">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div class="wa-avatar">K</div>
                            <div>
                                <div style="font-size: 13.5px; font-weight: 700; color: #e9edef;">Kosta Studio Bot</div>
                                <div style="font-size: 11px; color: #8696a0;">Destino: +<span x-text="waPhone || '573113894136'"></span> · en línea</div>
                            </div>
                        </div>
                        <span style="font-size: 11px; color: #25D366; background: rgba(37,211,102,0.12); padding: 3px 8px; border-radius: 6px; font-weight: 600;">Vista Previa</span>
                    </div>

                    <div class="wa-chat-body">
                        <div class="wa-bubble">{!! nl2br(e($sampleWaMessage)) !!}<div class="wa-bubble-meta"><span>{{ now()->format('H:i') }}</span><span style="color: #53bdeb; font-weight: 700;">✓✓</span></div></div>
                    </div>

                    <div class="wa-chat-footer">
                        <span style="font-size: 12px; color: #8696a0;">Envía este mensaje de prueba a tu celular:</span>
                        <form action="{{ route('automations.test-whatsapp') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="btn-wa">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span>Probar WhatsApp Ahora</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== PESTAÑA 2: HISTORIAL & RENDER ==================== -->
        <div class="auto-grid" x-show="activeTab === 'logs'" style="display: none;">
            <div class="auto-card">
                <div class="auto-card-header" style="margin-bottom: 12px; padding-bottom: 12px;">
                    <div>
                        <div class="auto-card-title">
                            <span>📋 Últimas Alertas de WhatsApp</span>
                        </div>
                        <p class="auto-card-desc">Registro reciente de prospectos notificados por WhatsApp.</p>
                    </div>
                </div>

                @if($recentNotifications->isEmpty())
                    <div style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 13px;">
                        No hay alertas registradas todavía.
                    </div>
                @else
                    <div style="overflow-x: auto;">
                        <table class="logs-table">
                            <thead>
                                <tr>
                                    <th>Prospecto</th>
                                    <th>Estado WhatsApp</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentNotifications as $leadItem)
                                    @php
                                        $notifs = $leadItem->metadata['notifications'] ?? [];
                                        $waStatus = $notifs['whatsapp']['status'] ?? 'pending';
                                        $metaInfo = $notifs['whatsapp']['meta_cloud'] ?? [];
                                    @endphp
                                    <tr>
                                        <td>
                                            <div style="font-weight: 700; color: #fff;">{{ $leadItem->nombre }}</div>
                                            <div style="color: var(--text-muted); font-size: 11px;">{{ $leadItem->servicio }}</div>
                                        </td>
                                        <td>
                                            @if($waStatus === 'sent_meta_cloud')
                                                <span style="color: #34d399; font-weight: 600;">✓ Meta API OK</span>
                                                @if(!empty($metaInfo['message_id']))
                                                    <div style="color: var(--text-muted); font-size: 10px; font-family: monospace;">{{ \Illuminate\Support\Str::limit($metaInfo['message_id'], 16) }}</div>
                                                @endif
                                            @elseif(in_array($waStatus, ['sent', 'prepared', 'sent_webhook'], true))
                                                <a href="{{ $notifs['whatsapp']['direct_alert_url'] ?? '#' }}" target="_blank" style="color: #25D366; text-decoration: none; font-weight: 600;">
                                                    ✓ Enlace listo ↗
                                                </a>
                                            @else
                                                <span style="color: #a1a1aa;">{{ ucfirst($waStatus) }}</span>
                                            @endif
                                        </td>
                                        <td style="color: var(--text-muted); font-size: 11.5px; white-space: nowrap;">
                                            {{ $leadItem->created_at?->format('d/m H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="auto-card">
                <div class="auto-card-header" style="margin-bottom: 14px; padding-bottom: 12px;">
                    <div>
                        <div class="auto-card-title">
                            <span>⚙️ Variables de Entorno (Render)</span>
                        </div>
                        <p class="auto-card-desc">Variables opcionales para fijar credenciales en el servidor de producción:</p>
                    </div>
                </div>

                <div style="background: rgba(0,0,0,0.55); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 16px; font-family: 'Geist Mono', monospace; font-size: 12px; line-height: 1.85; color: #d4d4d8; overflow-x: auto;">
                    <span style="color: #34d399;"># META WHATSAPP CLOUD API</span><br>
                    WHATSAPP_PHONE_NUMBER_ID=tu_phone_number_id<br>
                    WHATSAPP_ACCESS_TOKEN=tu_access_token_de_meta<br>
                    KOSTA_WHATSAPP_NUMBER=573113894136
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
