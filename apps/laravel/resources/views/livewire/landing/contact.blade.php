<div class="bg-dark-gradient min-h-screen relative">
    <style>

        /* ── Contact grid ── */
        .contact-grid { display: grid; grid-template-columns: 1fr; gap: 32px; }
        @media (min-width: 1024px) { .contact-grid { grid-template-columns: 5fr 7fr; gap: 40px; } }

        /* ── Info panel ── */
        .info-panel { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.07); border-radius: 24px; padding: 40px 36px; position: relative; overflow: hidden; }
        .info-panel::before { content: ''; position: absolute; top: -100px; right: -100px; width: 260px; height: 260px; background: radial-gradient(circle, rgba(230,57,70,0.18) 0%, transparent 70%); pointer-events: none; }
        .contact-item { display: flex; align-items: flex-start; gap: 16px; padding: 18px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .contact-item:last-of-type { border-bottom: none; }
        .contact-icon { width: 44px; height: 44px; border-radius: 12px; background: rgba(230,57,70,0.1); border: 1px solid rgba(230,57,70,0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: var(--accent-red); }
        .contact-icon svg { width: 20px; height: 20px; }
        .trust-badge { display: flex; align-items: center; gap: 10px; padding: 12px 16px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; margin-top: 8px; }
        .trust-badge svg { width: 16px; height: 16px; color: var(--accent-red); flex-shrink: 0; }
        .trust-badge span { font-size: 13px; color: rgba(255,255,255,0.55); }

        /* ── Form panel ── */
        .form-panel { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.07); border-radius: 24px; padding: 40px 36px; }
        @media (max-width: 640px) { .info-panel, .form-panel { padding: 28px 24px; } }

        /* ── Form elements ── */
        .field-group { margin-bottom: 20px; }
        .field-label { display: block; font-size: 13px; font-weight: 600; color: rgba(255,255,255,0.65); margin-bottom: 8px; }
        .field-label .req { color: var(--accent-red); margin-left: 2px; }
        .field-input, .field-select, .field-textarea {
            width: 100%; padding: 12px 16px;
            background: rgba(10,10,10,0.6);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            color: #fff;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }
        .field-input::placeholder, .field-textarea::placeholder { color: rgba(255,255,255,0.25); }
        .field-input:focus, .field-select:focus, .field-textarea:focus { border-color: var(--accent-red); box-shadow: 0 0 0 3px rgba(230,57,70,0.12); background: rgba(10,10,10,0.8); }
        .field-select { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='rgba(255,255,255,0.4)' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 16px center; padding-right: 40px; }
        .field-select option { background: #1a1a1a; color: #fff; }
        .field-textarea { resize: vertical; min-height: 100px; }
        .field-error { display: block; font-size: 12px; color: var(--accent-red); margin-top: 5px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 580px) { .form-row { grid-template-columns: 1fr; } }

        /* ── Submit button ── */
        .btn-submit { width: 100%; height: 54px; border-radius: 12px; background: #FFFFFF; border: none; color: #000000; font-size: 15px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease; box-shadow: 0 4px 20px rgba(255,255,255,0.15); font-family: 'Inter', sans-serif; }
        .btn-submit:hover:not(:disabled) { background: #E4E4E7; transform: translateY(-1px); box-shadow: 0 8px 28px rgba(255,255,255,0.25); }
        .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }

        /* ── Success state ── */
        .success-panel { text-align: center; padding: 60px 32px; }
        .success-icon { width: 72px; height: 72px; border-radius: 50%; background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.3); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
        .success-icon svg { width: 32px; height: 32px; color: #10b981; }
    </style>

    <!-- Background -->
    <div aria-hidden="true" class="fixed top-0 left-1/4 w-[500px] h-[500px] bg-red-accent rounded-full blur-[180px] pointer-events-none animate-pulse-glow opacity-20"></div>
    <div aria-hidden="true" class="fixed bottom-0 right-1/4 w-[400px] h-[400px] rounded-full blur-[150px] pointer-events-none opacity-15" style="background: var(--accent-red-dark);"></div>
    <div aria-hidden="true" class="absolute inset-0 grid-pattern pointer-events-none"></div>

    @include('partials.landing-nav')
    @include('partials.landing-nav-js')

    <!-- ── HERO ── -->
    <section class="relative pt-40 pb-16">
        <div class="relative z-10 max-w-7xl mx-auto container-pad text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 glass-card rounded-full mb-8 animate-fadeInUp">
                <span class="relative flex w-2 h-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-accent opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-accent"></span>
                </span>
                <span class="text-xs sm:text-sm text-white/85 font-medium">{{ __('landing.contact.hero_badge') }}</span>
            </div>
            <h1 class="font-display text-white mb-5 animate-fadeInUp delay-100">
                {{ __('landing.contact.hero_title_prefix') }} <span class="text-red-accent">{{ __('landing.contact.hero_title_highlight') }}</span>
            </h1>
            <p class="text-lg sm:text-xl text-white/60 max-w-xl mx-auto animate-fadeInUp delay-200">
                {{ __('landing.contact.hero_subtitle') }}
            </p>
        </div>
    </section>

    <!-- ── CONTACT SECTION ── -->
    <section class="relative pb-32">
        <div class="relative z-10 max-w-6xl mx-auto container-pad">
            <div class="contact-grid">

                <!-- ── LEFT: Info ── -->
                <div class="info-panel">
                    <div class="relative z-10">
                        <h2 class="font-display text-white text-2xl font-bold mb-2">{{ __('landing.contact.info_title') }}</h2>
                        <p class="text-white/50 text-sm mb-8 leading-relaxed">{{ __('landing.contact.info_desc') }}</p>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-white/40 font-medium uppercase tracking-wider mb-1">{{ __('landing.contact.email_label') }}</p>
                                <a href="mailto:hola@kosta.studio" class="text-white font-semibold hover:text-white/80 transition-colors">hola@kosta.studio</a>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-white/40 font-medium uppercase tracking-wider mb-1">{{ __('landing.contact.whatsapp_label') }}</p>
                                <a href="https://wa.me/573113894136" target="_blank" rel="noopener" class="text-white font-semibold hover:text-red-accent transition-colors">{{ __('landing.contact.whatsapp_action') }}</a>
                                <p class="text-xs text-white/35 mt-0.5">{{ __('landing.contact.whatsapp_reply') }}</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-white/40 font-medium uppercase tracking-wider mb-1">{{ __('landing.contact.location_label') }}</p>
                                <p class="text-white font-semibold">{{ __('landing.contact.location_value') }}</p>
                                <p class="text-xs text-white/35 mt-0.5">{{ __('landing.contact.location_desc') }}</p>
                            </div>
                        </div>

                        <!-- Trust badges -->
                        <div class="mt-8 space-y-2">
                            <div class="trust-badge">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ __('landing.contact.trust_24h') }}</span>
                            </div>
                            <div class="trust-badge">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span>{{ __('landing.contact.trust_free_proposal') }}</span>
                            </div>
                            <div class="trust-badge">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ __('landing.contact.trust_experience') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── RIGHT: Form ── -->
                <div class="form-panel">
                    @if($enviado)
                        <!-- SUCCESS STATE -->
                        <div class="success-panel">
                            <div class="success-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h3 class="font-display text-white text-2xl font-bold mb-3">{{ __('landing.contact.success_title') }}</h3>
                            <p class="text-white/55 text-base mb-8 max-w-sm mx-auto leading-relaxed">
                                {{ __('landing.contact.success_desc') }}
                            </p>
                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <a href="{{ route('portfolio') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl border border-white/10 bg-white/05 text-white/70 hover:text-white hover:border-white/20 transition-all text-sm font-medium">
                                    {{ __('landing.contact.success_btn_portfolio') }}
                                </a>
                                <a href="{{ route('landing') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-white text-zinc-950 text-sm font-semibold hover:bg-zinc-200 transition-colors">
                                    {{ __('landing.contact.success_btn_home') }}
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- FORM STATE -->
                        <div class="mb-8">
                            <h2 class="font-display text-white text-2xl font-bold mb-1">{{ __('landing.contact.form_title') }}</h2>
                            <p class="text-white/45 text-sm">{!! __('landing.contact.form_subtitle', ['req' => '<span class="text-red-accent">*</span>']) !!}</p>
                        </div>

                        <form wire:submit.prevent="enviar" novalidate>
                            <div class="form-row">
                                <div class="field-group">
                                    <label class="field-label" for="nombre">{{ __('landing.contact.field_name') }} <span class="req">*</span></label>
                                    <input id="nombre" type="text" wire:model.defer="nombre" class="field-input" placeholder="{{ __('landing.contact.field_name_placeholder') }}" autocomplete="name">
                                    @error('nombre') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                                <div class="field-group">
                                    <label class="field-label" for="email">{{ __('landing.contact.field_email') }} <span class="req">*</span></label>
                                    <input id="email" type="email" wire:model.defer="email" class="field-input" placeholder="{{ __('landing.contact.field_email_placeholder') }}" autocomplete="email">
                                    @error('email') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="field-group">
                                    <label class="field-label" for="telefono">{{ __('landing.contact.field_phone') }}</label>
                                    <input id="telefono" type="tel" wire:model.defer="telefono" class="field-input" placeholder="{{ __('landing.contact.field_phone_placeholder') }}" autocomplete="tel">
                                    @error('telefono') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                                <div class="field-group">
                                    <label class="field-label" for="servicio">{{ __('landing.contact.field_service') }} <span class="req">*</span></label>
                                    <select id="servicio" wire:model.defer="servicio" class="field-select">
                                        <option value="">{{ __('landing.contact.field_service_placeholder') }}</option>
                                        @foreach(__('landing.contact.field_service_options') as $val => $text)
                                            <option value="{{ $val }}">{{ $text }}</option>
                                        @endforeach
                                    </select>
                                    @error('servicio') <span class="field-error">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="field-group">
                                <label class="field-label" for="presupuesto">{{ __('landing.contact.field_budget') }}</label>
                                <select id="presupuesto" wire:model.defer="presupuesto" class="field-select">
                                    <option value="">{{ __('landing.contact.field_budget_placeholder') }}</option>
                                    @foreach(__('landing.contact.field_budget_options') as $val => $text)
                                        <option value="{{ $val }}">{{ $text }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field-group">
                                <label class="field-label" for="mensaje">{{ __('landing.contact.field_message') }}</label>
                                <textarea id="mensaje" wire:model.defer="mensaje" class="field-textarea" placeholder="{{ __('landing.contact.field_message_placeholder') }}"></textarea>
                                @error('mensaje') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit" class="btn-submit" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    {{ __('landing.contact.btn_submit') }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </span>
                                <span wire:loading class="flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                     {{ __('landing.contact.btn_submitting') }}
                                </span>
                            </button>

                            <p class="text-xs text-white/30 text-center mt-4">
                                {{ __('landing.contact.privacy_note') }}
                                 <a href="{{ route('privacy') }}" class="text-white/50 hover:text-white underline transition-colors">{{ __('landing.contact.privacy_link') }}</a>.
                                {{ __('landing.contact.privacy_suffix') }}
                            </p>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </section>

    @include('partials.landing-footer')
</div>
