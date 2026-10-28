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
        .subnav-dot.on { background: #10B981; box-shadow: 0 0 8px rgba(16,185,129,0.5); }
        .subnav-dot.off { background: #71717a; }

        .auto-grid { display: grid; grid-template-columns: 1fr; gap: 22px; align-items: start; }
        @media (min-width: 1024px) {
            .auto-grid { grid-template-columns: 1.08fr 0.92fr; }
        }

        .auto-card { background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 18px; padding: 24px; position: relative; }
        .auto-card-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; padding-bottom: 16px; margin-bottom: 18px; border-bottom: 1px solid var(--border-subtle); }
        .auto-card-title { font-size: 16px; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 8px; margin: 0; }
        .auto-card-desc { font-size: 12.5px; color: var(--text-muted); margin: 4px 0 0 0; line-height: 1.45; }

        /* Inline Compact Variable Chips */
        .vars-bar { background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.12); border-radius: 12px; padding: 10px 12px; margin-bottom: 18px; }
        .vars-bar-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; font-size: 11px; color: var(--text-muted); }
        .vars-bar-label { font-weight: 600; color: #d4d4d8; display: inline-flex; align-items: center; gap: 5px; }
        .vars-chips { display: flex; flex-wrap: wrap; gap: 6px; }
        .var-chip { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #e4e4e7; padding: 4px 9px; border-radius: 7px; font-size: 11.5px; font-weight: 500; cursor: pointer; transition: all 0.15s ease; display: inline-flex; align-items: center; gap: 4px; user-select: none; }
        .var-chip:hover { background: rgba(230, 57, 70, 0.15); border-color: rgba(230, 57, 70, 0.4); color: #fff; transform: translateY(-1px); }
        .var-chip:active { transform: scale(0.96); }
        .var-chip span.plus { color: #e63946; font-weight: 700; font-family: monospace; }

        /* Form Controls */
        .form-group { margin-bottom: 15px; }
        .form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        @media (max-width: 640px) { .form-row-2 { grid-template-columns: 1fr; } }
        .form-label { display: block; font-size: 11.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--text-secondary); margin-bottom: 6px; }
        .form-input, .form-textarea { width: 100%; background: rgba(0,0,0,0.45); border: 1px solid var(--border-default); border-radius: 10px; padding: 10px 13px; color: #fff; font-size: 13.5px; transition: all 0.18s ease; outline: none; box-sizing: border-box; font-family: inherit; }
        .form-input:focus, .form-textarea:focus { border-color: rgba(230, 57, 70, 0.55); background: rgba(0,0,0,0.7); box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.1); }
        .form-textarea { line-height: 1.55; resize: vertical; }
        .form-hint { font-size: 11.5px; color: var(--text-muted); margin-top: 5px; }

        /* Switch Pill */
        .switch-pill { display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.03); border: 1px solid var(--border-subtle); padding: 6px 10px; border-radius: 999px; cursor: pointer; user-select: none; flex-shrink: 0; }
        .switch-pill-label { font-size: 11.5px; font-weight: 600; color: #d4d4d8; }

        .card-footer-bar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-subtle); }

        .btn-primary { background: #FFFFFF; color: #000; font-weight: 700; font-size: 13px; padding: 10px 20px; border-radius: 10px; border: none; cursor: pointer; transition: all 0.18s ease; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary:hover { background: #e4e4e7; transform: translateY(-1px); }

        .btn-secondary { background: rgba(255, 255, 255, 0.07); color: #fff; font-weight: 600; font-size: 13px; padding: 10px 18px; border-radius: 10px; border: 1px solid var(--border-subtle); cursor: pointer; transition: all 0.18s ease; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-secondary:hover { background: rgba(255, 255, 255, 0.13); border-color: rgba(255, 255, 255, 0.25); }

        /* Realistic Email Client Preview */
        .mail-preview-shell { border: 1px solid var(--border-subtle); border-radius: 18px; overflow: hidden; background: #0d0d0f; box-shadow: 0 12px 32px rgba(0,0,0,0.45); position: sticky; top: 20px; }
        .mail-window-bar { background: #141418; padding: 11px 16px; border-bottom: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: space-between; }
        .mail-window-dots { display: flex; align-items: center; gap: 6px; }
        .mail-window-dots span { width: 9px; height: 9px; border-radius: 50%; }
        .mail-envelope-header { background: #101014; padding: 12px 16px; border-bottom: 1px solid rgba(255,255,255,0.06); font-size: 12px; display: grid; gap: 5px; }
        .mail-meta-row { display: flex; align-items: baseline; gap: 8px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .mail-meta-key { color: #71717a; font-weight: 600; min-width: 48px; font-size: 11px; text-transform: uppercase; }
        .mail-meta-val { color: #e4e4e7; font-weight: 500; overflow: hidden; text-overflow: ellipsis; }
        .preview-iframe { width: 100%; height: 530px; border: none; background: #0d0d0f; display: block; }

        /* Logs Table */
        .logs-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
        .logs-table th { padding: 10px 12px; text-align: left; color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 10.5px; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-subtle); }
        .logs-table td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.04); color: var(--text-secondary); vertical-align: middle; }

        .alert-box { padding: 12px 16px; border-radius: 12px; margin-bottom: 18px; font-size: 13px; display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .alert-box.success { background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7; }
        .alert-box.error { background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; }

        /* Floating Toast for variable insertion */
        .var-toast { position: fixed; bottom: 24px; right: 24px; background: #18181b; border: 1px solid rgba(16, 185, 129, 0.4); color: #6ee7b7; padding: 10px 16px; border-radius: 10px; font-size: 12.5px; font-weight: 600; box-shadow: 0 10px 25px rgba(0,0,0,0.6); z-index: 9999; display: flex; align-items: center; gap: 8px; pointer-events: none; transition: all 0.2s ease; }
    </style>

    @php
        $initialTab = in_array(request('tpl'), ['kosta', 'client', 'settings'], true) ? request('tpl') : 'kosta';
    @endphp

    <div class="auto-page"
         x-data="{
            activeTab: '{{ $initialTab }}',
            kostaEnabled: {{ $settings['automation_email_enabled'] ? 'true' : 'false' }},
            clientEnabled: {{ $settings['automation_auto_responder_enabled'] ? 'true' : 'false' }},
            kostaSubject: @js(old('email_tpl_kosta_subject', $settings['email_tpl_kosta_subject'])),
            clientSubject: @js(old('email_tpl_client_subject', $settings['email_tpl_client_subject'])),
            toastMsg: '',
            toastTimer: null,
            setTab(tab) {
                this.activeTab = tab;
                const url = new URL(window.location);
                url.searchParams.set('tpl', tab);
                window.history.replaceState({}, '', url);
            },
            showToast(msg) {
                this.toastMsg = msg;
                if (this.toastTimer) clearTimeout(this.toastTimer);
                this.toastTimer = setTimeout(() => { this.toastMsg = ''; }, 2400);
            }
         }"
         @switch-email-tab.window="setTab($event.detail)">

        <!-- Top Header + Channel Switcher -->
        <div class="auto-header">
            <div>
                <h1 class="auto-title">Automatizaciones de Correo</h1>
                <p class="auto-subtitle">Personaliza las plantillas automáticas y gestiona tu conexión con <strong>Resend</strong>.</p>
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
                <button type="button" class="subnav-btn" :class="{ 'active': activeTab === 'kosta' }" @click="setTab('kosta')">
                    <span class="subnav-dot" :class="kostaEnabled ? 'on' : 'off'"></span>
                    <span>1. Notificación Interna (Kosta)</span>
                </button>

                <button type="button" class="subnav-btn" :class="{ 'active': activeTab === 'client' }" @click="setTab('client')">
                    <span class="subnav-dot" :class="clientEnabled ? 'on' : 'off'"></span>
                    <span>2. Auto-responder (Cliente)</span>
                </button>

                <button type="button" class="subnav-btn" :class="{ 'active': activeTab === 'settings' }" @click="setTab('settings')">
                    <svg style="width: 15px; height: 15px; opacity: 0.8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>3. Conexión Resend & Historial</span>
                </button>
            </div>

            <div style="font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                <span style="width: 7px; height: 7px; border-radius: 50%; background: {{ $mailInfo['has_resend_key'] ? '#10B981' : '#F59E0B' }};"></span>
                <span>Estado API: <strong style="color: #fff;">{{ $mailInfo['has_resend_key'] ? 'Resend Activo' : 'Pendiente API Key' }}</strong></span>
            </div>
        </div>

        <div class="auto-grid">
            <!-- COLUMNA IZQUIERDA -->
            <div>
                <form action="{{ route('automations.email.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="active_tab" :value="activeTab">

                    <!-- ==================== PESTAÑA 1: PLANTILLA KOSTA ==================== -->
                    <div class="auto-card" x-show="activeTab === 'kosta'">
                        <div class="auto-card-header">
                            <div>
                                <div class="auto-card-title">
                                    <span style="background: rgba(230, 57, 70, 0.15); color: #e63946; font-size: 10.5px; padding: 3px 8px; border-radius: 6px; border: 1px solid rgba(230,57,70,0.3); letter-spacing: 0.04em;">INTERNO</span>
                                    <span>Correo que recibe Kosta</span>
                                </div>
                                <p class="auto-card-desc">Llega a tu bandeja ({{ $settings['automation_email_recipient'] }}) cada vez que entra un prospecto.</p>
                            </div>

                            <label class="switch-pill" title="Activar o pausar este correo">
                                <span class="switch-pill-label" x-text="kostaEnabled ? 'Activo' : 'Pausado'"></span>
                                <span style="position: relative; display: inline-block; width: 38px; height: 20px;">
                                    <input type="checkbox" name="automation_email_enabled" value="1" x-model="kostaEnabled" style="opacity: 0; width: 0; height: 0;">
                                    <span :style="kostaEnabled ? 'background: #10B981;' : 'background: rgba(255,255,255,0.2);'" style="position: absolute; inset: 0; border-radius: 999px; transition: 0.2s;">
                                        <span :style="kostaEnabled ? 'transform: translateX(18px);' : 'transform: translateX(0);'" style="position: absolute; height: 14px; width: 14px; left: 3px; bottom: 3px; background: #000; border-radius: 50%; transition: 0.2s;"></span>
                                    </span>
                                </span>
                            </label>
                        </div>

                        <!-- Barra compacta de Variables -->
                        <div class="vars-bar">
                            <div class="vars-bar-header">
                                <span class="vars-bar-label">
                                    <svg style="width: 13px; height: 13px; color: #e63946;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    Variables dinámicas (clic para insertar en el cursor):
                                </span>
                            </div>
                            <div class="vars-chips">
                                <button type="button" class="var-chip" onclick="insertMetaTag('@{{nombre}}')" title="Nombre del cliente"><span class="plus">+</span> Nombre</button>
                                <button type="button" class="var-chip" onclick="insertMetaTag('@{{servicio}}')" title="Servicio cotizado"><span class="plus">+</span> Servicio</button>
                                <button type="button" class="var-chip" onclick="insertMetaTag('@{{presupuesto}}')" title="Rango presupuestal"><span class="plus">+</span> Presupuesto</button>
                                <button type="button" class="var-chip" onclick="insertMetaTag('@{{telefono}}')" title="Teléfono o WhatsApp"><span class="plus">+</span> Teléfono</button>
                                <button type="button" class="var-chip" onclick="insertMetaTag('@{{email}}')" title="Correo del cliente"><span class="plus">+</span> Email</button>
                                <button type="button" class="var-chip" onclick="insertMetaTag('@{{tipo_cliente}}')" title="Empresa / Startup / etc."><span class="plus">+</span> Tipo cliente</button>
                                <button type="button" class="var-chip" onclick="insertMetaTag('@{{como_nos_conocio}}')" title="Canal de origen"><span class="plus">+</span> Origen</button>
                                <button type="button" class="var-chip" onclick="insertMetaTag('@{{fecha}}')" title="Fecha y hora"><span class="plus">+</span> Fecha</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email_tpl_kosta_subject">Asunto del Correo</label>
                            <input type="text" id="email_tpl_kosta_subject" name="email_tpl_kosta_subject" x-model="kostaSubject" class="form-input js-tag-target" placeholder="🚀 Nuevo Lead: @{{nombre}} (@{{servicio}})">
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label" for="email_tpl_kosta_badge">Etiqueta Superior (Badge)</label>
                                <input type="text" id="email_tpl_kosta_badge" name="email_tpl_kosta_badge" class="form-input js-tag-target" value="{{ old('email_tpl_kosta_badge', $settings['email_tpl_kosta_badge']) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email_tpl_kosta_title">Título Principal</label>
                                <input type="text" id="email_tpl_kosta_title" name="email_tpl_kosta_title" class="form-input js-tag-target" value="{{ old('email_tpl_kosta_title', $settings['email_tpl_kosta_title']) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email_tpl_kosta_intro">Texto de Introducción</label>
                            <input type="text" id="email_tpl_kosta_intro" name="email_tpl_kosta_intro" class="form-input js-tag-target" value="{{ old('email_tpl_kosta_intro', $settings['email_tpl_kosta_intro']) }}" placeholder="Se ha registrado una nueva solicitud desde el formulario...">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="email_tpl_kosta_footer">Pie de Página (Footer)</label>
                            <input type="text" id="email_tpl_kosta_footer" name="email_tpl_kosta_footer" class="form-input js-tag-target" value="{{ old('email_tpl_kosta_footer', $settings['email_tpl_kosta_footer']) }}">
                        </div>

                        <div class="card-footer-bar">
                            <span style="font-size: 12px; color: var(--text-muted);">Guarda para actualizar la vista previa interactiva →</span>
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Guardar Plantilla Kosta</span>
                            </button>
                        </div>
                    </div>

                    <!-- ==================== PESTAÑA 2: PLANTILLA CLIENTE ==================== -->
                    <div class="auto-card" x-show="activeTab === 'client'" style="display: none;">
                        <div class="auto-card-header">
                            <div>
                                <div class="auto-card-title">
                                    <span style="background: rgba(16, 185, 129, 0.15); color: #34d399; font-size: 10.5px; padding: 3px 8px; border-radius: 6px; border: 1px solid rgba(16,185,129,0.3); letter-spacing: 0.04em;">AUTO-RESPONDER</span>
                                    <span>Correo de confirmación al Cliente</span>
                                </div>
                                <p class="auto-card-desc">Se envía automáticamente al prospecto apenas completa el formulario web.</p>
                            </div>

                            <label class="switch-pill" title="Activar o pausar el auto-responder">
                                <span class="switch-pill-label" x-text="clientEnabled ? 'Activo' : 'Pausado'"></span>
                                <span style="position: relative; display: inline-block; width: 38px; height: 20px;">
                                    <input type="checkbox" name="automation_auto_responder_enabled" value="1" x-model="clientEnabled" style="opacity: 0; width: 0; height: 0;">
                                    <span :style="clientEnabled ? 'background: #10B981;' : 'background: rgba(255,255,255,0.2);'" style="position: absolute; inset: 0; border-radius: 999px; transition: 0.2s;">
                                        <span :style="clientEnabled ? 'transform: translateX(18px);' : 'transform: translateX(0);'" style="position: absolute; height: 14px; width: 14px; left: 3px; bottom: 3px; background: #000; border-radius: 50%; transition: 0.2s;"></span>
                                    </span>
                                </span>
                            </label>
                        </div>

                        <!-- Barra compacta de Variables -->
                        <div class="vars-bar">
                            <div class="vars-bar-header">
                                <span class="vars-bar-label">
                                    <svg style="width: 13px; height: 13px; color: #e63946;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    Variables dinámicas (clic para insertar en el cursor):
                                </span>
                            </div>
                            <div class="vars-chips">
                                <button type="button" class="var-chip" onclick="insertMetaTag('@{{nombre}}')"><span class="plus">+</span> Nombre</button>
                                <button type="button" class="var-chip" onclick="insertMetaTag('@{{servicio}}')"><span class="plus">+</span> Servicio</button>
                                <button type="button" class="var-chip" onclick="insertMetaTag('@{{presupuesto}}')"><span class="plus">+</span> Presupuesto</button>
                                <button type="button" class="var-chip" onclick="insertMetaTag('@{{telefono}}')"><span class="plus">+</span> Teléfono</button>
                                <button type="button" class="var-chip" onclick="insertMetaTag('@{{email}}')"><span class="plus">+</span> Email</button>
                                <button type="button" class="var-chip" onclick="insertMetaTag('@{{fecha}}')"><span class="plus">+</span> Fecha</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email_tpl_client_subject">Asunto del Correo</label>
                            <input type="text" id="email_tpl_client_subject" name="email_tpl_client_subject" x-model="clientSubject" class="form-input js-tag-target" placeholder="¡Hemos recibido tu mensaje! — Kosta Studio & Films">
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label" for="email_tpl_client_title">Saludo Principal</label>
                                <input type="text" id="email_tpl_client_title" name="email_tpl_client_title" class="form-input js-tag-target" value="{{ old('email_tpl_client_title', $settings['email_tpl_client_title']) }}" placeholder="¡Hola, @{{nombre}}!">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email_tpl_client_subtitle">Subtítulo</label>
                                <input type="text" id="email_tpl_client_subtitle" name="email_tpl_client_subtitle" class="form-input js-tag-target" value="{{ old('email_tpl_client_subtitle', $settings['email_tpl_client_subtitle']) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email_tpl_client_p1">Primer Párrafo (Confirmación y Servicio)</label>
                            <textarea id="email_tpl_client_p1" name="email_tpl_client_p1" rows="2" class="form-textarea js-tag-target">{{ old('email_tpl_client_p1', $settings['email_tpl_client_p1']) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email_tpl_client_p2">Segundo Párrafo (Compromiso y Tiempos)</label>
                            <textarea id="email_tpl_client_p2" name="email_tpl_client_p2" rows="2" class="form-textarea js-tag-target">{{ old('email_tpl_client_p2', $settings['email_tpl_client_p2']) }}</textarea>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label" for="email_tpl_client_wa_text">Llamado a WhatsApp</label>
                                <input type="text" id="email_tpl_client_wa_text" name="email_tpl_client_wa_text" class="form-input js-tag-target" value="{{ old('email_tpl_client_wa_text', $settings['email_tpl_client_wa_text']) }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email_tpl_client_wa_btn">Texto Botón WhatsApp</label>
                                <input type="text" id="email_tpl_client_wa_btn" name="email_tpl_client_wa_btn" class="form-input js-tag-target" value="{{ old('email_tpl_client_wa_btn', $settings['email_tpl_client_wa_btn']) }}">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="email_tpl_client_footer">Pie de Página (Footer)</label>
                            <input type="text" id="email_tpl_client_footer" name="email_tpl_client_footer" class="form-input js-tag-target" value="{{ old('email_tpl_client_footer', $settings['email_tpl_client_footer']) }}">
                        </div>

                        <div class="card-footer-bar">
                            <span style="font-size: 12px; color: var(--text-muted);">Guarda para actualizar la vista previa interactiva →</span>
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Guardar Auto-responder</span>
                            </button>
                        </div>
                    </div>

                    <!-- ==================== PESTAÑA 3: CONEXIÓN RESEND ==================== -->
                    <div class="auto-card" x-show="activeTab === 'settings'" style="display: none;">
                        <div class="auto-card-header">
                            <div>
                                <div class="auto-card-title">
                                    <svg class="w-5 h-5" style="color: #e63946;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>Credenciales de Resend & Enrutamiento</span>
                                </div>
                                <p class="auto-card-desc">Configura la llave API de Resend y los correos remitente/receptor del estudio.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="automation_email_recipient">Bandeja Receptora de Kosta (Destino de Leads)</label>
                            <input type="email" id="automation_email_recipient" name="automation_email_recipient" class="form-input" value="{{ old('automation_email_recipient', $settings['automation_email_recipient']) }}" required placeholder="hola@kostastudio.com">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="resend_api_key">API Key de Resend</label>
                            <div style="position: relative;">
                                <input type="password" id="resend_api_key" name="resend_api_key" class="form-input" value="{{ old('resend_api_key', $settings['resend_api_key']) }}" placeholder="re_123456789_abcdef...">
                                <button type="button" onclick="const f=document.getElementById('resend_api_key'); f.type = f.type==='password'?'text':'password';" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #a1a1aa; cursor: pointer; font-size: 11px; text-transform: uppercase; font-family: monospace;">Mostrar</button>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label" for="resend_from_email">Correo Remitente (From)</label>
                                <input type="email" id="resend_from_email" name="resend_from_email" class="form-input" value="{{ old('resend_from_email', $settings['resend_from_email']) }}" placeholder="hola@kostastudio.com">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="resend_from_name">Nombre Remitente</label>
                                <input type="text" id="resend_from_name" name="resend_from_name" class="form-input" value="{{ old('resend_from_name', $settings['resend_from_name']) }}" placeholder="Kosta Studio">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="automation_email_cc">Copia de respaldo CC (Opcional, separados por coma)</label>
                            <input type="text" id="automation_email_cc" name="automation_email_cc" class="form-input" value="{{ old('automation_email_cc', $settings['automation_email_cc']) }}" placeholder="socio@kostastudio.com">
                        </div>

                        <div class="card-footer-bar">
                            <span style="font-size: 12px; color: var(--text-muted);">Tus credenciales se guardan de forma segura en la base de datos.</span>
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Guardar Credenciales</span>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Tarjeta de Prueba en Tiempo Real (solo en pestaña 3) -->
                <div class="auto-card" x-show="activeTab === 'settings'" style="display: none; margin-top: 20px;">
                    <div class="auto-card-header" style="margin-bottom: 14px; padding-bottom: 12px;">
                        <div>
                            <div class="auto-card-title">
                                <svg class="w-5 h-5" style="color: #F59E0B;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span>Enviar Correo de Prueba</span>
                            </div>
                            <p class="auto-card-desc">Verifica al instante que Resend entrega correctamente en tu bandeja.</p>
                        </div>
                    </div>

                    <form action="{{ route('automations.test-email') }}" method="POST" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                        @csrf
                        <input type="email" name="test_email" class="form-input" style="flex: 1; min-width: 220px;" value="{{ $settings['automation_email_recipient'] }}" placeholder="correo-destino@ejemplo.com">
                        <button type="submit" class="btn-secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>Disparar Prueba Ahora</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- COLUMNA DERECHA -->
            <div>
                <!-- PREVIEW DE CORREO EN VIVO (Visible en Pestañas 1 y 2) -->
                <div class="mail-preview-shell" x-show="activeTab === 'kosta' || activeTab === 'client'">
                    <div class="mail-window-bar">
                        <div class="mail-window-dots">
                            <span style="background: #EF4444;"></span>
                            <span style="background: #F59E0B;"></span>
                            <span style="background: #10B981;"></span>
                            <span style="margin-left: 8px; font-size: 11.5px; font-weight: 600; color: #d4d4d8;" x-text="activeTab === 'kosta' ? 'Vista previa: Correo a Kosta' : 'Vista previa: Auto-responder al Cliente'"></span>
                        </div>
                        <button type="button" onclick="document.getElementById('preview-frame').contentWindow.location.reload();" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; padding: 3px 9px; color: #d4d4d8; cursor: pointer; font-size: 11px;">
                            ↻ Refrescar
                        </button>
                    </div>

                    <!-- Cabecera simulada de cliente de correo -->
                    <div class="mail-envelope-header">
                        <div class="mail-meta-row">
                            <span class="mail-meta-key">De:</span>
                            <span class="mail-meta-val">{{ $settings['resend_from_name'] }} &lt;{{ $settings['resend_from_email'] }}&gt;</span>
                        </div>
                        <div class="mail-meta-row">
                            <span class="mail-meta-key">Para:</span>
                            <span class="mail-meta-val" x-text="activeTab === 'kosta' ? '{{ $settings['automation_email_recipient'] }}' : '{{ $sampleLead->nombre }} <{{ $sampleLead->email }}>'"></span>
                        </div>
                        <div class="mail-meta-row">
                            <span class="mail-meta-key">Asunto:</span>
                            <span class="mail-meta-val" style="color: #fff; font-weight: 600;" x-text="activeTab === 'kosta' ? kostaSubject : clientSubject"></span>
                        </div>
                    </div>

                    <iframe id="preview-frame"
                            class="preview-iframe"
                            :src="activeTab === 'client' ? '{{ route('automations.email.preview', 'client') }}' : '{{ route('automations.email.preview', 'kosta') }}'"
                            title="Previsualización de Correo"></iframe>
                </div>

                <!-- HISTORIAL DE CORREOS DESPACHADOS (Visible en Pestaña 3) -->
                <div class="auto-card" x-show="activeTab === 'settings'" style="display: none;">
                    <div class="auto-card-header" style="margin-bottom: 12px; padding-bottom: 12px;">
                        <div>
                            <div class="auto-card-title">
                                <span>📋 Últimos Correos Despachados</span>
                            </div>
                            <p class="auto-card-desc">Registro reciente de prospectos procesados por Resend.</p>
                        </div>
                    </div>

                    @if($recentNotifications->isEmpty())
                        <div style="text-align: center; padding: 36px 16px; color: var(--text-muted); font-size: 13px;">
                            Aún no hay envíos registrados en el historial.
                        </div>
                    @else
                        <div style="overflow-x: auto;">
                            <table class="logs-table">
                                <thead>
                                    <tr>
                                        <th>Prospecto</th>
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
                                                        <div style="color: var(--text-muted); font-size: 10px; font-family: monospace;">{{ \Illuminate\Support\Str::limit($resendId, 18) }}</div>
                                                    @endif
                                                @elseif($emailStatus === 'failed')
                                                    <span style="color: #f87171; font-weight: 600;" title="{{ $notifs['email']['error'] ?? '' }}">✕ Falló</span>
                                                @else
                                                    <span style="color: #a1a1aa;">{{ ucfirst($emailStatus) }}</span>
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
            </div>
        </div>

        <!-- Floating Toast -->
        <div class="var-toast" x-show="toastMsg" x-transition.opacity style="display: none;">
            <svg style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span x-text="toastMsg"></span>
        </div>
    </div>

    <script>
        let lastFocusedField = null;

        document.addEventListener('focusin', (e) => {
            if (e.target && e.target.classList && e.target.classList.contains('js-tag-target')) {
                lastFocusedField = e.target;
            }
        });

        function insertMetaTag(tag) {
            let target = lastFocusedField;

            // Si no hay un campo enfocado visible, usar el campo de asunto de la pestaña activa
            if (!target || target.offsetParent === null) {
                const visibleInputs = Array.from(document.querySelectorAll('.js-tag-target')).filter(el => el.offsetParent !== null);
                target = visibleInputs[0] || null;
            }

            if (target) {
                const start = target.selectionStart ?? target.value.length;
                const end = target.selectionEnd ?? target.value.length;
                const before = target.value.substring(0, start);
                const after = target.value.substring(end);
                target.value = before + tag + after;
                target.focus();
                const nextPos = start + tag.length;
                target.setSelectionRange(nextPos, nextPos);
                target.dispatchEvent(new Event('input', { bubbles: true }));
            }

            if (navigator.clipboard) {
                navigator.clipboard.writeText(tag).catch(() => {});
            }

            const pageEl = document.querySelector('.auto-page');
            if (pageEl && pageEl.__x) {
                pageEl.__x.$data.showToast('Variable ' + tag + ' insertada');
            } else if (window.Alpine && pageEl) {
                const data = Alpine.$data(pageEl);
                if (data && data.showToast) data.showToast('Variable ' + tag + ' insertada');
            }
        }
    </script>
</x-layouts.app>
