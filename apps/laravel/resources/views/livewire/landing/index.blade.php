<div class="bg-dark-gradient min-h-screen relative">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&display=swap');

        *, *::before, *::after {
            font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }

        .hero-section { padding-top: 112px; padding-bottom: 36px; }
        @media (min-width: 768px) { .hero-section { padding-top: 120px; padding-bottom: 40px; } }
        @media (min-width: 1024px) { .hero-section { padding-top: 124px; padding-bottom: 48px; } }

        .hero-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 40px;
            align-items: center;
        }
        @media (min-width: 1024px) {
            .hero-grid {
                grid-template-columns: 1.25fr 1fr;
                gap: 48px;
            }
        }
        @media (min-width: 1280px) {
            .hero-grid {
                grid-template-columns: 1.28fr 1fr;
                gap: 56px;
            }
        }

        .hero-mockup-col { order: 2; width: 100%; }

        .hero-headline {
            font-size: clamp(26px, 3.8vw, 44px);
            line-height: 1.12;
            letter-spacing: -0.03em;
            font-weight: 800;
        }
        @media (min-width: 1440px) {
            .hero-headline {
                font-size: 48px;
                line-height: 1.1;
            }
        }

        /* ===== SELECTED WORKS SHOWCASE ===== */
        .hero-showcase {
            position: relative;
            width: 100%;
            max-width: 520px;
            margin-inline: auto;
            padding-top: 24px;
            padding-bottom: 28px;
        }
        @media (min-width: 1024px) {
            .hero-showcase {
                max-width: 560px;
                margin-right: 0;
                margin-left: auto;
                padding-top: 28px;
                padding-bottom: 32px;
            }
        }

        .hero-showcase__card {
            position: relative;
            display: block;
            text-decoration: none;
            border-radius: 18px;
            overflow: hidden;
            background: rgba(18, 18, 22, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 24px 60px -10px rgba(0, 0, 0, 0.75);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1),
                        box-shadow 0.4s ease,
                        border-color 0.4s ease;
            will-change: transform;
        }

        /* Card 1: Classy (Principal) */
        .hero-showcase__card--main {
            width: 88%;
            margin-top: 36px;
            margin-left: 2%;
            z-index: 20;
            border-color: rgba(255, 255, 255, 0.16);
            box-shadow: 0 32px 85px -15px rgba(0, 0, 0, 0.85),
                        0 0 50px -10px rgba(230, 57, 70, 0.14);
        }
        .hero-showcase__card--main:hover {
            transform: translateY(-4px) scale(1.015);
            border-color: rgba(255, 255, 255, 0.32);
            box-shadow: 0 40px 95px -15px rgba(0, 0, 0, 0.95),
                        0 0 65px -8px rgba(230, 57, 70, 0.25);
            z-index: 25;
        }

        /* Card 2: Compulago (Secundaria - Derecha Superior) */
        .hero-showcase__card--secondary {
            position: absolute;
            top: 0;
            right: 0;
            width: 74%;
            z-index: 10;
            transform: rotate(2deg);
            opacity: 0.94;
        }
        .hero-showcase__card--secondary:hover {
            transform: translateY(-4px) rotate(0deg) scale(1.02);
            opacity: 1;
            z-index: 35;
            border-color: rgba(230, 57, 70, 0.45);
            box-shadow: 0 32px 80px -10px rgba(0, 0, 0, 0.9);
        }

        /* Card 3: Audiovisual Film (Terciaria - Izquierda Inferior) */
        .hero-showcase__card--film {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 58%;
            z-index: 30;
            transform: rotate(-2deg);
            border-color: rgba(230, 57, 70, 0.35);
            box-shadow: 0 28px 70px -8px rgba(0, 0, 0, 0.85),
                        0 0 35px -6px rgba(230, 57, 70, 0.3);
        }
        .hero-showcase__card--film:hover {
            transform: translateY(-4px) rotate(0deg) scale(1.03);
            border-color: rgba(230, 57, 70, 0.65);
            box-shadow: 0 36px 90px -8px rgba(0, 0, 0, 0.95),
                        0 0 50px -4px rgba(230, 57, 70, 0.45);
            z-index: 40;
        }

        @media (max-width: 640px) {
            .hero-showcase {
                padding-top: 16px;
                padding-bottom: 20px;
            }
            .hero-showcase__card--main {
                width: 90%;
                margin-top: 28px;
                margin-left: 0;
            }
            .hero-showcase__card--secondary {
                width: 75%;
                transform: rotate(1.5deg);
            }
            .hero-showcase__card--film {
                width: 62%;
                transform: rotate(-1.5deg);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .hero-showcase__card,
            .hero-showcase__card--main,
            .hero-showcase__card--secondary,
            .hero-showcase__card--film {
                transform: none !important;
                transition: none !important;
            }
        }

        .cta-group { display: flex; flex-direction: column; gap: 12px; width: 100%; }
        @media (min-width: 640px) { .cta-group { flex-direction: row; gap: 16px; width: auto; } }

        .cta-group .btn-primary,
        .cta-group .btn-secondary { width: 100%; }
        @media (min-width: 640px) {
            .cta-group .btn-primary,
            .cta-group .btn-secondary { width: auto; }
        }

        /* ===== SERVICE CARDS ===== */
        .service-card {
            background: linear-gradient(145deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            padding: 40px 36px;
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            cursor: pointer;
        }
        @media (min-width: 768px) { .service-card { padding: 48px 40px; } }

        .service-card-link {
            position: absolute;
            inset: 0;
            z-index: 20;
            border-radius: inherit;
        }
        .service-card-link:focus-visible {
            outline: 2px solid var(--accent-red, #FFFFFF);
            outline-offset: -2px;
        }

        .service-card:hover {
            transform: translateY(-8px);
            border-color: rgba(230, 57, 70, 0.4);
            background: linear-gradient(145deg, rgba(230, 57, 70, 0.08), rgba(230, 57, 70, 0.02));
            box-shadow: 0 20px 60px rgba(230, 57, 70, 0.15), 0 8px 24px rgba(0,0,0,0.3);
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(230, 57, 70, 0.25) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.5s ease;
            pointer-events: none;
        }
        .service-card:hover::before { opacity: 1; }

        .service-icon {
            width: 72px; height: 72px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(230, 57, 70, 0.2), rgba(139, 26, 37, 0.15));
            border: 1px solid rgba(230, 57, 70, 0.25);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 32px;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1),
                        background 0.4s ease,
                        box-shadow 0.4s ease;
            position: relative;
            z-index: 1;
            box-shadow: 0 8px 24px rgba(230, 57, 70, 0.15);
        }

        /* Single merged hover rule — no conflict */
        .service-card:hover .service-icon {
            transform: scale(1.12) translateY(-4px);
            background: linear-gradient(135deg, rgba(230, 57, 70, 0.4), rgba(230, 57, 70, 0.2));
            box-shadow: 0 16px 40px rgba(230, 57, 70, 0.4);
        }

        .service-icon svg {
            width: 36px; height: 36px;
            color: var(--accent-red);
            transition: transform 0.4s var(--ease-out), color 0.4s var(--ease-out);
        }
        .service-card:hover .service-icon svg { transform: scale(1.1); color: #FF6B6B; }

        .service-title {
            font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 22px; font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 12px;
            letter-spacing: -0.01em;
            line-height: 1.2;
            position: relative; z-index: 1;
        }
        @media (min-width: 768px) { .service-title { font-size: 24px; } }

        .service-pillar {
            display: inline-flex;
            margin-bottom: 14px;
            color: rgba(255,255,255,0.42);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            position: relative;
            z-index: 1;
        }

        .service-desc {
            color: rgba(255,255,255,0.55);
            font-size: 15px; line-height: 1.65;
            margin-bottom: 28px;
            flex-grow: 1;
            position: relative; z-index: 1;
        }

        .service-tags { display: flex; flex-wrap: wrap; gap: 8px; position: relative; z-index: 1; }

        .service-tag {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px; font-weight: 500;
            color: rgba(255,255,255,0.65);
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            transition: all 0.3s ease;
        }
        .service-card:hover .service-tag {
            border-color: rgba(230, 57, 70, 0.3);
            background: rgba(230, 57, 70, 0.08);
            color: rgba(255, 255, 255, 0.85);
        }

        .service-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 12px 18px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .service-card:hover .service-action-btn {
            border-color: rgba(230, 57, 70, 0.45);
            background: rgba(230, 57, 70, 0.16);
            box-shadow: 0 6px 20px rgba(230, 57, 70, 0.25);
            transform: translateY(-2px);
        }
        .service-action-arrow {
            width: 16px;
            height: 16px;
            color: rgba(255, 255, 255, 0.6);
            transition: transform 0.3s ease, color 0.3s ease;
            flex-shrink: 0;
        }
        .service-card:hover .service-action-arrow,
        .service-action-btn:hover .service-action-arrow {
            transform: translateX(4px);
            color: #FFFFFF;
        }

        /* ===== COMPARISON SECTION (SIN KOSTA VS CON KOSTA) ===== */
        .comparison-card-kosta {
            background:
                radial-gradient(ellipse at 15% 0%, rgba(230, 57, 70, 0.32) 0%, transparent 60%),
                radial-gradient(ellipse at 85% 100%, rgba(230, 57, 70, 0.16) 0%, transparent 50%),
                linear-gradient(145deg, rgba(46, 17, 23, 0.98) 0%, rgba(24, 11, 14, 0.98) 55%, rgba(14, 8, 10, 0.99) 100%);
            border: 1px solid rgba(230, 57, 70, 0.38);
            border-radius: 24px;
            padding: 40px 32px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            position: relative;
            box-shadow: 0 24px 60px rgba(230, 57, 70, 0.18), 0 10px 28px rgba(0, 0, 0, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease, border-color 0.3s ease;
        }
        .comparison-card-kosta:hover {
            transform: translateY(-4px);
            border-color: rgba(230, 57, 70, 0.55);
            box-shadow: 0 30px 70px rgba(230, 57, 70, 0.28), 0 14px 34px rgba(0, 0, 0, 0.5);
        }
        .comparison-card-sin {
            background: rgba(18, 18, 20, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 40px 32px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            position: relative;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
        }
        .comparison-card-sin:hover {
            transform: translateY(-4px);
            border-color: rgba(255, 255, 255, 0.16);
        }
        .comparison-badge-kosta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(230, 57, 70, 0.16);
            border: 1px solid rgba(230, 57, 70, 0.35);
            color: #FFFFFF;
            font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.01em;
            width: fit-content;
            box-shadow: 0 2px 10px rgba(230, 57, 70, 0.15);
        }
        .comparison-badge-sin {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #A1A1AA;
            font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.01em;
            width: fit-content;
        }
        .comparison-title-kosta {
            font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 30px;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.025em;
            margin: 18px 0 4px 0;
            line-height: 1.15;
        }
        .comparison-subtitle-kosta {
            font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 13.5px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 24px;
            line-height: 1.4;
        }
        .comparison-title-sin {
            font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 30px;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.025em;
            margin: 18px 0 4px 0;
            line-height: 1.15;
        }
        .comparison-subtitle-sin {
            font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 13.5px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.52);
            margin-bottom: 24px;
            line-height: 1.4;
        }
        .comparison-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .comparison-item-kosta {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif;
            color: rgba(255, 255, 255, 0.84);
            font-size: 14px;
            font-weight: 400;
            line-height: 1.55;
            letter-spacing: -0.01em;
        }
        .comparison-item-kosta strong {
            font-weight: 600;
            color: #FFFFFF;
        }
        .comparison-item-sin {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif;
            color: rgba(255, 255, 255, 0.72);
            font-size: 14px;
            font-weight: 400;
            line-height: 1.55;
            letter-spacing: -0.01em;
        }
        .comparison-item-sin strong {
            font-weight: 600;
            color: #FFFFFF;
        }
        .comp-icon-check {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: rgba(230, 57, 70, 0.22);
            border: 1px solid rgba(230, 57, 70, 0.38);
            color: #FF5A68;
            font-size: 11px;
            font-weight: 900;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .comp-icon-x {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.75);
            font-size: 11px;
            font-weight: 800;
            flex-shrink: 0;
            margin-top: 2px;
        }
    </style>

    <div aria-hidden="true" class="fixed top-0 left-1/4 w-[500px] h-[500px] bg-red-accent rounded-full blur-[180px] pointer-events-none animate-pulse-glow opacity-30"></div>
    <div aria-hidden="true" class="fixed bottom-0 right-1/4 w-[400px] h-[400px] rounded-full blur-[150px] pointer-events-none opacity-20" style="background: var(--accent-red-dark);"></div>
    <div aria-hidden="true" class="absolute inset-0 grid-pattern pointer-events-none"></div>

    @include('partials.landing-nav')
    @include('partials.landing-nav-js')

    <!-- HERO -->
    <main id="main">
    <section class="relative overflow-hidden hero-section" aria-labelledby="hero-heading">
        <div class="relative z-10 max-w-7xl mx-auto container-pad w-full">
            <div class="hero-grid">
                <div class="hero-text-col">
                    <div class="inline-flex items-center gap-2 px-4 py-2 glass-card rounded-full mb-8 animate-fadeInUp">
                        <span class="relative flex w-2 h-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-accent opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-accent"></span>
                        </span>
                        <span class="text-xs sm:text-sm text-white/85 font-medium">{{ __('landing.hero.accepting_prefix') }} {{ now()->locale(app()->getLocale())->isoFormat('MMMM') }}</span>
                    </div>

                    <h1 id="hero-heading" class="font-display hero-headline text-white mb-6 animate-fadeInUp delay-100">
                        <span class="block text-white">{{ __('landing.hero.title_line_1') }}</span>
                        <span class="block text-gradient">{{ __('landing.hero.title_line_2') }}</span>
                        <span class="block text-white">{{ __('landing.hero.title_line_3') }}</span>
                    </h1>

                    <p class="text-base sm:text-lg lg:text-xl text-white/70 mb-7 max-w-xl leading-relaxed animate-fadeInUp delay-200">
                        {{ __('landing.hero.subtitle') }}
                    </p>

                    <div class="cta-group mb-8 animate-fadeInUp delay-300">
                        <a href="#contacto" class="btn-primary group">
                            <span>{{ __('landing.hero.cta_start') }}</span>
                            <span class="text-base font-normal transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" aria-hidden="true">↗</span>
                        </a>
                        <a href="{{ route('portfolio') }}" class="btn-secondary group">
                            <span>{{ __('landing.hero.cta_projects') }}</span>
                            <span class="text-base font-normal transition-transform duration-200 group-hover:translate-x-1" aria-hidden="true">→</span>
                        </a>
                    </div>

                    <div class="flex flex-wrap items-center gap-8 animate-fadeInUp delay-400">
                        <div class="flex items-center gap-3">
                            <div class="flex -space-x-2">
                                <div class="w-9 h-9 rounded-full border-2 border-black flex items-center justify-center text-xs font-bold text-white" style="background: linear-gradient(135deg, #E63946, #8B1A25);">M</div>
                                <div class="w-9 h-9 rounded-full border-2 border-black flex items-center justify-center text-xs font-bold text-white" style="background: linear-gradient(135deg, #FF6B6B, #C92A2A);">A</div>
                                <div class="w-9 h-9 rounded-full border-2 border-black flex items-center justify-center text-xs font-bold text-white" style="background: linear-gradient(135deg, #FF8787, #E03131);">L</div>
                                <div class="w-9 h-9 rounded-full border-2 border-black bg-white/10 backdrop-blur flex items-center justify-center text-xs font-bold text-white">+47</div>
                            </div>
                            <div>
                                <div class="flex items-center gap-1" aria-label="{{ __('landing.hero.stars_aria') }}">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-4 h-4 text-red-accent" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-xs text-white/55 mt-1">{{ __('landing.hero.satisfied_clients') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hero-mockup-col animate-fadeInUp delay-200">
                    <div class="hero-showcase">
                        <!-- Ambient luxury backlight glow -->
                        <div aria-hidden="true" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[340px] h-[340px] sm:w-[420px] sm:h-[420px] rounded-full blur-[110px] pointer-events-none opacity-45" style="background: radial-gradient(circle, rgba(230, 57, 70, 0.4) 0%, rgba(139, 26, 37, 0.2) 50%, transparent 75%);"></div>

                        <!-- Card 2: Compulago (Secondary - Back Right) -->
                        <a href="https://compulago.net" target="_blank" rel="noopener noreferrer" class="hero-showcase__card hero-showcase__card--secondary group" aria-label="Compulago - {{ __('landing.hero.card_compulago_tag') }}">
                            <div class="flex items-center justify-between px-3.5 py-2 border-b border-white/5 bg-black/40">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-white/25"></span>
                                    <span class="w-2 h-2 rounded-full bg-white/15"></span>
                                    <span class="w-2 h-2 rounded-full bg-white/10"></span>
                                </div>
                                <span class="text-[10.5px] font-mono text-white/50 tracking-wider">compulago.net</span>
                                <span class="text-[9.5px] font-semibold text-emerald-400/90 uppercase tracking-wider bg-emerald-500/10 px-2 py-0.5 rounded-full border border-emerald-500/20">E-COMMERCE</span>
                            </div>
                            <div class="relative aspect-[16/9] overflow-hidden bg-zinc-950">
                                <img
                                    src="{{ asset('img/hero/compulago-ecommerce.jpg') }}"
                                    alt="Compulago E-commerce"
                                    class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105"
                                    loading="eager"
                                    decoding="async"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent pointer-events-none"></div>
                                <div class="absolute bottom-2 left-3 right-3 flex items-center justify-between pointer-events-none">
                                    <span class="text-xs font-semibold text-white/95 drop-shadow">{{ __('landing.hero.card_compulago_title') }}</span>
                                    <span class="text-[10.5px] text-white/70 drop-shadow">{{ __('landing.hero.card_compulago_tag') }}</span>
                                </div>
                            </div>
                        </a>

                        <!-- Card 1: Classy Cartagena (Main - Front Center) -->
                        <a href="https://classycartagena.com" target="_blank" rel="noopener noreferrer" class="hero-showcase__card hero-showcase__card--main group" aria-label="Classy Cartagena - {{ __('landing.hero.card_classy_tag') }}">
                            <div class="flex items-center justify-between px-4 py-2.5 border-b border-white/10 bg-black/60">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-red-500/60"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/60"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/60"></span>
                                </div>
                                <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[11px] font-mono text-white/75">
                                    <svg class="w-2.5 h-2.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>classycartagena.com</span>
                                </div>
                                <div class="flex items-center gap-1 text-[10.5px] font-medium text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full border border-emerald-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>{{ __('landing.hero.live_site') }} ↗</span>
                                </div>
                            </div>
                            <div class="relative aspect-[16/9.5] overflow-hidden bg-zinc-950">
                                <img
                                    src="{{ asset('img/hero/classy-cartagena.png') }}"
                                    alt="Classy Cartagena Luxury Tourism & Booking"
                                    class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-[1.03]"
                                    loading="eager"
                                    decoding="async"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent pointer-events-none"></div>
                                <div class="absolute bottom-3 left-4 right-4 flex items-center justify-between pointer-events-none">
                                    <div>
                                        <div class="text-sm sm:text-base font-bold text-white drop-shadow">{{ __('landing.hero.card_classy_title') }}</div>
                                        <div class="text-[11px] text-white/75 drop-shadow">{{ __('landing.hero.card_classy_tag') }}</div>
                                    </div>
                                    <span class="text-xs text-white/80 font-medium px-2.5 py-1 rounded-md bg-white/10 backdrop-blur border border-white/15">Web & Luxury</span>
                                </div>
                            </div>
                        </a>

                        <!-- Card 3: Producción Audiovisual (Front Left Cinematic) -->
                        <a href="{{ route('portfolio') }}" class="hero-showcase__card hero-showcase__card--film group" aria-label="KOSTA FILMS - {{ __('landing.hero.card_film_tag') }}">
                            <div class="relative aspect-video overflow-hidden bg-black">
                                <img
                                    src="{{ asset('img/hero/kosta-film-still.jpg') }}"
                                    alt="KOSTA Audiovisual Production Still"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    loading="eager"
                                    decoding="async"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-black/30 pointer-events-none"></div>

                                <!-- Cinematic HUD elements -->
                                <div class="absolute top-2.5 left-3 right-3 flex items-center justify-between pointer-events-none">
                                    <div class="flex items-center gap-1.5 px-2 py-0.5 rounded bg-black/60 backdrop-blur border border-white/10 text-[9.5px] font-mono tracking-widest text-white/90">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-accent animate-pulse"></span>
                                        <span>REC</span>
                                    </div>
                                    <span class="text-[9.5px] font-mono tracking-widest text-white/70 bg-black/60 px-2 py-0.5 rounded backdrop-blur border border-white/10">4K CINEMA</span>
                                </div>

                                <!-- Center Play Badge -->
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/15 backdrop-blur-md border border-white/30 flex items-center justify-center text-white transition-all duration-300 group-hover:scale-110 group-hover:bg-red-accent/80 group-hover:border-red-accent">
                                        <svg class="w-4 h-4 ml-0.5 text-white" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                        </svg>
                                    </div>
                                </div>

                                <div class="absolute bottom-2.5 left-3 right-3 pointer-events-none">
                                    <div class="text-xs sm:text-sm font-bold text-white drop-shadow">{{ __('landing.hero.card_film_title') }}</div>
                                    <div class="text-[10px] sm:text-[11px] text-white/75 drop-shadow">{{ __('landing.hero.card_film_tag') }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BRAND MARQUEE -->
    @php
        $brandLogos = [
            ['name' => 'CESD', 'url' => 'https://pub-e1e6c45a16d04ab888631d49250d2b1f.r2.dev/Brands/CESD.png'],
            ['name' => 'Classy', 'url' => 'https://pub-e1e6c45a16d04ab888631d49250d2b1f.r2.dev/Brands/Classy.png'],
            ['name' => 'COMPULAGO', 'url' => 'https://pub-e1e6c45a16d04ab888631d49250d2b1f.r2.dev/Brands/Compulago.png'],
            ['name' => 'FIAO', 'url' => 'https://pub-e1e6c45a16d04ab888631d49250d2b1f.r2.dev/Brands/Fiao.png'],
            ['name' => 'HB', 'url' => 'https://pub-e1e6c45a16d04ab888631d49250d2b1f.r2.dev/Brands/HB%20Mantenimiento.png'],
            ['name' => 'GMI', 'url' => 'https://pub-e1e6c45a16d04ab888631d49250d2b1f.r2.dev/Brands/GMI%20SAS.png'],
            ['name' => 'SPIRNET', 'url' => 'https://pub-e1e6c45a16d04ab888631d49250d2b1f.r2.dev/Brands/Spirnet.png'],
            ['name' => 'DSM', 'url' => 'https://pub-e1e6c45a16d04ab888631d49250d2b1f.r2.dev/Brands/DSM%20Proyectos.png'],
            ['name' => 'RDZ', 'url' => 'https://pub-e1e6c45a16d04ab888631d49250d2b1f.r2.dev/Brands/RDZ.png'],
            ['name' => 'LUX', 'url' => 'https://pub-e1e6c45a16d04ab888631d49250d2b1f.r2.dev/Brands/LuxByLs.png'],
            ['name' => 'HCB', 'url' => 'https://pub-e1e6c45a16d04ab888631d49250d2b1f.r2.dev/Brands/Hi%20Custom%20Blinds.png'],
            ['name' => 'OCRL', 'url' => 'https://pub-e1e6c45a16d04ab888631d49250d2b1f.r2.dev/Brands/OCRL.png'],
            ['name' => 'SIT', 'url' => 'https://pub-e1e6c45a16d04ab888631d49250d2b1f.r2.dev/Brands/SIT.png'],
            ['name' => 'TRANSPETROL', 'url' => 'https://pub-e1e6c45a16d04ab888631d49250d2b1f.r2.dev/Brands/Transpetrol.png'],
        ];
        $brandRows = [$brandLogos, array_reverse($brandLogos)];
    @endphp
    <section class="relative py-7 sm:py-9 border-y border-white/5" aria-labelledby="brand-marquee-title">
        <h2 id="brand-marquee-title" class="text-center text-xs font-semibold text-white/40 uppercase tracking-[0.2em] mb-5 sm:mb-7">
            {{ __('landing.brands.eyebrow') }}
        </h2>

        <div class="flex flex-col gap-2 sm:gap-3">
            @foreach ($brandRows as $rowIndex => $logos)
                <div class="brand-marquee {{ $rowIndex === 1 ? 'brand-marquee--reverse' : '' }}">
                    <div class="brand-marquee__track">
                        @foreach ([false, true] as $isDuplicate)
                            <div class="brand-marquee__group" @if ($isDuplicate) aria-hidden="true" @endif>
                                @foreach ($logos as $brand)
                                    <div class="brand-marquee__item">
                                        <img
                                            class="brand-marquee__logo"
                                            src="{{ $brand['url'] }}"
                                            alt="{{ $isDuplicate ? '' : $brand['name'] }}"
                                            title="{{ $brand['name'] }}"
                                            width="176"
                                            height="48"
                                            loading="lazy"
                                            decoding="async"
                                        >
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- STATS -->
    <section class="stats-section section-spacing" aria-label="{{ __('landing.stats.delivered_projects_label') }}">
        <div class="relative max-w-7xl mx-auto container-pad">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12 md:gap-8 lg:gap-12">
                <div class="text-center">
                    <div class="stats-number"
                         data-counter
                         data-target="20"
                         data-prefix="+"
                         data-suffix=""
                         aria-label="{{ __('landing.stats.delivered_projects') }} {{ __('landing.stats.delivered_projects_label') }}">
                        <span class="counter-value">{{ __('landing.stats.delivered_projects') }}</span>
                    </div>
                    <div class="stats-bar-track" aria-hidden="true">
                        <div class="stats-bar-fill" data-bar-target="100" style="width: 100%;"></div>
                    </div>
                    <div class="stats-label mt-3">{{ __('landing.stats.delivered_projects_label') }}</div>
                </div>
                <div class="text-center">
                    <div class="stats-number"
                         data-counter
                         data-target="95"
                         data-prefix="+"
                         data-suffix="%"
                         aria-label="{{ __('landing.stats.satisfied_rate') }} {{ __('landing.stats.satisfied_rate_label') }}">
                        <span class="counter-value">{{ __('landing.stats.satisfied_rate') }}</span>
                    </div>
                    <div class="stats-bar-track" aria-hidden="true">
                        <div class="stats-bar-fill" data-bar-target="95" style="width: 95%;"></div>
                    </div>
                    <div class="stats-label mt-3">{{ __('landing.stats.satisfied_rate_label') }}</div>
                </div>
                <div class="text-center">
                    <div class="stats-number"
                         data-counter
                         data-target="100"
                         data-prefix=""
                         data-suffix="%"
                         aria-label="{{ __('landing.stats.custom_solutions') }} {{ __('landing.stats.custom_solutions_label') }}">
                        <span class="counter-value">{{ __('landing.stats.custom_solutions') }}</span>
                    </div>
                    <div class="stats-bar-track" aria-hidden="true">
                        <div class="stats-bar-fill" data-bar-target="100" style="width: 100%;"></div>
                    </div>
                    <div class="stats-label mt-3">{{ __('landing.stats.custom_solutions_label') }}</div>
                </div>
                <div class="text-center">
                    <div class="stats-number"
                         data-counter
                         data-target="8"
                         data-prefix=""
                         data-suffix="{{ __('landing.stats.years_suffix') }}"
                         aria-label="{{ __('landing.stats.years_building') }} {{ __('landing.stats.years_building_label') }}">
                        <span class="counter-value">{{ __('landing.stats.years_building') }}</span>
                    </div>
                    <div class="stats-bar-track" aria-hidden="true">
                        <div class="stats-bar-fill" data-bar-target="100" style="width: 100%;"></div>
                    </div>
                    <div class="stats-label mt-3">{{ __('landing.stats.years_building_label') }}</div>
                </div>
            </div>
        </div>
    </section>


    <!-- SERVICES -->
    <section id="servicios" class="relative section-spacing" aria-labelledby="services-heading">
        <div aria-hidden="true" class="absolute top-40 right-0 w-96 h-96 rounded-full blur-[150px]" style="background: var(--accent-red-soft);"></div>

        <div class="relative max-w-7xl mx-auto container-pad">
            <div class="max-w-3xl mb-12 sm:mb-16">
                <span class="eyebrow">{{ __('landing.services.eyebrow') }}</span>
                <h2 id="services-heading" class="font-display text-white mb-8">
                    {{ __('landing.services.title_prefix') }} <span class="text-red-accent">{{ __('landing.services.title_highlight') }}</span>
                </h2>
                <p class="text-white/60 text-base sm:text-xl leading-relaxed">
                    {{ __('landing.services.subtitle') }}
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @php
                    $services = \App\Livewire\Landing\ServiceDetail::allServices();
                @endphp

                @foreach($services as $service)
                    <article class="service-card">
                        <a href="{{ route('services.show', $service['slug']) }}" wire:navigate class="service-card-link" aria-label="{{ $service['title'] }}"></a>
                        <div class="service-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $service['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="service-pillar">{{ $service['pillar'] }}</span>
                        <h3 class="service-title">{{ $service['title'] }}</h3>
                        <p class="service-desc">{{ $service['subtitle'] ?? ($service['desc'] ?? '') }}</p>
                        <div class="service-tags" style="margin-bottom: 24px;">
                            @foreach($service['tags'] as $tag)
                                <span class="service-tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                        <div class="service-action-wrap" style="margin-top: auto; padding-top: 18px; border-top: 1px solid rgba(255,255,255,0.07); position: relative; z-index: 1;">
                            <div class="service-action-btn" aria-hidden="true">
                                <span>{{ $service['btn_text'] ?? ($service['btn'] ?? '') }}</span>
                                <svg class="service-action-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- COMPARATIVA: SIN KOSTA VS CON KOSTA -->
    <section id="diferencia" class="relative section-spacing overflow-hidden" aria-labelledby="comparison-heading" style="scroll-margin-top: 110px;">
        <div aria-hidden="true" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[380px] rounded-full blur-[180px] pointer-events-none" style="background: radial-gradient(circle, rgba(230, 57, 70, 0.15) 0%, rgba(34, 16, 19, 0.12) 50%, transparent 70%);"></div>

        <div class="relative max-w-7xl mx-auto container-pad">
            <div class="text-center mb-12 sm:mb-14 max-w-3xl mx-auto">
                <span class="eyebrow">{{ __('landing.comparison.eyebrow') }}</span>
                <h2 id="comparison-heading" class="font-display text-white mb-6">
                    {{ __('landing.comparison.title_prefix') }} <span class="text-red-accent">{{ __('landing.comparison.title_highlight') }}</span>
                </h2>
                <p class="text-white/65 text-base sm:text-lg leading-relaxed">
                    {{ __('landing.comparison.subtitle') }}
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl mx-auto items-stretch">
                {{-- Tarjeta 1 (Izquierda): Sin Kosta --}}
                <div class="comparison-card-sin">
                    <div class="comparison-badge-sin">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>{{ __('landing.comparison.sin_kosta.badge') }}</span>
                    </div>

                    <h3 class="comparison-title-sin">{{ __('landing.comparison.sin_kosta.title') }}</h3>
                    <p class="comparison-subtitle-sin">{{ __('landing.comparison.sin_kosta.subtitle') }}</p>

                    <ul class="comparison-list">
                        <li class="comparison-item-sin">
                            <span class="comp-icon-x">✕</span>
                            <span><strong>{{ __('landing.comparison.sin_kosta.item1_label') }}</strong> {{ __('landing.comparison.sin_kosta.item1_text') }}</span>
                        </li>
                        <li class="comparison-item-sin">
                            <span class="comp-icon-x">✕</span>
                            <span><strong>{{ __('landing.comparison.sin_kosta.item2_label') }}</strong> {{ __('landing.comparison.sin_kosta.item2_text') }}</span>
                        </li>
                        <li class="comparison-item-sin">
                            <span class="comp-icon-x">✕</span>
                            <span><strong>{{ __('landing.comparison.sin_kosta.item3_label') }}</strong> {{ __('landing.comparison.sin_kosta.item3_text') }}</span>
                        </li>
                        <li class="comparison-item-sin">
                            <span class="comp-icon-x">✕</span>
                            <span><strong>{{ __('landing.comparison.sin_kosta.item4_label') }}</strong> {{ __('landing.comparison.sin_kosta.item4_text') }}</span>
                        </li>
                        <li class="comparison-item-sin">
                            <span class="comp-icon-x">✕</span>
                            <span><strong>{{ __('landing.comparison.sin_kosta.item5_label') }}</strong> {{ __('landing.comparison.sin_kosta.item5_text') }}</span>
                        </li>
                    </ul>
                </div>

                {{-- Tarjeta 2 (Derecha): Con Kosta --}}
                <div class="comparison-card-kosta">
                    <div class="comparison-badge-kosta">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        <span>{{ __('landing.comparison.con_kosta.badge') }}</span>
                    </div>

                    <h3 class="comparison-title-kosta">{{ __('landing.comparison.con_kosta.title') }}</h3>
                    <p class="comparison-subtitle-kosta">{{ __('landing.comparison.con_kosta.subtitle') }}</p>

                    <ul class="comparison-list">
                        <li class="comparison-item-kosta">
                            <span class="comp-icon-check">✓</span>
                            <span><strong>{{ __('landing.comparison.con_kosta.item1_label') }}</strong> {{ __('landing.comparison.con_kosta.item1_text') }}</span>
                        </li>
                        <li class="comparison-item-kosta">
                            <span class="comp-icon-check">✓</span>
                            <span><strong>{{ __('landing.comparison.con_kosta.item2_label') }}</strong> {{ __('landing.comparison.con_kosta.item2_text') }}</span>
                        </li>
                        <li class="comparison-item-kosta">
                            <span class="comp-icon-check">✓</span>
                            <span><strong>{{ __('landing.comparison.con_kosta.item3_label') }}</strong> {{ __('landing.comparison.con_kosta.item3_text') }}</span>
                        </li>
                        <li class="comparison-item-kosta">
                            <span class="comp-icon-check">✓</span>
                            <span><strong>{{ __('landing.comparison.con_kosta.item4_label') }}</strong> {{ __('landing.comparison.con_kosta.item4_text') }}</span>
                        </li>
                        <li class="comparison-item-kosta">
                            <span class="comp-icon-check">✓</span>
                            <span><strong>{{ __('landing.comparison.con_kosta.item5_label') }}</strong> {{ __('landing.comparison.con_kosta.item5_text') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- PROCESS -->
    <section id="proceso" class="relative section-spacing">
        <div class="absolute inset-0" style="background: linear-gradient(180deg, transparent 0%, rgba(230, 57, 70, 0.03) 50%, transparent 100%);"></div>

        <div class="relative max-w-7xl mx-auto container-pad">
            <div class="text-center mb-12 sm:mb-16 max-w-3xl mx-auto">
                <span class="eyebrow">{{ __('landing.process.eyebrow') }}</span>
                <h2 class="font-display text-white mb-8">
                    {{ __('landing.process.title_prefix') }} <span class="text-red-accent">{{ __('landing.process.title_highlight') }}</span>
                </h2>
                <p class="text-white/60 text-base sm:text-lg leading-relaxed">
                    {{ __('landing.process.subtitle') }}
                </p>
            </div>

            <div class="relative process-timeline-wrapper">
                <!-- Desktop Connecting Track & Progress Line -->
                <div aria-hidden="true" class="hidden lg:block absolute process-timeline-track">
                    <div class="process-timeline-fill" style="width: 0%;"></div>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-12 sm:gap-10 lg:gap-8 relative z-10">
                    @php
                        $stepIcons = [
                            'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
                            'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                            'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z',
                            'M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58',
                        ];
                        $steps = trans('landing.process.steps');
                    @endphp

                    @foreach($steps as $i => $step)
                        <div class="text-center group relative process-step-item" data-step="{{ $i }}">
                            <div class="relative w-20 h-20 mx-auto mb-8 z-10">
                                <div aria-hidden="true" class="process-icon-halo absolute inset-0 rounded-2xl blur-2xl group-hover:blur-3xl transition-all duration-500 opacity-40" style="background: var(--accent-red-glow);"></div>
                                <div class="process-icon-box relative w-full h-full glass-card rounded-2xl flex items-center justify-center" style="border: 1px solid rgba(230, 57, 70, 0.3);">
                                    <svg class="w-8 h-8 text-red-accent transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $stepIcons[$i] ?? '' }}"/>
                                    </svg>
                                </div>
                                <span class="process-step-badge">
                                    <span class="step-num">{{ $step['num'] }}</span>
                                    <span class="step-check hidden" aria-hidden="true">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                </span>
                            </div>

                            <h3 class="text-lg sm:text-xl font-bold text-white mb-4 transition-colors duration-300">{{ $step['title'] }}</h3>
                            <p class="text-white/45 text-sm sm:text-base leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section id="testimonios" class="relative section-spacing">
        <div class="relative max-w-7xl mx-auto container-pad">
            <div class="text-center mb-12 sm:mb-16 max-w-3xl mx-auto">
                <span class="eyebrow">{{ __('landing.testimonials.eyebrow') }}</span>
                <h2 class="font-display text-white mb-6">
                    {{ __('landing.testimonials.title_prefix') }} <span class="text-red-accent">{{ __('landing.testimonials.title_highlight') }}</span>
                </h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @php
                    $testimonials = trans('landing.testimonials.items');
                @endphp

                @foreach($testimonials as $t)
                    <figure class="testimonial-card flex flex-col">
                        <div class="flex items-center gap-1 mb-4" aria-label="{{ __('landing.hero.stars_aria') }}">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-red-accent" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>

                        <blockquote class="text-white/80 text-base leading-relaxed mb-6 flex-grow">
                            "{{ $t['quote'] }}"
                        </blockquote>

                        <figcaption class="flex items-center gap-3 pt-4 border-t border-white/5">
                            <div class="w-11 h-11 rounded-full flex items-center justify-center text-sm font-bold text-white" style="background: linear-gradient(135deg, var(--accent-red), var(--accent-red-dark));">
                                {{ $t['initial'] }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-white">{{ $t['name'] }}</div>
                                <div class="text-xs text-white/50">{{ $t['role'] }}</div>
                            </div>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section id="contacto" class="relative section-spacing overflow-hidden">
        <div aria-hidden="true" class="absolute inset-0" style="background: radial-gradient(ellipse at center, var(--accent-red-soft) 0%, transparent 60%);"></div>

        <div class="relative max-w-3xl mx-auto container-pad text-center">
            <span class="eyebrow">{{ __('landing.cta.eyebrow') }}</span>
            <h2 class="font-display text-white mb-8">
                {{ __('landing.cta.title_prefix') }} <span class="text-red-accent">{{ __('landing.cta.title_highlight') }}</span> {{ __('landing.cta.title_suffix') }}
            </h2>
            <p class="text-base sm:text-xl text-white/65 mb-8 max-w-2xl mx-auto leading-relaxed">
                {{ __('landing.cta.subtitle') }}
            </p>

            <div class="cta-group justify-center" style="max-width: 480px; margin: 0 auto;">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary">
                        {{ __('landing.cta.go_dashboard') }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                @else
                    <a href="mailto:hola@kosta.studio" class="btn-primary">
                        {{ __('landing.cta.btn_project') }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="mailto:hola@kosta.studio" class="btn-secondary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ __('landing.cta.btn_write') }}
                    </a>
                @endauth
            </div>

            <div class="mt-12 sm:mt-14 flex flex-wrap items-center justify-center gap-x-8 gap-y-4 text-sm text-white/50">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('landing.cta.badge_48h') }}
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('landing.cta.badge_no_obligation') }}
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('landing.cta.badge_custom') }}
                </span>
            </div>
        </div>
    </section>
    </main>

    @include('partials.landing-footer')


    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const targets = [
            { trigger: '.service-card',     svgSelector: '.service-icon svg' },
            { trigger: '.group.relative',   svgSelector: 'svg' },
        ];

        targets.forEach(({ trigger, svgSelector }) => {
            document.querySelectorAll(trigger).forEach(card => {
                const elements = card.querySelectorAll(
                    `${svgSelector} path, ${svgSelector} polyline, ${svgSelector} line, ${svgSelector} circle, ${svgSelector} rect, ${svgSelector} ellipse`
                );

                card.addEventListener('mouseenter', () => {
                    elements.forEach((el, i) => {
                        const len = el.getTotalLength ? Math.ceil(el.getTotalLength()) + 2 : 200;
                        el.style.transition = 'none';
                        el.style.strokeDasharray = len;
                        el.style.strokeDashoffset = len;
                        void el.getBoundingClientRect();
                        el.style.transition = `stroke-dashoffset 1.4s cubic-bezier(0.4, 0, 0.2, 1) ${i * 0.12}s`;
                        el.style.strokeDashoffset = '0';
                    });
                });

                card.addEventListener('mouseleave', () => {
                    elements.forEach(el => {
                        el.style.transition = 'none';
                        el.style.strokeDasharray = '';
                        el.style.strokeDashoffset = '';
                    });
                });
            });
        });
    });

    // ===== SCROLL-DRIVEN STATS COUNT-UP & PROGRESS BARS =====
    (() => {
        const initScrollStats = () => {
            const statsSection = document.querySelector('.stats-section');
            if (!statsSection) return;

            const counters = Array.from(statsSection.querySelectorAll('[data-counter]')).map((el) => ({
                el,
                valueEl: el.querySelector('.counter-value') || el,
                target: parseFloat(el.getAttribute('data-target')) || 0,
                prefix: el.getAttribute('data-prefix') || '',
                suffix: el.getAttribute('data-suffix') || '',
            }));

            const bars = Array.from(statsSection.querySelectorAll('.stats-bar-fill')).map((el) => ({
                el,
                target: parseFloat(el.getAttribute('data-bar-target')) || 100,
            }));

            if (!counters.length) return;

            const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (prefersReducedMotion) {
                counters.forEach((c) => {
                    c.valueEl.textContent = `${c.prefix}${c.target}${c.suffix}`;
                });
                bars.forEach((b) => {
                    b.el.style.width = `${b.target}%`;
                });
                return;
            }

            let currentProgress = 0;
            let targetProgress = 0;
            let isTicking = false;

            const render = (progress) => {
                counters.forEach((c) => {
                    const val = Math.round(progress * c.target);
                    let suffix = c.suffix;
                    if (val === 1 && suffix.includes('años')) {
                        suffix = suffix.replace('años', 'año');
                    } else if (val === 1 && suffix.includes('years')) {
                        suffix = suffix.replace('years', 'year');
                    }
                    c.valueEl.textContent = `${c.prefix}${val}${suffix}`;
                });

                bars.forEach((b) => {
                    const widthPct = Math.min(progress * b.target, b.target);
                    b.el.style.width = `${widthPct.toFixed(1)}%`;
                });
            };

            const calculateTargetProgress = () => {
                const rect = statsSection.getBoundingClientRect();
                const vh = window.innerHeight || 800;

                // Starts counting when top of stats-section enters lower viewport
                const startY = vh * 0.95;
                // Completes when stats-section reaches comfortably centered view
                const endY = vh * 0.22;
                const distance = startY - endY;

                if (distance <= 0) return 1;

                const raw = (startY - rect.top) / distance;
                return Math.max(0, Math.min(1, raw));
            };

            const tick = () => {
                const diff = targetProgress - currentProgress;
                // Smooth 0.08 lerp for slow, momentum feel on scroll
                if (Math.abs(diff) > 0.001) {
                    currentProgress += diff * 0.08;
                    render(currentProgress);
                    requestAnimationFrame(tick);
                } else {
                    currentProgress = targetProgress;
                    render(currentProgress);
                    isTicking = false;
                }
            };

            const onScroll = () => {
                targetProgress = calculateTargetProgress();
                if (!isTicking) {
                    isTicking = true;
                    requestAnimationFrame(tick);
                }
            };

            // Remove existing listener if re-initializing on livewire navigation
            if (window.__kostaStatsScrollHandler) {
                window.removeEventListener('scroll', window.__kostaStatsScrollHandler);
                window.removeEventListener('resize', window.__kostaStatsScrollHandler);
            }
            window.__kostaStatsScrollHandler = onScroll;

            // Initial calculation & immediate render based on current scroll position
            targetProgress = calculateTargetProgress();
            currentProgress = targetProgress;
            render(currentProgress);

            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', onScroll, { passive: true });
        };

        // ===== SCROLL-DRIVEN PROCESS TIMELINE & CHECKS =====
        const initScrollProcess = () => {
            const processSection = document.getElementById('proceso');
            if (!processSection) return;

            const lineFill = processSection.querySelector('.process-timeline-fill');
            const stepItems = Array.from(processSection.querySelectorAll('.process-step-item'));
            if (!stepItems.length) return;

            const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (prefersReducedMotion) {
                if (lineFill) lineFill.style.width = '100%';
                stepItems.forEach((item) => {
                    item.classList.add('is-active');
                    const badge = item.querySelector('.process-step-badge');
                    if (badge) badge.classList.add('is-active');
                });
                return;
            }

            let currentProgress = 0;
            let targetProgress = 0;
            let isTicking = false;

            const thresholds = [0.04, 0.33, 0.66, 0.95];

            const render = (progress) => {
                if (lineFill) {
                    lineFill.style.width = `${(progress * 100).toFixed(1)}%`;
                    lineFill.classList.toggle('is-active', progress > 0.01);
                }

                stepItems.forEach((item, index) => {
                    const isActive = progress >= thresholds[index];
                    item.classList.toggle('is-active', isActive);
                    const badge = item.querySelector('.process-step-badge');
                    if (badge) {
                        badge.classList.toggle('is-active', isActive);
                    }
                });
            };

            const calculateTargetProgress = () => {
                const rect = processSection.getBoundingClientRect();
                const vh = window.innerHeight || 800;

                const startY = vh * 0.85;
                const endY = vh * 0.35;
                const distance = startY - endY;

                if (distance <= 0) return 1;

                const raw = (startY - rect.top) / distance;
                return Math.max(0, Math.min(1, raw));
            };

            const tick = () => {
                const diff = targetProgress - currentProgress;
                if (Math.abs(diff) > 0.001) {
                    currentProgress += diff * 0.08;
                    render(currentProgress);
                    requestAnimationFrame(tick);
                } else {
                    currentProgress = targetProgress;
                    render(currentProgress);
                    isTicking = false;
                }
            };

            const onScroll = () => {
                targetProgress = calculateTargetProgress();
                if (!isTicking) {
                    isTicking = true;
                    requestAnimationFrame(tick);
                }
            };

            if (window.__kostaProcessScrollHandler) {
                window.removeEventListener('scroll', window.__kostaProcessScrollHandler);
                window.removeEventListener('resize', window.__kostaProcessScrollHandler);
            }
            window.__kostaProcessScrollHandler = onScroll;

            targetProgress = calculateTargetProgress();
            currentProgress = targetProgress;
            render(currentProgress);

            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', onScroll, { passive: true });
        };

        const setupAll = () => {
            initScrollStats();
            initScrollProcess();
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupAll);
        } else {
            setupAll();
        }

        document.addEventListener('livewire:navigated', setupAll);
    })();
    </script>
</div>
