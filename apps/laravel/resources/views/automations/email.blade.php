<x-layouts.app>
    <style>
        .auto-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
        .auto-title { font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif; font-size: 28px; font-weight: 700; color: var(--text-primary); }
        .auto-subtitle { color: var(--text-muted); font-size: 14px; margin-top: 4px; }

        .auto-grid { display: grid; grid-template-columns: 1fr; gap: 24px; }
        @media (min-width: 1024px) {
            .auto-grid { grid-template-columns: 1.15fr 0.85fr; }
        }

        .auto-card { background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 18px; padding: 24px; margin-bottom: 24px; position: relative; overflow: hidden; }
        .auto-card-title { font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 4px; display: flex; align-items: center; gap: 10px; }
        .auto-card-desc { font-size: 13px; color: var(--text-muted); margin-bottom: 20px; line-height: 1.5; }

        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary); margin-bottom: 8px; }
        .form-input { width: 100%; background: rgba(0,0,0,0.5); border: 1px solid var(--border-default); border-radius: 12px; padding: 11px 14px; color: #fff; font-size: 14px; transition: border-color 0.2s ease; outline: none; box-sizing: border-box; }
        .form-input:focus { border-color: rgba(255, 255, 255, 0.4); background: rgba(0,0,0,0.7); }
        .form-hint { font-size: 12px; color: var(--text-muted); margin-top: 6px; }

        .toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle); border-radius: 12px; margin-bottom: 18px; }
        .toggle-text { font-size: 13px; font-weight: 600; color: #fff; }
        .toggle-subtext { font-size: 12px; color: var(--text-muted); margin-top: 2px; }

        .btn-primary { background: #FFFFFF; color: #000; font-weight: 700; font-size: 13px; padding: 11px 24px; border-radius: 10px; border: none; cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary:hover { background: #e4e4e7; transform: translateY(-1px); }

        .btn-secondary { background: rgba(255, 255, 255, 0.08); color: #fff; font-weight: 600; font-size: 13px; padding: 10px 20px; border-radius: 10px; border: 1px solid var(--border-subtle); cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-secondary:hover { background: rgba(255, 255, 255, 0.14); border-color: rgba(255, 255, 255, 0.25); }

        .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; font-family: 'Geist Mono', monospace; text-transform: uppercase; letter-spacing: 0.05em; }
        .status-badge.live { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .status-badge.warning { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }

        .alert-box { padding: 14px 18px; border-radius: 12px; margin-bottom: 24px; font-size: 13px; display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .alert-box.success { background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; }
        .alert-box.error { background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; }

        .logs-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .logs-table th { padding: 10px 12px; text-align: left; color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 10.5px; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-subtle); }
        .logs-table td { padding: 12px; border-bottom: 1px solid var(--border-subtle); color: var(--text-secondary); vertical-align: middle; }
    </style>

    <div style="max-width: 1400px; margin: 0 auto; padding: 24px 20px;">
        <!-- Header -->
        <div class="auto-header">
            <div>
                <h1 class="auto-title">Automatizaciones: Correo</h1>
                <p class="auto-subtitle">Enrutamiento de prospectos web a tu bandeja de correo personal mediante <strong>Resend</strong>.</p>
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
                <span class="status-badge {{ $mailInfo['has_resend_key'] ? 'live' : 'warning' }}">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background: currentColor;"></span>
                    Resend: {{ $mailInfo['has_resend_key'] ? 'Conectado' : 'Pendiente API Key' }}
                </span>
            </div>
        </div>

        <!-- Navigation Tabs -->
        @include('automations.nav-tabs')

        <!-- Flash Messages -->
        @if(session('status'))
            <div class="alert-box success">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
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

        <div class="auto-grid">
            <!-- Columna Izquierda: Configuración de Correo & Resend -->
            <div>
                <form action="{{ route('automations.email.update') }}" method="POST">
                    @csrf

                    <div class="auto-card">
                        <div class="auto-card-title">
                            <svg class="w-5 h-5 text-red-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>Configuración de Resend & Entrega de Correo</span>
                        </div>
                        <p class="auto-card-desc">Los correos se despachan a través de la infraestructura de Resend hacia tu bandeja de entrada.</p>

                        <div class="toggle-row">
                            <div>
                                <div class="toggle-text">Activar notificaciones por correo</div>
                                <div class="toggle-subtext">Envía un email cada vez que entra un nuevo prospecto en la web</div>
                            </div>
                            <label style="position: relative; display: inline-block; width: 44px; height: 24px; cursor: pointer;">
                                <input type="checkbox" name="automation_email_enabled" value="1" {{ $settings['automation_email_enabled'] ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;" onchange="this.nextElementSibling.style.background = this.checked ? '#FFFFFF' : 'rgba(255,255,255,0.2)'">
                                <span style="position: absolute; inset: 0; background: {{ $settings['automation_email_enabled'] ? '#FFFFFF' : 'rgba(255,255,255,0.2)' }}; border-radius: 999px; transition: 0.2s;">
                                    <span style="position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background: #000; border-radius: 50%; transition: 0.2s; transform: {{ $settings['automation_email_enabled'] ? 'translateX(20px)' : 'translateX(0)' }};"></span>
                                </span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="automation_email_recipient">Tu Correo de Destino (Bandeja Personal / Estudio)</label>
                            <input type="email" id="automation_email_recipient" name="automation_email_recipient" class="form-input" value="{{ old('automation_email_recipient', $settings['automation_email_recipient']) }}" required placeholder="tu-correo@kostastudio.com">
                            <div class="form-hint">Aquí recibirás los leads con los datos completos del cliente.</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="resend_api_key">API Key de Resend</label>
                            <div style="position: relative;">
                                <input type="password" id="resend_api_key" name="resend_api_key" class="form-input" value="{{ old('resend_api_key', $settings['resend_api_key']) }}" placeholder="re_123456789_abcdef...">
                                <button type="button" onclick="const f=document.getElementById('resend_api_key'); f.type = f.type==='password'?'text':'password';" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #a1a1aa; cursor: pointer; font-size: 11px; text-transform: uppercase; font-family: monospace;">Ver</button>
                            </div>
                            <div class="form-hint">Generada en <a href="https://resend.com/api-keys" target="_blank" style="color: #60a5fa; text-decoration: underline;">resend.com/api-keys</a>.</div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                            <div class="form-group">
                                <label class="form-label" for="resend_from_email">Remitente (From)</label>
                                <input type="email" id="resend_from_email" name="resend_from_email" class="form-input" value="{{ old('resend_from_email', $settings['resend_from_email']) }}" placeholder="hola@kostastudio.com">
                                <div class="form-hint">Tu dominio en Resend (o <code>onboarding@resend.dev</code> para pruebas).</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="resend_from_name">Nombre Remitente</label>
                                <input type="text" id="resend_from_name" name="resend_from_name" class="form-input" value="{{ old('resend_from_name', $settings['resend_from_name']) }}" placeholder="Kosta Studio">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="automation_email_cc">Copia de respaldo (CC opcional)</label>
                            <input type="text" id="automation_email_cc" name="automation_email_cc" class="form-input" value="{{ old('automation_email_cc', $settings['automation_email_cc']) }}" placeholder="socio@kostastudio.com, backup@gmail.com">
                            <div class="form-hint">Separa múltiples correos con comas.</div>
                        </div>

                        <div class="toggle-row" style="margin-bottom: 0;">
                            <div>
                                <div class="toggle-text">Auto-responder de cortesía al cliente</div>
                                <div class="toggle-subtext">Envía confirmación con estética Kosta a quien escribió en la web</div>
                            </div>
                            <label style="position: relative; display: inline-block; width: 44px; height: 24px; cursor: pointer;">
                                <input type="checkbox" name="automation_auto_responder_enabled" value="1" {{ $settings['automation_auto_responder_enabled'] ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;" onchange="this.nextElementSibling.style.background = this.checked ? '#FFFFFF' : 'rgba(255,255,255,0.2)'">
                                <span style="position: absolute; inset: 0; background: {{ $settings['automation_auto_responder_enabled'] ? '#FFFFFF' : 'rgba(255,255,255,0.2)' }}; border-radius: 999px; transition: 0.2s;">
                                    <span style="position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background: #000; border-radius: 50%; transition: 0.2s; transform: {{ $settings['automation_auto_responder_enabled'] ? 'translateX(20px)' : 'translateX(0)' }};"></span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-bottom: 24px;">
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Guardar Configuración de Correo</span>
                        </button>
                    </div>
                </form>

                <!-- Pruebas en Vivo -->
                <div class="auto-card">
                    <div class="auto-card-title">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Prueba en Tiempo Real con Resend</span>
                    </div>
                    <p class="auto-card-desc">Envía un correo de prueba ahora mismo a tu bandeja para validar que Resend entrega correctamente.</p>

                    <form action="{{ route('automations.test-email') }}" method="POST">
                        @csrf
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <button type="submit" class="btn-secondary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span>Enviar Correo de Prueba Ahora</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Columna Derecha: Guía de Render e Historial de Envíos -->
            <div>
                <!-- Variables para Render -->
                <div class="auto-card">
                    <div class="auto-card-title">
                        <span>🚀 Variables para Render Dashboard</span>
                    </div>
                    <p class="auto-card-desc">Copia y pega estas variables en tu servicio de Render (Environment Variables) para dejarlas fijas en producción:</p>

                    <div style="background: rgba(0,0,0,0.6); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 16px; font-family: 'Geist Mono', monospace; font-size: 11.5px; line-height: 1.8; color: #a1a1aa; overflow-x: auto;">
                        <span style="color: #60a5fa;"># CONFIGURACIÓN RESEND EN RENDER</span><br>
                        RESEND_API_KEY=re_tu_api_key_aqui<br>
                        MAIL_FROM_ADDRESS=hola@kostastudio.com<br>
                        MAIL_FROM_NAME="Kosta Studio"<br>
                        KOSTA_LEAD_NOTIFICATION_EMAIL=tu-correo@kostastudio.com
                    </div>

                    <div style="margin-top: 14px; font-size: 12px; color: var(--text-muted); line-height: 1.6;">
                        <strong style="color: #fff;">Tip:</strong> Al guardar la API Key en el formulario de la izquierda, queda guardada en la base de datos de producción inmediatamente.
                    </div>
                </div>

                <!-- Historial de Correos Recientes -->
                <div class="auto-card">
                    <div class="auto-card-title">
                        <span>📋 Últimos Correos Despachados</span>
                    </div>
                    <p class="auto-card-desc">Historial de envíos de leads procesados vía Resend:</p>

                    @if($recentNotifications->isEmpty())
                        <div style="text-align: center; padding: 24px 0; color: var(--text-muted); font-size: 13px;">
                            No hay correos registrados todavía.
                        </div>
                    @else
                        <div style="overflow-x: auto;">
                            <table class="logs-table">
                                <thead>
                                    <tr>
                                        <th>Lead</th>
                                        <th>Estado Resend</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentNotifications as $leadItem)
                                        @php
                                            $notifs = $leadItem->metadata['notifications'] ?? [];
                                            $emailStatus = $notifs['email']['status'] ?? 'pending';
                                            $resendId = $notifs['email']['resend_id'] ?? null;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div style="font-weight: 700; color: #fff;">{{ $leadItem->nombre }}</div>
                                                <div style="color: var(--text-muted); font-size: 11px;">{{ $leadItem->servicio }}</div>
                                            </td>
                                            <td>
                                                @if($emailStatus === 'sent')
                                                    <span style="color: #34d399; font-weight: 600;">✓ Entregado</span>
                                                    @if($resendId)
                                                        <div style="color: var(--text-muted); font-size: 9.5px; font-family: monospace;">{{ Str::limit($resendId, 16) }}</div>
                                                    @endif
                                                @elseif($emailStatus === 'failed')
                                                    <span style="color: #f87171; font-weight: 600;" title="{{ $notifs['email']['error'] ?? '' }}">✕ Falló</span>
                                                @else
                                                    <span style="color: #a1a1aa;">{{ ucfirst($emailStatus) }}</span>
                                                @endif
                                            </td>
                                            <td style="color: var(--text-muted); font-size: 11px;">
                                                {{ $leadItem->created_at?->format('d/m H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
