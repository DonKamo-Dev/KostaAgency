<div class="bg-dark-gradient min-h-screen relative">
    <!-- SEO / Head Meta via component -->
    <x-slot:title>{{ $service['title'] }} — Kosta Agency</x-slot:title>

    @php
        $resolveImg = function (?string $path, string $fallback = 'img/hero/classy-cartagena.png') {
            if (!$path) return asset($fallback);
            return str_starts_with($path, 'http') ? $path : asset(ltrim($path, '/'));
        };
        $heroBgUrl = $resolveImg($service['hero_bg'] ?? null, 'img/hero/kosta-film-still.jpg');
        $heroMainUrl = $resolveImg($service['hero_main_image'] ?? ($service['card_image'] ?? null), 'img/hero/classy-cartagena.png');
        $heroOverlayUrl = $resolveImg($service['hero_overlay_image'] ?? null, 'img/hero/compulago-ecommerce.jpg');
        $sectionBgUrl = $resolveImg($service['section_bg_image'] ?? ($service['hero_bg'] ?? null), 'img/hero/kosta-film-still.jpg');
    @endphp

    <style>
        .service-detail-hero {
            position: relative;
            padding-top: clamp(116px, 15vh, 168px);
            padding-bottom: clamp(60px, 10vh, 100px);
            overflow: hidden;
        }

        .sd-hero-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 44px;
            align-items: center;
            margin-bottom: 56px;
        }
        @media (min-width: 1024px) {
            .sd-hero-grid {
                grid-template-columns: 1.18fr 0.95fr;
                gap: 52px;
            }
        }

        .sd-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 999px;
            background: rgba(230, 57, 70, 0.14);
            border: 1px solid rgba(230, 57, 70, 0.38);
            color: #FFFFFF;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            margin-bottom: 22px;
            backdrop-filter: blur(10px);
        }

        .sd-title {
            font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: clamp(32px, 4.8vw, 58px);
            font-weight: 800;
            line-height: 1.08;
            color: #FFFFFF;
            letter-spacing: -0.025em;
            margin-bottom: 22px;
            max-width: 900px;
        }

        .sd-subtitle {
            font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: clamp(15.5px, 1.6vw, 19px);
            line-height: 1.65;
            color: rgba(255, 255, 255, 0.68);
            max-width: 720px;
            margin-bottom: 34px;
        }

        /* ===== HERO SUPERIMPOSED SHOWCASE COLLAGE ===== */
        .sd-hero-showcase {
            position: relative;
            width: 100%;
            max-width: 540px;
            margin-inline: auto;
            padding-top: 16px;
            padding-bottom: 38px;
        }

        .sd-showcase-main {
            position: relative;
            width: 90%;
            margin-left: auto;
            border-radius: 22px;
            overflow: hidden;
            background: rgba(16, 16, 20, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.16);
            box-shadow:
                0 32px 80px -12px rgba(0, 0, 0, 0.88),
                0 0 50px -10px rgba(230, 57, 70, 0.22);
            z-index: 10;
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease;
        }
        .sd-showcase-main:hover {
            transform: translateY(-4px) scale(1.01);
            border-color: rgba(255, 255, 255, 0.32);
        }

        .sd-showcase-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 62%;
            border-radius: 18px;
            overflow: hidden;
            background: rgba(14, 14, 18, 0.94);
            border: 1px solid rgba(230, 57, 70, 0.45);
            box-shadow:
                0 28px 65px -8px rgba(0, 0, 0, 0.92),
                0 0 35px -6px rgba(230, 57, 70, 0.35);
            z-index: 20;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            --float-rot: -2.5deg;
            transform: rotate(-2.5deg);
            transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease;
        }
        .sd-showcase-overlay:hover {
            transform: translateY(-4px) rotate(0deg) scale(1.03);
            border-color: rgba(230, 57, 70, 0.75);
        }

        .usa-award-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 5;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px solid rgba(248, 113, 113, 0.45);
            background: linear-gradient(135deg, rgba(153, 27, 27, 0.95), rgba(69, 10, 10, 0.96));
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.48), 0 0 16px rgba(220, 38, 38, 0.28), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            pointer-events: none;
            user-select: none;
        }
        .usa-award-badge svg {
            width: 12px;
            height: 12px;
            flex-shrink: 0;
            color: #fbbf24;
            filter: drop-shadow(0 0 3px rgba(251, 191, 36, 0.65));
        }

        .sd-stat-card {
            background: rgba(18, 18, 22, 0.78);
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 18px;
            padding: 24px 28px;
            backdrop-filter: blur(18px);
            transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .sd-stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(230, 57, 70, 0.45);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.45), 0 0 24px rgba(230, 57, 70, 0.12);
        }

        .sd-card {
            background: rgba(18, 18, 22, 0.88);
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 22px;
            padding: 0 28px 30px 28px;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .sd-card:hover {
            transform: translateY(-6px);
            border-color: rgba(230, 57, 70, 0.45);
            background: linear-gradient(145deg, rgba(230, 57, 70, 0.08), rgba(18, 18, 22, 0.96));
            box-shadow: 0 22px 48px rgba(0, 0, 0, 0.55), 0 0 32px rgba(230, 57, 70, 0.12);
        }

        .sd-card-media {
            position: relative;
            margin: 0 -28px 22px -28px;
            height: 170px;
            overflow: hidden;
            background: #0d0d11;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }
        .sd-card-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.72;
            transition: transform 0.65s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.4s ease;
        }
        .sd-card:hover .sd-card-media img {
            transform: scale(1.07);
            opacity: 0.92;
        }
        .sd-card-media::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(10,10,14,0.15) 0%, rgba(18,18,22,0.96) 100%);
            pointer-events: none;
        }

        .sd-pill-tag {
            padding: 9px 18px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.82);
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.09);
            transition: all 0.25s ease;
        }
        .sd-pill-tag:hover {
            background: rgba(230, 57, 70, 0.14);
            border-color: rgba(230, 57, 70, 0.4);
            color: #FFFFFF;
            transform: translateY(-2px);
        }

        .sd-step-box {
            background: rgba(18, 18, 22, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 30px 24px;
            position: relative;
            backdrop-filter: blur(14px);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }
        .sd-step-box:hover {
            border-color: rgba(230, 57, 70, 0.38);
            transform: translateY(-5px);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.45);
        }

        .sd-cta-banner {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(ellipse at 10% 0%, rgba(230, 57, 70, 0.32) 0%, transparent 60%),
                radial-gradient(ellipse at 90% 100%, rgba(230, 57, 70, 0.2) 0%, transparent 50%),
                linear-gradient(145deg, rgba(35, 14, 18, 0.96) 0%, rgba(20, 10, 12, 0.96) 60%, rgba(12, 8, 10, 0.98) 100%);
            border: 1px solid rgba(230, 57, 70, 0.45);
            border-radius: 28px;
            padding: clamp(40px, 6vw, 70px) clamp(24px, 5vw, 60px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6), 0 0 50px rgba(230, 57, 70, 0.15);
        }
    </style>

    <!-- Background orbs -->
    <div aria-hidden="true" class="fixed top-0 left-1/4 w-[500px] h-[500px] bg-red-accent rounded-full blur-[180px] pointer-events-none animate-pulse-glow opacity-20"></div>
    <div aria-hidden="true" class="fixed bottom-0 right-1/4 w-[400px] h-[400px] rounded-full blur-[150px] pointer-events-none opacity-15" style="background: var(--accent-red-dark);"></div>
    <div aria-hidden="true" class="absolute inset-0 grid-pattern pointer-events-none"></div>

    @include('partials.landing-nav')
    @include('partials.landing-nav-js')

    <!-- ── 1. HERO SECTION (WITH BACKGROUND IMAGE & SUPERIMPOSED COLLAGE) ── -->
    <section class="service-detail-hero">
        <!-- Atmospheric Background Image -->
        <div class="section-bg-media" aria-hidden="true">
            <img src="{{ $heroBgUrl }}" alt="" loading="eager" decoding="async" style="opacity: 0.17;">
        </div>

        <!-- Ambient Red Glow Background -->
        <div aria-hidden="true" class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[450px] rounded-full blur-[200px] pointer-events-none" style="background: radial-gradient(circle, rgba(230, 57, 70, 0.22) 0%, rgba(28, 12, 16, 0.15) 50%, transparent 70%);"></div>

        <div class="relative z-10 max-w-7xl mx-auto container-pad">
            <!-- Breadcrumbs -->
            <nav class="animate-fadeInUp" style="display:flex;align-items:center;gap:10px;margin-bottom:24px;font-size:13px;color:rgba(255,255,255,0.4);">
                <a href="{{ route('landing') }}" wire:navigate style="color:rgba(255,255,255,0.65);text-decoration:none;transition:color .2s;">{{ __('services.ui.breadcrumbs_home') }}</a>
                <span>/</span>
                <a href="{{ route('landing') }}#servicios" wire:navigate style="color:rgba(255,255,255,0.65);text-decoration:none;transition:color .2s;">{{ __('services.ui.breadcrumbs_services') }}</a>
                <span>/</span>
                <span style="color:#FFFFFF;font-weight:600;">{{ $service['title'] }}</span>
            </nav>

            <div class="sd-hero-grid">
                <!-- Left Column: Copy & CTAs -->
                <div>
                    <!-- Pillar Badge -->
                    <div class="sd-badge animate-fadeInUp">
                        <span style="width:7px;height:7px;border-radius:50%;background:#EF4444;box-shadow:0 0 10px #EF4444;"></span>
                        <span>{{ $service['pillar'] }}</span>
                    </div>

                    <!-- Title -->
                    <h1 class="sd-title animate-fadeInUp delay-100">
                        {!! $service['headline'] !!}
                    </h1>

                    <!-- Subtitle -->
                    <p class="sd-subtitle animate-fadeInUp delay-200">
                        {{ $service['subtitle'] }}
                    </p>

                    <!-- Action Buttons -->
                    <div class="animate-fadeInUp delay-300" style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                        <a href="{{ route('contact', ['servicio' => $service['contact_name']]) }}" wire:navigate class="btn-primary" style="padding:16px 34px;font-size:15px;font-weight:700;box-shadow:0 10px 30px rgba(255,255,255,0.15);">
                            {{ __('services.ui.request_quote', ['service' => $service['title']]) }}
                            <svg style="width:16px;height:16px;margin-left:8px;" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                        <a href="{{ route('portfolio') }}" wire:navigate class="btn-secondary" style="padding:16px 28px;font-size:15px;font-weight:600;">
                            {{ __('services.ui.view_success_cases') }}
                        </a>
                    </div>
                </div>

                <!-- Right Column: Superimposed 2-Card Showcase Collage -->
                <div class="animate-fadeInUp delay-200">
                    <div class="sd-hero-showcase">
                        <!-- Ambient glow behind showcase -->
                        <div aria-hidden="true" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-72 h-72 rounded-full blur-[95px] pointer-events-none opacity-55" style="background: radial-gradient(circle, rgba(230, 57, 70, 0.45) 0%, transparent 70%);"></div>

                        <!-- Main Framed Showcase Image -->
                        <div class="sd-showcase-main group">
                            <div class="flex items-center justify-between px-4 py-2.5 border-b border-white/10 bg-black/70">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-red-500/70"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/70"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/70"></span>
                                </div>
                                <span class="text-[11px] font-mono text-white/60 tracking-wider uppercase">{{ $service['slug'] }}.kosta.studio</span>
                                <span class="text-[10px] font-mono font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-0.5 rounded-full border border-emerald-500/25">
                                    {{ $service['hero_main_tag'] ?? 'KOSTA PRO' }}
                                </span>
                            </div>
                            <div class="relative aspect-[16/10] overflow-hidden bg-zinc-950">
                                <img
                                    src="{{ $heroMainUrl }}"
                                    alt="{{ $service['hero_main_label'] ?? $service['title'] }}"
                                    class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-105"
                                    loading="eager"
                                    decoding="async"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent pointer-events-none"></div>
                                <div class="absolute bottom-3.5 left-4 right-4 flex items-center justify-between pointer-events-none">
                                    <div>
                                        <div class="text-sm font-bold text-white drop-shadow">{{ $service['hero_main_label'] ?? $service['title'] }}</div>
                                        <div class="text-[11px] text-white/70">{{ $service['pillar'] }}</div>
                                    </div>
                                    <span class="w-8 h-8 rounded-full bg-white/15 backdrop-blur-md border border-white/25 flex items-center justify-center text-white text-xs">↗</span>
                                </div>
                            </div>
                        </div>

                        <!-- Superimposed Floating Secondary Image Card -->
                        <div class="sd-showcase-overlay animate-float-slow">
                            <div class="relative aspect-[16/10] overflow-hidden bg-zinc-950">
                                <img
                                    src="{{ $heroOverlayUrl }}"
                                    alt="{{ $service['hero_overlay_label'] ?? $service['title'] }}"
                                    class="w-full h-full object-cover"
                                    loading="eager"
                                    decoding="async"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/35 to-transparent pointer-events-none"></div>
                                <div class="absolute top-2.5 left-3 right-3 flex items-center justify-between pointer-events-none">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-black/65 border border-white/15 text-[9.5px] font-mono font-semibold text-white/90 backdrop-blur-md">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                        {{ $service['hero_overlay_tag'] ?? 'VERIFIED' }}
                                    </span>
                                </div>
                                <div class="absolute bottom-2.5 left-3 right-3 pointer-events-none">
                                    <div class="text-xs font-bold text-white drop-shadow">{{ $service['hero_overlay_label'] ?? $service['title'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Metrics / Stats -->
            @if(!empty($service['stats']))
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    @foreach($service['stats'] as $sIdx => $st)
                        <div class="sd-stat-card" data-reveal="up" data-reveal-delay="{{ ($sIdx + 1) * 100 }}">
                            <div style="font-family:'Google Sans Flex',sans-serif;font-size:clamp(28px, 3.5vw, 40px);font-weight:800;color:#FFFFFF;letter-spacing:-0.03em;margin-bottom:6px;">
                                {{ $st['value'] }}
                            </div>
                            <div style="font-size:13.5px;color:rgba(255,255,255,0.58);font-weight:500;">
                                {{ $st['label'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- ── 2. WHAT'S INCLUDED / DELIVERABLES (WITH VISUAL CARD BANNERS) ── -->
    <section class="relative section-spacing overflow-hidden" style="border-top: 1px solid rgba(255,255,255,0.06);background:rgba(10,10,12,0.4);">
        <div class="relative z-10 max-w-7xl mx-auto container-pad">
            <div class="max-w-2xl mb-14" data-reveal="up">
                <span class="eyebrow">{{ __('services.ui.deliverables_eyebrow') }}</span>
                <h2 class="font-display text-white mb-6">
                    {!! str_replace(':service', '<span class="text-red-accent">' . e($service['title']) . '</span>', e(__('services.ui.deliverables_title', ['service' => ':service']))) !!}
                </h2>
                <p class="text-white/60 text-base sm:text-lg leading-relaxed">
                    {{ __('services.ui.deliverables_subtitle') }}
                </p>
            </div>

            <div class="grid sm:grid-cols-2 gap-6 lg:gap-8">
                @php
                    $delivDelays = ['80', '160', '240', '320'];
                @endphp
                @foreach($service['deliverables'] as $idx => $deliv)
                    <div class="sd-card" data-reveal="up" data-reveal-delay="{{ $delivDelays[$idx % 4] }}">
                        <!-- Visual Deliverable Image Banner -->
                        <div class="sd-card-media" aria-hidden="true">
                            <img
                                src="{{ $resolveImg($deliv['image'] ?? ($service['card_image'] ?? null)) }}"
                                alt="{{ $deliv['title'] }}"
                                loading="lazy"
                                decoding="async"
                            >
                            <!-- Superimposed Number & Check Badges on the Image -->
                            <div style="position:absolute;bottom:14px;left:24px;right:24px;z-index:5;display:flex;align-items:center;justify-content:space-between;">
                                <span style="font-family:'Geist Mono',monospace;font-size:13px;font-weight:700;color:#FFFFFF;background:rgba(230,57,70,0.85);border:1px solid rgba(255,255,255,0.3);border-radius:8px;padding:4px 11px;backdrop-filter:blur(8px);box-shadow:0 6px 16px rgba(230,57,70,0.35);">
                                    0{{ $idx + 1 }}
                                </span>
                                <div style="width:36px;height:36px;border-radius:10px;background:rgba(12,12,16,0.75);border:1px solid rgba(255,255,255,0.16);backdrop-filter:blur(8px);display:flex;align-items:center;justify-content:center;color:#FFFFFF;">
                                    <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <h3 style="font-family:'Google Sans Flex',sans-serif;font-size:21px;font-weight:700;color:#FFFFFF;margin-bottom:10px;line-height:1.25;">
                            {{ $deliv['title'] }}
                        </h3>
                        <p style="font-size:14.5px;color:rgba(255,255,255,0.62);line-height:1.65;margin:0;">
                            {{ $deliv['desc'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ── 3. TECH STACK & TOOLS ── -->
    <section class="relative section-spacing overflow-hidden" style="border-top: 1px solid rgba(255,255,255,0.06);">
        <div class="relative z-10 max-w-7xl mx-auto container-pad">
            <div class="text-center max-w-2xl mx-auto mb-12" data-reveal="up">
                <span class="eyebrow">{{ __('services.ui.stack_eyebrow') }}</span>
                <h2 class="font-display text-white mb-4">
                    {{ __('services.ui.stack_title_prefix') }} <span class="text-red-accent">{{ __('services.ui.stack_title_highlight') }}</span>
                </h2>
                <p class="text-white/60 text-base leading-relaxed">
                    {{ __('services.ui.stack_subtitle') }}
                </p>
            </div>

            <div data-reveal="scale" data-reveal-delay="150" style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center;max-width:900px;margin:0 auto;">
                @foreach($service['all_tags'] ?? $service['tags'] as $tech)
                    <span class="sd-pill-tag">
                        {{ $tech }}
                    </span>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ── 4. STEP-BY-STEP PROCESS (WITH ATMOSPHERIC BACKGROUND IMAGE) ── -->
    <section class="relative section-spacing overflow-hidden" style="border-top: 1px solid rgba(255,255,255,0.06);background:rgba(10,10,12,0.5);">
        <!-- Atmospheric Background Image -->
        <div class="section-bg-media" aria-hidden="true">
            <img src="{{ $sectionBgUrl }}" alt="" loading="lazy" decoding="async" style="opacity: 0.12;">
        </div>

        <div class="relative z-10 max-w-7xl mx-auto container-pad">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8 mb-14">
                <div class="max-w-2xl" data-reveal="up">
                    <span class="eyebrow">{{ __('services.ui.process_eyebrow') }}</span>
                    <h2 class="font-display text-white mb-6">
                        {{ __('services.ui.process_title_prefix') }} <span class="text-red-accent">{{ $service['title'] }}</span>
                    </h2>
                    <p class="text-white/60 text-base sm:text-lg leading-relaxed">
                        {{ __('services.ui.process_subtitle') }}
                    </p>
                </div>

                <!-- Superimposed Floating Process Preview Card -->
                <div class="hidden lg:flex items-center gap-3.5 px-4 py-3 rounded-2xl bg-zinc-900/85 border border-white/12 backdrop-blur-xl shadow-2xl animate-float-reverse shrink-0" data-reveal="left" data-reveal-delay="150">
                    <div class="w-12 h-12 rounded-xl overflow-hidden border border-white/15 shrink-0">
                        <img src="{{ $heroMainUrl }}" alt="" class="w-full h-full object-cover" loading="lazy">
                    </div>
                    <div>
                        <div class="text-[10.5px] font-mono uppercase tracking-wider text-red-400 font-bold">4-Stage Sprint</div>
                        <div class="text-xs text-white/85 font-semibold">{{ $service['title'] }} · Kosta Standard</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $procDelays = ['80', '160', '240', '320'];
                @endphp
                @foreach($service['process'] as $pIdx => $step)
                    <div class="sd-step-box" data-reveal="up" data-reveal-delay="{{ $procDelays[$pIdx % 4] }}">
                        <div style="font-family:'Geist Mono',sans-serif;font-size:32px;font-weight:800;color:rgba(230,57,70,0.65);line-height:1;margin-bottom:16px;">
                            {{ $step['step'] }}
                        </div>
                        <h4 style="font-family:'Google Sans Flex',sans-serif;font-size:17px;font-weight:700;color:#FFFFFF;margin-bottom:10px;line-height:1.3;">
                            {{ $step['title'] }}
                        </h4>
                        <p style="font-size:13.5px;color:rgba(255,255,255,0.6);line-height:1.6;margin:0;">
                            {{ $step['desc'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ── 5. RELATED PROJECTS / PORTFOLIO LINK ── -->
    <section class="relative section-spacing overflow-hidden" style="border-top: 1px solid rgba(255,255,255,0.06);">
        <div class="relative z-10 max-w-7xl mx-auto container-pad">
            <div data-reveal="up" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:20px;margin-bottom:36px;">
                <div>
                    <span class="eyebrow">{{ __('services.ui.related_eyebrow') }}</span>
                    <h2 class="font-display text-white">
                        {{ __('services.ui.related_title_prefix') }} <span class="text-red-accent">{{ $service['title'] }}</span>
                    </h2>
                </div>
                <a href="{{ route('portfolio') }}" wire:navigate class="btn-secondary" style="font-size:14px;padding:10px 20px;">
                    {{ __('services.ui.view_all_projects') }}
                </a>
            </div>

            @if($relatedStudies->isNotEmpty())
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedStudies as $rIdx => $st)
                        <div data-reveal="up" data-reveal-delay="{{ ($rIdx % 3 + 1) * 100 }}"
                             style="background:#131316;border:1px solid rgba(255,255,255,0.08);border-radius:18px;overflow:hidden;transition:transform .3s ease, border-color .3s ease;"
                             onmouseenter="this.style.borderColor='rgba(230,57,70,0.4)';this.style.transform='translateY(-4px)'"
                             onmouseleave="this.style.borderColor='rgba(255,255,255,0.08)';this.style.transform='translateY(0)'">
                            <div style="height:180px;position:relative;background:linear-gradient(135deg, {{ $st->gradient_inicio }}, {{ $st->gradient_fin }});overflow:hidden;">
                                @if($st->image_url)
                                    <button type="button" class="case-preview-trigger" data-case-preview data-image="{{ $st->image_url }}" data-title="{{ $st->titulo }}" data-url="{{ $st->public_url ?? '' }}" aria-label="Ver imagen ampliada de {{ $st->titulo }}">
                                        <img src="{{ $st->image_url }}" alt="{{ $st->titulo }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                                        <span class="case-preview-hint">Ampliar</span>
                                    </button>
                                @else
                                    <img src="{{ $heroMainUrl }}" alt="{{ $st->titulo }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.45;mix-blend-mode:luminosity;">
                                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#FFFFFF;font-family:'Google Sans Flex',sans-serif;font-weight:700;text-shadow:0 4px 16px rgba(0,0,0,0.8);">
                                        {{ $st->titulo }}
                                    </div>
                                @endif
                                @if($st->proyecto_usa)
                                    <span class="usa-award-badge" aria-label="Distintivo: Proyecto USA">
                                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2.75 2.72 5.52 6.09.88-4.4 4.29 1.04 6.06L12 16.64 6.55 19.5l1.04-6.06-4.4-4.29 6.09-.88L12 2.75Z"/></svg>
                                        Proyecto USA
                                    </span>
                                @endif
                            </div>
                            <div style="padding:22px;">
                                <h4 style="font-family:'Google Sans Flex',sans-serif;font-size:18px;font-weight:700;color:#FFFFFF;margin-bottom:8px;">
                                    @if($st->image_url)
                                        <button type="button"
                                                class="project-title-link"
                                                data-case-preview
                                                data-image="{{ $st->image_url }}"
                                                data-title="{{ $st->titulo }}"
                                                data-url="{{ $st->public_url ?? '' }}"
                                                aria-label="Ver imagen ampliada de {{ $st->titulo }}">
                                            {{ $st->titulo }}
                                        </button>
                                    @else
                                        {{ $st->titulo }}
                                    @endif
                                </h4>
                                @if($st->descripcion)
                                    <p style="font-size:13px;color:rgba(255,255,255,0.5);line-height:1.5;margin-bottom:14px;">
                                        {{ \Illuminate\Support\Str::limit($st->descripcion, 90) }}
                                    </p>
                                @endif
                                @if($st->metrica_valor)
                                    <div style="display:inline-flex;align-items:baseline;gap:6px;padding:4px 10px;background:rgba(230,57,70,0.12);border-radius:6px;font-size:12px;font-weight:700;color:#FFFFFF;">
                                        <span>{{ $st->metrica_valor }}</span>
                                        <span style="font-weight:400;color:rgba(255,255,255,0.6);">{{ $st->metrica_label }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div data-reveal="scale" style="position:relative;overflow:hidden;background:rgba(18,18,22,0.75);border:1px solid rgba(255,255,255,0.1);border-radius:22px;padding:52px 24px;text-align:center;">
                    <img src="{{ $heroMainUrl }}" alt="" aria-hidden="true" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.1;pointer-events:none;">
                    <div style="position:relative;z-index:2;">
                        <div style="font-family:'Google Sans Flex',sans-serif;font-size:19px;font-weight:700;color:#FFFFFF;margin-bottom:8px;">
                            {{ __('services.ui.empty_related_title') }}
                        </div>
                        <p style="font-size:14px;color:rgba(255,255,255,0.6);max-width:500px;margin:0 auto 22px;">
                            {{ __('services.ui.empty_related_desc') }}
                        </p>
                        <a href="{{ route('portfolio') }}" wire:navigate class="btn-primary" style="padding:10px 24px;font-size:13.5px;">
                            {{ __('services.ui.explore_full_gallery') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- ── 6. CONVERSION CTA BANNER (WITH BACKGROUND & FLOATING OVERLAY IMAGES) ── -->
    <section class="relative section-spacing">
        <div class="relative max-w-7xl mx-auto container-pad">
            <div class="sd-cta-banner text-center max-w-5xl mx-auto" data-reveal="scale">
                <!-- Background Image Inside CTA Banner -->
                <img
                    src="{{ $heroBgUrl }}"
                    alt=""
                    aria-hidden="true"
                    loading="lazy"
                    decoding="async"
                    style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.14;mix-blend-mode:luminosity;pointer-events:none;"
                >

                <!-- Superimposed Floating Image Card Left (Desktop) -->
                <div aria-hidden="true" class="hidden lg:block absolute -left-6 bottom-6 w-44 rounded-2xl overflow-hidden border border-white/15 shadow-2xl opacity-85 pointer-events-none animate-float-slow" style="--float-rot: -6deg; transform: rotate(-6deg);">
                    <img src="{{ $heroMainUrl }}" alt="" class="w-full h-28 object-cover" loading="lazy">
                </div>

                <!-- Superimposed Floating Image Card Right (Desktop) -->
                <div aria-hidden="true" class="hidden lg:block absolute -right-6 top-6 w-44 rounded-2xl overflow-hidden border border-red-400/35 shadow-2xl opacity-85 pointer-events-none animate-float-reverse" style="--float-rot: 6deg; transform: rotate(6deg);">
                    <img src="{{ $heroOverlayUrl }}" alt="" class="w-full h-28 object-cover" loading="lazy">
                </div>

                <div class="relative z-10">
                    <span class="eyebrow" style="color:rgba(255,255,255,0.75);">{{ __('services.ui.cta_eyebrow') }}</span>
                    <h2 class="font-display text-white mb-6" style="font-size:clamp(30px, 4.5vw, 50px);line-height:1.15;">
                        {{ __('services.ui.cta_title_prefix') }} <span class="text-red-accent">{{ $service['title'] }}</span> {{ __('services.ui.cta_title_suffix') }}
                    </h2>
                    <p class="text-white/75 text-base sm:text-lg max-w-2xl mx-auto mb-10 leading-relaxed">
                        {{ __('services.ui.cta_subtitle') }}
                    </p>
                    <div style="display:flex;align-items:center;justify-content:center;gap:16px;flex-wrap:wrap;">
                        <a href="{{ route('contact', ['servicio' => $service['contact_name']]) }}" wire:navigate class="btn-primary" style="padding:16px 36px;font-size:15px;font-weight:700;">
                            {{ __('services.ui.cta_start_btn', ['service' => $service['title']]) }}
                        </a>
                        <a href="https://wa.me/573113894136?text={{ urlencode(__('services.ui.cta_whatsapp_msg', ['service' => $service['title']])) }}" target="_blank" rel="noopener noreferrer" class="btn-secondary" style="padding:16px 28px;font-size:15px;font-weight:600;">
                            {{ __('services.ui.cta_whatsapp_btn') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── 7. OTHER SERVICES (WITH PREVIEW IMAGES & SCROLL REVEAL) ── -->
    <section class="relative section-spacing" style="border-top:1px solid rgba(255,255,255,0.06);background:#070708;">
        <div class="relative max-w-7xl mx-auto container-pad">
            <div class="mb-10 text-center" data-reveal="up">
                <span class="eyebrow">{{ __('services.ui.other_eyebrow') }}</span>
                <h3 class="font-display text-white" style="font-size:28px;">
                    {{ __('services.ui.other_title_prefix') }} <span class="text-red-accent">Kosta</span>
                </h3>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($otherServices as $other)
                    <a href="{{ route('services.show', $other['slug']) }}" wire:navigate
                       data-reveal="up" data-reveal-delay="{{ ($loop->index + 1) * 100 }}"
                       class="group"
                       style="background:rgba(18,18,22,0.8);border:1px solid rgba(255,255,255,0.08);border-radius:20px;overflow:hidden;text-decoration:none;display:flex;flex-direction:column;justify-content:space-between;transition:all .35s ease;"
                       onmouseenter="this.style.borderColor='rgba(230,57,70,0.45)';this.style.transform='translateY(-5px)'"
                       onmouseleave="this.style.borderColor='rgba(255,255,255,0.08)';this.style.transform='translateY(0)'">
                        <!-- Visual Thumbnail Header -->
                        <div style="position:relative;height:132px;overflow:hidden;background:#0d0d11;border-bottom:1px solid rgba(255,255,255,0.06);">
                            <img
                                src="{{ $resolveImg($other['card_image'] ?? null) }}"
                                alt="{{ $other['title'] }}"
                                loading="lazy"
                                decoding="async"
                                style="width:100%;height:100%;object-fit:cover;opacity:0.68;transition:transform .6s ease;"
                                class="group-hover:scale-105"
                            >
                            <div style="position:absolute;inset:0;background:linear-gradient(180deg, rgba(10,10,14,0.15) 0%, rgba(18,18,22,0.95) 100%);"></div>
                            <span style="position:absolute;top:12px;left:16px;font-size:10px;font-weight:700;color:rgba(255,255,255,0.85);background:rgba(0,0,0,0.6);border:1px solid rgba(255,255,255,0.15);padding:3px 10px;border-radius:999px;letter-spacing:0.12em;text-transform:uppercase;backdrop-filter:blur(8px);">
                                {{ $other['pillar'] }}
                            </span>
                        </div>

                        <div style="padding:20px 22px 22px 22px;display:flex;flex-direction:column;flex-grow:1;justify-content:space-between;">
                            <div>
                                <h4 style="font-family:'Google Sans Flex',sans-serif;font-size:18px;font-weight:700;color:#FFFFFF;margin-bottom:8px;">
                                    {{ $other['title'] }}
                                </h4>
                                <p style="font-size:13px;color:rgba(255,255,255,0.55);line-height:1.55;margin:0 0 16px;">
                                    {{ \Illuminate\Support\Str::limit($other['subtitle'], 95) }}
                                </p>
                            </div>
                            <div style="display:flex;align-items:center;gap:6px;font-size:12.5px;font-weight:600;color:var(--accent-red);">
                                <span>{{ $other['btn_text'] ?? __('services.ui.other_learn_more') }}</span>
                                <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @include('partials.landing-footer')
    <x-case-study-viewer />
</div>
