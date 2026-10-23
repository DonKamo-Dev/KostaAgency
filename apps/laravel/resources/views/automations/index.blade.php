<x-layouts.app>
    <style>
        .auto-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; flex-wrap: wrap; gap: 16px; }
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

        .btn-secondary { background: rgba(255, 255, 255, 0.08); color: #fff; font-weight: 600; font-size: 13px; padding: 9px 18px; border-radius: 10px; border: 1px solid var(--border-subtle); cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
        .btn-secondary:hover { background: rgba(255, 255, 255, 0.14); border-color: rgba(255, 255, 255, 0.25); }

        .btn-wa { background: rgba(37, 211, 102, 0.15); color: #25D366; border: 1px solid rgba(37, 211, 102, 0.35); font-weight: 700; font-size: 13px; padding: 9px 18px; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-wa:hover { background: rgba(37, 211, 102, 0.25); }

        .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; font-family: 'Geist Mono', monospace; text-transform: uppercase; letter-spacing: 0.05em; }
        .status-badge.live { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .status-badge.warning { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }

        .wa-preview { background: #0b141a; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; position: relative; }
        .wa-bubble { background: #005c4b; color: #e9edef; border-radius: 12px 0 12px 12px; padding: 14px 16px; font-size: 12.5px; line-height: 1.6; white-space: pre-wrap; word-break: break-word; box-shadow: 0 1px 2px rgba(0,0,0,0.3); }

        .alert-box { padding: 14px 18px; border-radius: 12px; margin-bottom: 24px; font-size: 13px; display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .alert-box.success { background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; }
        .alert-box.error { background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; }
        .alert-box.info { background: rgba(59, 130, 246, 0.12); border: 1px solid rgba(59, 130, 246, 0.3); color: #93c5fd; }

        .logs-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .logs-table th { padding: 10px 12px; text-align: left; color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 10.5px; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-subtle); }
        .logs-table td { padding: 12px; border-bottom: 1px solid var(--border-subtle); color: var(--text-secondary); vertical-align: middle; }

        .service-pill { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; }
        .service-pill.resend { background: rgba(255, 255, 255, 0.1); color: #fff; border: 1px solid rgba(255, 255, 255, 0.2); }
        .service-pill.meta { background: rgba(24, 119, 242, 0.15); color: #60a5fa; border: 1px solid rgba(24, 119, 242, 0.3); }
    </style>

    <div style="max-width: 1400px; margin: 0 auto; padding: 24px 20px;">
        <!-- Header -->
        <div class="auto-header">
            <div>
                <h1 class="auto-title">Automatizaciones</h1>
                <p class="auto-subtitle">Enrutamiento de leads con <strong>Resend</strong> (Correo) y <strong>Meta Business Developer</strong> (WhatsApp Cloud API).</p>
            </div>
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <span class="status-badge {{ $mailInfo['has_resend_key'] ? 'live' : 'warning' }}">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background: currentColor;"></span>
                    Resend: {{ $mailInfo['has_resend_key'] ? 'Conectado' : 'Sin API Key' }}
                </span>
                <span class="status-badge {{ $whatsappInfo['has_meta'] ? 'live' : 'warning' }}">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background: currentColor;"></span>
                    Meta WhatsApp: {{ $whatsappInfo['has_meta'] ? 'Activo' : 'Pendiente' }}
                </span>
            </div>
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

        <div class="auto-grid">
            <!-- Columna Izquierda: Formularios de Configuración -->
            <div>
                <form action="{{ route('automations.update') }}" method="POST">
                    @csrf

                    <!-- 1. RESEND (CORREO ELECTRÓNICO) -->
                    <div class="auto-card">
                        <div class="auto-card-title">
                            <span class="service-pill resend">Resend</span>
                            <span>1. Notificaciones por Correo a tu Bandeja</span>
                        </div>
                        <p class="auto-card-desc">Los correos se despachan a través de la API de Resend para garantizar entrega en la bandeja principal de entrada.</p>

                        <div class="toggle-row">
                            <div>
                                <div class="toggle-text">Activar alertas por correo electrónico</div>
                                <div class="toggle-subtext">Envía notificación instantánea cada vez que entra un lead</div>
                            </div>
                            <label style="position: relative; display: inline-block; width: 44px; height: 24px; cursor: pointer;">
                                <input type="checkbox" name="automation_email_enabled" value="1" {{ $settings['automation_email_enabled'] ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;" onchange="this.nextElementSibling.style.background = this.checked ? '#FFFFFF' : 'rgba(255,255,255,0.2)'">
                                <span style="position: absolute; inset: 0; background: {{ $settings['automation_email_enabled'] ? '#FFFFFF' : 'rgba(255,255,255,0.2)' }}; border-radius: 999px; transition: 0.2s;">
                                    <span style="position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background: #000; border-radius: 50%; transition: 0.2s; transform: {{ $settings['automation_email_enabled'] ? 'translateX(20px)' : 'translateX(0)' }};"></span>
                                </span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="automation_email_recipient">Tu Correo de Destino (Bandeja Personal)</label>
                            <input type="email" id="automation_email_recipient" name="automation_email_recipient" class="form-input" value="{{ old('automation_email_recipient', $settings['automation_email_recipient']) }}" required placeholder="tu-correo@kostastudio.com">
                            <div class="form-hint">Aquí recibirás los leads con los datos completos del cliente.</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="resend_api_key">API Key de Resend (Producción)</label>
                            <div style="position: relative;">
                                <input type="password" id="resend_api_key" name="resend_api_key" class="form-input" value="{{ old('resend_api_key', $settings['resend_api_key']) }}" placeholder="re_123456789_abcdef...">
                                <button type="button" onclick="const f=document.getElementById('resend_api_key'); f.type = f.type==='password'?'text':'password';" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #a1a1aa; cursor: pointer; font-size: 11px; text-transform: uppercase; font-family: monospace;">Ver</button>
                            </div>
                            <div class="form-hint">Obtén tu API key en <a href="https://resend.com/api-keys" target="_blank" style="color: #60a5fa; text-decoration: underline;">resend.com/api-keys</a>. También puedes fijarla en Render como <code>RESEND_API_KEY</code>.</div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                            <div class="form-group">
                                <label class="form-label" for="resend_from_email">Remitente (From)</label>
                                <input type="email" id="resend_from_email" name="resend_from_email" class="form-input" value="{{ old('resend_from_email', $settings['resend_from_email']) }}" placeholder="hola@kostastudio.com">
                                <div class="form-hint">Debe ser un dominio verificado en Resend o <code>onboarding@resend.dev</code> en pruebas.</div>
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
                                <div class="toggle-subtext">Responde automáticamente al prospecto confirmando que Kosta revisará su solicitud</div>
                            </div>
                            <label style="position: relative; display: inline-block; width: 44px; height: 24px; cursor: pointer;">
                                <input type="checkbox" name="automation_auto_responder_enabled" value="1" {{ $settings['automation_auto_responder_enabled'] ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;" onchange="this.nextElementSibling.style.background = this.checked ? '#FFFFFF' : 'rgba(255,255,255,0.2)'">
                                <span style="position: absolute; inset: 0; background: {{ $settings['automation_auto_responder_enabled'] ? '#FFFFFF' : 'rgba(255,255,255,0.2)' }}; border-radius: 999px; transition: 0.2s;">
                                    <span style="position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background: #000; border-radius: 50%; transition: 0.2s; transform: {{ $settings['automation_auto_responder_enabled'] ? 'translateX(20px)' : 'translateX(0)' }};"></span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- 2. META BUSINESS DEVELOPER (WHATSAPP CLOUD API) -->
                    <div class="auto-card">
                        <div class="auto-card-title">
                            <span class="service-pill meta">Meta Business</span>
                            <span>2. Alertas por WhatsApp Cloud API</span>
                        </div>
                        <p class="auto-card-desc">Conecta la API oficial de WhatsApp Cloud de Meta for Developers para recibir alertas directas en tu teléfono.</p>

                        <div class="toggle-row">
                            <div>
                                <div class="toggle-text">Activar alertas de WhatsApp</div>
                                <div class="toggle-subtext">Envía la ficha del lead a tu WhatsApp mediante Meta Cloud API</div>
                            </div>
                            <label style="position: relative; display: inline-block; width: 44px; height: 24px; cursor: pointer;">
                                <input type="checkbox" name="automation_whatsapp_enabled" value="1" {{ $settings['automation_whatsapp_enabled'] ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;" onchange="this.nextElementSibling.style.background = this.checked ? '#25D366' : 'rgba(255,255,255,0.2)'">
                                <span style="position: absolute; inset: 0; background: {{ $settings['automation_whatsapp_enabled'] ? '#25D366' : 'rgba(255,255,255,0.2)' }}; border-radius: 999px; transition: 0.2s;">
                                    <span style="position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background: #000; border-radius: 50%; transition: 0.2s; transform: {{ $settings['automation_whatsapp_enabled'] ? 'translateX(20px)' : 'translateX(0)' }};"></span>
                                </span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="automation_whatsapp_number">Tu Número de WhatsApp Receptor (Con código de país)</label>
                            <input type="text" id="automation_whatsapp_number" name="automation_whatsapp_number" class="form-input" value="{{ old('automation_whatsapp_number', $settings['automation_whatsapp_number']) }}" required placeholder="573113894136">
                            <div class="form-hint">Ejemplo: <code>573113894136</code> (Colombia). Sin signos +, espacios ni guiones.</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="whatsapp_meta_phone_number_id">Meta Phone Number ID (Identificador del Número)</label>
                            <input type="text" id="whatsapp_meta_phone_number_id" name="whatsapp_meta_phone_number_id" class="form-input" value="{{ old('whatsapp_meta_phone_number_id', $settings['whatsapp_meta_phone_number_id']) }}" placeholder="105934529384729">
                            <div class="form-hint">Lo encuentras en Meta for Developers > WhatsApp > API Setup > <em>Phone number ID</em>.</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="whatsapp_meta_access_token">Meta Access Token (Token de Acceso Permanente o Temporal)</label>
                            <div style="position: relative;">
                                <input type="password" id="whatsapp_meta_access_token" name="whatsapp_meta_access_token" class="form-input" value="{{ old('whatsapp_meta_access_token', $settings['whatsapp_meta_access_token']) }}" placeholder="EAAGm0PX4ZCBO...">
                                <button type="button" onclick="const f=document.getElementById('whatsapp_meta_access_token'); f.type = f.type==='password'?'text':'password';" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #a1a1aa; cursor: pointer; font-size: 11px; text-transform: uppercase; font-family: monospace;">Ver</button>
                            </div>
                            <div class="form-hint">Token con permiso <code>whatsapp_business_messaging</code>. También puedes configurarlo en Render como <code>WHATSAPP_ACCESS_TOKEN</code>.</div>
                        </div>

                        <!-- Webhook opcional de respaldo -->
                        <div style="background: rgba(0,0,0,0.3); border: 1px dashed var(--border-subtle); border-radius: 12px; padding: 16px; margin-top: 18px;">
                            <div style="font-size: 12px; font-weight: 700; color: #fff; margin-bottom: 4px;">Webhook adicional (Opcional)</div>
                            <p class="form-hint" style="margin-bottom: 10px;">Si además quieres reenviar el evento a Make, Zapier o n8n:</p>
                            <input type="url" id="automation_whatsapp_webhook_url" name="automation_whatsapp_webhook_url" class="form-input" value="{{ old('automation_whatsapp_webhook_url', $settings['automation_whatsapp_webhook_url']) }}" placeholder="https://hook.make.com/...">
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-bottom: 30px;">
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Guardar Configuración de Resend & Meta</span>
                        </button>
                    </div>
                </form>

                <!-- Pruebas en Vivo -->
                <div class="auto-card">
                    <div class="auto-card-title">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Pruebas en Tiempo Real</span>
                    </div>
                    <p class="auto-card-desc">Haz clic para comprobar la conectividad en vivo con Resend y Meta WhatsApp Cloud API.</p>

                    <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                        <form action="{{ route('automations.test-email') }}" method="POST" style="display: inline-block;">
                            @csrf
                            <button type="submit" class="btn-secondary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span>Probar Envío con Resend</span>
                            </button>
                        </form>

                        <form action="{{ route('automations.test-whatsapp') }}" method="POST" style="display: inline-block;">
                            @csrf
                            <button type="submit" class="btn-wa">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <span>Probar Meta WhatsApp Cloud API</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Guías y Previsualización -->
            <div>
                <!-- Previsualización de WhatsApp -->
                <div class="auto-card">
                    <div class="auto-card-title">
                        <span>📱 Formato de Alerta WhatsApp</span>
                    </div>
                    <p class="auto-card-desc">Formato exacto que Meta WhatsApp Cloud API entregará en tu teléfono:</p>

                    <div class="wa-preview">
                        <div class="wa-bubble">{!! nl2br(e($sampleWaMessage)) !!}</div>
                    </div>
                </div>

                <!-- Guía de Variables para Render -->
                <div class="auto-card">
                    <div class="auto-card-title">
                        <span>🚀 Variables para Render Dashboard</span>
                    </div>
                    <p class="auto-card-desc">Copia y pega estas variables en tu servicio de Render (Environment Variables) para dejarlas persistentes en producción:</p>

                    <div style="background: rgba(0,0,0,0.6); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 14px; font-family: 'Geist Mono', monospace; font-size: 11px; line-height: 1.8; color: #a1a1aa; overflow-x: auto;">
                        <span style="color: #60a5fa;"># RESEND (CORREOS DIRECTOS)</span><br>
                        RESEND_API_KEY=re_tu_api_key_aqui<br>
                        MAIL_FROM_ADDRESS=hola@kostastudio.com<br>
                        MAIL_FROM_NAME="Kosta Studio"<br><br>
                        <span style="color: #34d399;"># META BUSINESS (WHATSAPP CLOUD API)</span><br>
                        WHATSAPP_PHONE_NUMBER_ID=tu_phone_number_id<br>
                        WHATSAPP_ACCESS_TOKEN=tu_meta_access_token<br>
                        KOSTA_WHATSAPP_NUMBER=573113894136<br>
                        KOSTA_LEAD_NOTIFICATION_EMAIL=tu-correo@kostastudio.com
                    </div>

                    <div style="margin-top: 14px; font-size: 12px; color: var(--text-muted); line-height: 1.6;">
                        <strong style="color: #fff;">Tip:</strong> También puedes guardar la API Key y el Token directamente en el formulario de la izquierda y se guardarán en la base de datos de producción automáticamente.
                    </div>
                </div>

                <!-- Historial de Notificaciones de Leads Recientes -->
                <div class="auto-card">
                    <div class="auto-card-title">
                        <span>📋 Últimas Notificaciones Procesadas</span>
                    </div>
                    <p class="auto-card-desc">Registro en tiempo real de leads despachados:</p>

                    @if($recentNotifications->isEmpty())
                        <div style="text-align: center; padding: 24px 0; color: var(--text-muted); font-size: 13px;">
                            No hay notificaciones registradas todavía.
                        </div>
                    @else
                        <div style="overflow-x: auto;">
                            <table class="logs-table">
                                <thead>
                                    <tr>
                                        <th>Lead</th>
                                        <th>Resend</th>
                                        <th>WhatsApp</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentNotifications as $leadItem)
                                        @php
                                            $notifs = $leadItem->metadata['notifications'] ?? [];
                                            $emailStatus = $notifs['email']['status'] ?? 'pending';
                                            $waStatus = $notifs['whatsapp']['status'] ?? 'pending';
                                        @endphp
                                        <tr>
                                            <td>
                                                <div style="font-weight: 700; color: #fff;">{{ $leadItem->nombre }}</div>
                                                <div style="color: var(--text-muted); font-size: 11px;">{{ $leadItem->servicio }}</div>
                                            </td>
                                            <td>
                                                @if($emailStatus === 'sent')
                                                    <span style="color: #34d399; font-weight: 600;">✓ Resend OK</span>
                                                @elseif($emailStatus === 'failed')
                                                    <span style="color: #f87171; font-weight: 600;" title="{{ $notifs['email']['error'] ?? '' }}">✕ Error</span>
                                                @else
                                                    <span style="color: #a1a1aa;">{{ ucfirst($emailStatus) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($waStatus === 'sent_meta_cloud')
                                                    <span style="color: #34d399; font-weight: 600;">✓ Meta API</span>
                                                @elseif(in_array($waStatus, ['sent', 'prepared', 'sent_webhook'], true))
                                                    <a href="{{ $notifs['whatsapp']['direct_alert_url'] ?? '#' }}" target="_blank" style="color: #25D366; text-decoration: none; font-weight: 600;">
                                                        ✓ Listo ↗
                                                    </a>
                                                @else
                                                    <span style="color: #a1a1aa;">{{ ucfirst($waStatus) }}</span>
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
