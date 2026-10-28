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
        .form-input { width: 100%; background: rgba(0,0,0,0.5); border: 1px solid var(--border-default); border-radius: 12px; padding: 11px 14px; color: #fff; font-size: 13.5px; transition: border-color 0.2s ease; outline: none; box-sizing: border-box; font-family: inherit; }
        .form-input:focus { border-color: rgba(255, 255, 255, 0.4); background: rgba(0,0,0,0.7); }
        .form-textarea { width: 100%; background: rgba(0,0,0,0.5); border: 1px solid var(--border-default); border-radius: 12px; padding: 11px 14px; color: #fff; font-size: 13.5px; transition: border-color 0.2s ease; outline: none; box-sizing: border-box; font-family: inherit; line-height: 1.6; resize: vertical; }
        .form-textarea:focus { border-color: rgba(255, 255, 255, 0.4); background: rgba(0,0,0,0.7); }
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

        /* Meta tags pill bar */
        .metatags-panel { background: rgba(0,0,0,0.4); border: 1px solid var(--border-subtle); border-radius: 14px; padding: 16px; margin-bottom: 22px; }
        .metatags-title { font-size: 11.5px; font-weight: 700; color: #e63946; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
        .metatags-list { display: flex; flex-wrap: wrap; gap: 8px; }
        .meta-tag-pill { display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); padding: 5px 10px; border-radius: 8px; font-size: 11.5px; font-family: 'Geist Mono', monospace; color: #fff; cursor: pointer; transition: all 0.2s ease; user-select: none; }
        .meta-tag-pill:hover { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.3); transform: translateY(-1px); }
        .meta-tag-pill:active { transform: scale(0.97); }
        .meta-tag-pill .desc { color: var(--text-muted); font-size: 10px; font-family: sans-serif; }

        /* Template Switcher Tabs */
        .tpl-tab-nav { display: flex; gap: 6px; margin-bottom: 20px; border-bottom: 1px solid var(--border-subtle); padding-bottom: 10px; }
        .tpl-tab-btn { background: none; border: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; color: var(--text-muted); cursor: pointer; transition: all 0.2s ease; }
        .tpl-tab-btn.active { background: rgba(255,255,255,0.1); color: #fff; }

        /* Iframe Preview Container */
        .preview-container { border: 1px solid var(--border-subtle); border-radius: 16px; overflow: hidden; background: #000; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .preview-toolbar { background: #18181b; padding: 10px 16px; border-bottom: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: space-between; }
        .preview-iframe { width: 100%; height: 560px; border: none; background: #0d0d0f; }
    </style>

    <div style="max-width: 1400px; margin: 0 auto; padding: 24px 20px;" x-data="{ activeTpl: '{{ request('tpl') === 'client' ? 'client' : 'kosta' }}', previewTpl: '{{ request('tpl') === 'client' ? 'client' : 'kosta' }}' }">
        <!-- Header -->
        <div class="auto-header">
            <div>
                <h1 class="auto-title">Automatizaciones: Correo</h1>
                <p class="auto-subtitle">Configura <strong>Resend</strong> y edita el contenido exacto de los correos para Kosta y para el Cliente con <strong>meta campos dinámicos</strong>.</p>
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
                <span class="status-badge {{ $mailInfo['has_resend_key'] ? 'live' : 'warning' }}">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background: currentColor;"></span>
                    Resend: {{ $mailInfo['has_resend_key'] ? 'Conectado' : 'Pendiente API Key' }}
                </span>
            </div>
        </div>

        <!-- Navigation Tabs Principal -->
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

        <!-- Panel de Metacampos Disponibles -->
        <div class="metatags-panel">
            <div class="metatags-title">
                <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span>Meta Campos Visibles (Haz clic sobre cualquier etiqueta para copiarla al portapapeles):</span>
            </div>
            <div class="metatags-list">
                <button type="button" class="meta-tag-pill" data-tag="@{{nombre}}" onclick="copyTag(this.getAttribute('data-tag'))">
                    <strong>@{{nombre}}</strong> <span class="desc">(Nombre del cliente)</span>
                </button>
                <button type="button" class="meta-tag-pill" data-tag="@{{email}}" onclick="copyTag(this.getAttribute('data-tag'))">
                    <strong>@{{email}}</strong> <span class="desc">(Correo electrónico)</span>
                </button>
                <button type="button" class="meta-tag-pill" data-tag="@{{telefono}}" onclick="copyTag(this.getAttribute('data-tag'))">
                    <strong>@{{telefono}}</strong> <span class="desc">(Teléfono / WhatsApp)</span>
                </button>
                <button type="button" class="meta-tag-pill" data-tag="@{{servicio}}" onclick="copyTag(this.getAttribute('data-tag'))">
                    <strong>@{{servicio}}</strong> <span class="desc">(Servicio cotizado)</span>
                </button>
                <button type="button" class="meta-tag-pill" data-tag="@{{tipo_cliente}}" onclick="copyTag(this.getAttribute('data-tag'))">
                    <strong>@{{tipo_cliente}}</strong> <span class="desc">(Empresa / Startup / etc.)</span>
                </button>
                <button type="button" class="meta-tag-pill" data-tag="@{{presupuesto}}" onclick="copyTag(this.getAttribute('data-tag'))">
                    <strong>@{{presupuesto}}</strong> <span class="desc">(Rango presupuestal)</span>
                </button>
                <button type="button" class="meta-tag-pill" data-tag="@{{como_nos_conocio}}" onclick="copyTag(this.getAttribute('data-tag'))">
                    <strong>@{{como_nos_conocio}}</strong> <span class="desc">(Canal de origen)</span>
                </button>
                <button type="button" class="meta-tag-pill" data-tag="@{{mensaje}}" onclick="copyTag(this.getAttribute('data-tag'))">
                    <strong>@{{mensaje}}</strong> <span class="desc">(Texto del mensaje)</span>
                </button>
                <button type="button" class="meta-tag-pill" data-tag="@{{fecha}}" onclick="copyTag(this.getAttribute('data-tag'))">
                    <strong>@{{fecha}}</strong> <span class="desc">(Fecha y hora de envío)</span>
                </button>
            </div>
        </div>

        <div class="auto-grid">
            <!-- Columna Izquierda: Editor de Plantillas y Resend -->
            <div>
                <form action="{{ route('automations.email.update') }}" method="POST">
                    @csrf

                    <!-- Selector de Plantilla a Editar -->
                    <div class="tpl-tab-nav">
                        <button type="button" class="tpl-tab-btn" :class="{ 'active': activeTpl === 'kosta' }" @click="activeTpl = 'kosta'; previewTpl = 'kosta';">
                            🏢 1. Correo que le llega a Kosta
                        </button>
                        <button type="button" class="tpl-tab-btn" :class="{ 'active': activeTpl === 'client' }" @click="activeTpl = 'client'; previewTpl = 'client';">
                            👤 2. Correo que le llega al Cliente (Auto-responder)
                        </button>
                    </div>

                    <!-- PLANTILLA 1: KOSTA STUDIO -->
                    <div class="auto-card" x-show="activeTpl === 'kosta'">
                        <div class="auto-card-title">
                            <span style="background: rgba(230, 57, 70, 0.15); color: #e63946; font-size: 11px; padding: 2px 8px; border-radius: 6px; border: 1px solid rgba(230,57,70,0.3);">NOTIFICACIÓN INTERNA</span>
                            <span>Editar Correo que Recibe Kosta</span>
                        </div>
                        <p class="auto-card-desc">Este es el correo que llegará a tu bandeja cada vez que alguien solicita un servicio en la web.</p>

                        <div class="form-group">
                            <label class="form-label" for="email_tpl_kosta_subject">Asunto del Correo</label>
                            <input type="text" id="email_tpl_kosta_subject" name="email_tpl_kosta_subject" class="form-input" value="{{ old('email_tpl_kosta_subject', $settings['email_tpl_kosta_subject']) }}" placeholder="🚀 Nuevo Lead: @{{nombre}} (@{{servicio}})">
                            <div class="form-hint">Puedes usar metacampos como <code>@{{nombre}}</code> y <code>@{{servicio}}</code> en el asunto.</div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                            <div class="form-group">
                                <label class="form-label" for="email_tpl_kosta_badge">Etiqueta Superior (Badge)</label>
                                <input type="text" id="email_tpl_kosta_badge" name="email_tpl_kosta_badge" class="form-input" value="{{ old('email_tpl_kosta_badge', $settings['email_tpl_kosta_badge']) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email_tpl_kosta_title">Título Principal</label>
                                <input type="text" id="email_tpl_kosta_title" name="email_tpl_kosta_title" class="form-input" value="{{ old('email_tpl_kosta_title', $settings['email_tpl_kosta_title']) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email_tpl_kosta_intro">Texto de Introducción (Opcional)</label>
                            <input type="text" id="email_tpl_kosta_intro" name="email_tpl_kosta_intro" class="form-input" value="{{ old('email_tpl_kosta_intro', $settings['email_tpl_kosta_intro']) }}" placeholder="Se ha registrado una nueva solicitud desde el formulario...">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email_tpl_kosta_footer">Pie de Página (Footer)</label>
                            <input type="text" id="email_tpl_kosta_footer" name="email_tpl_kosta_footer" class="form-input" value="{{ old('email_tpl_kosta_footer', $settings['email_tpl_kosta_footer']) }}">
                        </div>
                    </div>

                    <!-- PLANTILLA 2: CLIENTE (AUTO-RESPONDER) -->
                    <div class="auto-card" x-show="activeTpl === 'client'">
                        <div class="auto-card-title">
                            <span style="background: rgba(16, 185, 129, 0.15); color: #34d399; font-size: 11px; padding: 2px 8px; border-radius: 6px; border: 1px solid rgba(16,185,129,0.3);">CONFIRMACIÓN AL CLIENTE</span>
                            <span>Auto-responder de cortesía (Correo al Cliente)</span>
                        </div>
                        <p class="auto-card-desc">Este es el correo de cortesía que el cliente recibe inmediatamente confirmándole la recepción.</p>

                        <div class="form-group">
                            <label class="form-label" for="email_tpl_client_subject">Asunto del Correo</label>
                            <input type="text" id="email_tpl_client_subject" name="email_tpl_client_subject" class="form-input" value="{{ old('email_tpl_client_subject', $settings['email_tpl_client_subject']) }}" placeholder="¡Hemos recibido tu mensaje! — Kosta Studio & Films">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                            <div class="form-group">
                                <label class="form-label" for="email_tpl_client_title">Saludo Principal</label>
                                <input type="text" id="email_tpl_client_title" name="email_tpl_client_title" class="form-input" value="{{ old('email_tpl_client_title', $settings['email_tpl_client_title']) }}" placeholder="¡Hola, @{{nombre}}!">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email_tpl_client_subtitle">Subtítulo</label>
                                <input type="text" id="email_tpl_client_subtitle" name="email_tpl_client_subtitle" class="form-input" value="{{ old('email_tpl_client_subtitle', $settings['email_tpl_client_subtitle']) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email_tpl_client_p1">Primer Párrafo (Recepción y Servicio)</label>
                            <textarea id="email_tpl_client_p1" name="email_tpl_client_p1" rows="3" class="form-textarea">{{ old('email_tpl_client_p1', $settings['email_tpl_client_p1']) }}</textarea>
                            <div class="form-hint">Puedes usar <code>@{{servicio}}</code> para indicar el servicio exacto por el que preguntó.</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email_tpl_client_p2">Segundo Párrafo (Compromiso y Tiempo de Respuesta)</label>
                            <textarea id="email_tpl_client_p2" name="email_tpl_client_p2" rows="3" class="form-textarea">{{ old('email_tpl_client_p2', $settings['email_tpl_client_p2']) }}</textarea>
                        </div>

                        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 14px;">
                            <div class="form-group">
                                <label class="form-label" for="email_tpl_client_wa_text">Pregunta de Atención Urgente</label>
                                <input type="text" id="email_tpl_client_wa_text" name="email_tpl_client_wa_text" class="form-input" value="{{ old('email_tpl_client_wa_text', $settings['email_tpl_client_wa_text']) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email_tpl_client_wa_btn">Texto del Botón de WhatsApp</label>
                                <input type="text" id="email_tpl_client_wa_btn" name="email_tpl_client_wa_btn" class="form-input" value="{{ old('email_tpl_client_wa_btn', $settings['email_tpl_client_wa_btn']) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email_tpl_client_footer">Texto de Pie de Página (Footer)</label>
                            <input type="text" id="email_tpl_client_footer" name="email_tpl_client_footer" class="form-input" value="{{ old('email_tpl_client_footer', $settings['email_tpl_client_footer']) }}">
                        </div>
                    </div>

                    <!-- AJUSTES DE ENRUTAMIENTO & RESEND -->
                    <div class="auto-card">
                        <div class="auto-card-title">
                            <svg class="w-5 h-5 text-red-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Configuración de Resend & Enrutamiento</span>
                        </div>

                        <div class="toggle-row">
                            <div>
                                <div class="toggle-text">Activar alertas de correo para Kosta</div>
                                <div class="toggle-subtext">Envía el email a tu bandeja al recibir un lead</div>
                            </div>
                            <label style="position: relative; display: inline-block; width: 44px; height: 24px; cursor: pointer;">
                                <input type="checkbox" name="automation_email_enabled" value="1" {{ $settings['automation_email_enabled'] ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;" onchange="this.nextElementSibling.style.background = this.checked ? '#FFFFFF' : 'rgba(255,255,255,0.2)'">
                                <span style="position: absolute; inset: 0; background: {{ $settings['automation_email_enabled'] ? '#FFFFFF' : 'rgba(255,255,255,0.2)' }}; border-radius: 999px; transition: 0.2s;">
                                    <span style="position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background: #000; border-radius: 50%; transition: 0.2s; transform: {{ $settings['automation_email_enabled'] ? 'translateX(20px)' : 'translateX(0)' }};"></span>
                                </span>
                            </label>
                        </div>

                        <div class="toggle-row">
                            <div>
                                <div class="toggle-text">Activar auto-responder para el cliente</div>
                                <div class="toggle-subtext">Envía confirmación automática de cortesía a quien escribió</div>
                            </div>
                            <label style="position: relative; display: inline-block; width: 44px; height: 24px; cursor: pointer;">
                                <input type="checkbox" name="automation_auto_responder_enabled" value="1" {{ $settings['automation_auto_responder_enabled'] ? 'checked' : '' }} style="opacity: 0; width: 0; height: 0;" onchange="this.nextElementSibling.style.background = this.checked ? '#FFFFFF' : 'rgba(255,255,255,0.2)'">
                                <span style="position: absolute; inset: 0; background: {{ $settings['automation_auto_responder_enabled'] ? '#FFFFFF' : 'rgba(255,255,255,0.2)' }}; border-radius: 999px; transition: 0.2s;">
                                    <span style="position: absolute; height: 18px; width: 18px; left: 3px; bottom: 3px; background: #000; border-radius: 50%; transition: 0.2s; transform: {{ $settings['automation_auto_responder_enabled'] ? 'translateX(20px)' : 'translateX(0)' }};"></span>
                                </span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="automation_email_recipient">Tu Correo de Destino (Bandeja Personal / Estudio)</label>
                            <input type="email" id="automation_email_recipient" name="automation_email_recipient" class="form-input" value="{{ old('automation_email_recipient', $settings['automation_email_recipient']) }}" required placeholder="tu-correo@kostastudio.com">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="resend_api_key">API Key de Resend</label>
                            <div style="position: relative;">
                                <input type="password" id="resend_api_key" name="resend_api_key" class="form-input" value="{{ old('resend_api_key', $settings['resend_api_key']) }}" placeholder="re_123456789_abcdef...">
                                <button type="button" onclick="const f=document.getElementById('resend_api_key'); f.type = f.type==='password'?'text':'password';" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #a1a1aa; cursor: pointer; font-size: 11px; text-transform: uppercase; font-family: monospace;">Ver</button>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                            <div class="form-group">
                                <label class="form-label" for="resend_from_email">Remitente (From)</label>
                                <input type="email" id="resend_from_email" name="resend_from_email" class="form-input" value="{{ old('resend_from_email', $settings['resend_from_email']) }}" placeholder="hola@kostastudio.com">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="resend_from_name">Nombre Remitente</label>
                                <input type="text" id="resend_from_name" name="resend_from_name" class="form-input" value="{{ old('resend_from_name', $settings['resend_from_name']) }}" placeholder="Kosta Studio">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="automation_email_cc">Copia de respaldo (CC opcional)</label>
                            <input type="text" id="automation_email_cc" name="automation_email_cc" class="form-input" value="{{ old('automation_email_cc', $settings['automation_email_cc']) }}" placeholder="socio@kostastudio.com, backup@gmail.com">
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 12px; margin-bottom: 24px;">
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Guardar Cambios de Plantillas & Correo</span>
                        </button>
                    </div>
                </form>

                <!-- Pruebas en Vivo -->
                <div class="auto-card">
                    <div class="auto-card-title">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Prueba en Tiempo Real con Resend</span>
                    </div>
                    <p class="auto-card-desc">Envía un correo de prueba a tu bandeja para validar que Resend entrega con las plantillas configuradas.</p>

                    <form action="{{ route('automations.test-email') }}" method="POST">
                        @csrf
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <button type="submit" class="btn-secondary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span>Enviar Correo de Prueba a mi Bandeja</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Columna Derecha: Previsualización en Vivo de los Correos -->
            <div>
                <div class="auto-card" style="padding: 20px;">
                    <div class="auto-card-title" style="margin-bottom: 12px; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span>👁️ Previsualización en Vivo</span>
                        </div>
                        <div style="display: flex; gap: 6px;">
                            <button type="button" class="tpl-tab-btn" :class="{ 'active': previewTpl === 'kosta' }" @click="previewTpl = 'kosta'; document.getElementById('preview-frame').src='{{ route('automations.email.preview', 'kosta') }}';" style="padding: 4px 12px; font-size: 11.5px;">
                                Para Kosta
                            </button>
                            <button type="button" class="tpl-tab-btn" :class="{ 'active': previewTpl === 'client' }" @click="previewTpl = 'client'; document.getElementById('preview-frame').src='{{ route('automations.email.preview', 'client') }}';" style="padding: 4px 12px; font-size: 11.5px;">
                                Para Cliente
                            </button>
                        </div>
                    </div>
                    <p class="auto-card-desc" style="margin-bottom: 14px;">
                        Así se verá exactamente el correo en la bandeja de entrada, con los metacampos reemplazados por datos de muestra:
                    </p>

                    <!-- Preview Frame -->
                    <div class="preview-container">
                        <div class="preview-toolbar">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span style="width: 10px; height: 10px; border-radius: 50%; background: #EF4444;"></span>
                                <span style="width: 10px; height: 10px; border-radius: 50%; background: #F59E0B;"></span>
                                <span style="width: 10px; height: 10px; border-radius: 50%; background: #10B981;"></span>
                            </div>
                            <span style="font-size: 11px; font-family: 'Geist Mono', monospace; color: #a1a1aa;" x-text="previewTpl === 'kosta' ? 'emails.new-lead (Kosta)' : 'emails.lead-confirmation (Cliente)'"></span>
                            <button type="button" onclick="document.getElementById('preview-frame').contentWindow.location.reload();" style="background: none; border: none; color: #a1a1aa; cursor: pointer; font-size: 11px;">↻ Recargar</button>
                        </div>
                        <iframe id="preview-frame" class="preview-iframe" src="{{ route('automations.email.preview', 'kosta') }}" title="Previsualización de Correo"></iframe>
                    </div>
                </div>

                <!-- Historial de Correos Recientes -->
                <div class="auto-card">
                    <div class="auto-card-title">
                        <span>📋 Últimos Correos Despachados</span>
                    </div>
                    <p class="auto-card-desc">Historial de leads procesados vía Resend:</p>

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

    <script>
        function copyTag(tag) {
            navigator.clipboard.writeText(tag).then(() => {
                alert('Etiqueta copiada al portapapeles: ' + tag + '\n\nPégala en el campo de texto que desees.');
            }).catch(() => {
                prompt('Copia esta etiqueta:', tag);
            });
        }
    </script>
</x-layouts.app>
