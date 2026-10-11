<div class="bg-dark-gradient min-h-screen relative">
    <style>

        /* ── Filter tabs ── */
        .filter-tabs { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; margin-bottom: 56px; }
        .filter-btn { padding: 9px 22px; border-radius: 999px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.04); color: rgba(255,255,255,0.55); font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s ease; font-family: 'Inter', sans-serif; }
        .filter-btn:hover { border-color: rgba(255,255,255,0.2); color: rgba(255,255,255,0.85); background: rgba(255,255,255,0.07); }
        .filter-btn.active { background: var(--accent-red); border-color: var(--accent-red); color: #0A0A0A; font-weight: 700; }
        .filter-btn:focus-visible { outline: 2px solid #fff; outline-offset: 3px; }

        /* ── Projects grid ── */
        .projects-grid { display: grid; grid-template-columns: 1fr; gap: 24px; }
        @media (min-width: 640px) { .projects-grid { grid-template-columns: repeat(2, 1fr); } }
        /* "Todos": 2 cols so each mockup se ve grande */
        @media (min-width: 1024px) { .projects-grid { grid-template-columns: repeat(2, 1fr); } }
        /* Categoría filtrada regular: 3 cols */
        @media (min-width: 1024px) { .projects-grid.cols-3 { grid-template-columns: repeat(3, 1fr); } }
        /* Modo Reels (Films & Reels): formato compacto tipo feed de redes */
        @media (min-width: 640px) {
            .projects-grid.cols-reels { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
        }
        @media (min-width: 900px) {
            .projects-grid.cols-reels { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 22px; }
        }
        @media (min-width: 1200px) {
            .projects-grid.cols-reels { grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 24px; }
        }

        /* ── Project card ── */
        .project-card { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.07); border-radius: 20px; overflow: hidden; transition: transform 0.4s cubic-bezier(0.16,1,0.3,1), border-color 0.3s ease, box-shadow 0.4s ease; }
        .project-card:hover { transform: translateY(-8px); border-color: rgba(230,57,70,0.3); box-shadow: 0 24px 64px rgba(0,0,0,0.35), 0 0 0 1px rgba(230,57,70,0.1); }
        .project-card.hidden-card,
        .project-card.reel-card.hidden-card { display: none !important; }

        /* Browser mockup */
        .browser-chrome { display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: rgba(0,0,0,0.55); border-bottom: 1px solid rgba(255,255,255,0.06); }
        .browser-dots { display: flex; gap: 5px; flex-shrink: 0; }
        .browser-dot { width: 8px; height: 8px; border-radius: 50%; }
        .dot-red { background: #ff5f57; }
        .dot-yellow { background: #febc2e; }
        .dot-green { background: #28c840; }
        .browser-url { flex: 1; background: rgba(255,255,255,0.07); border-radius: 4px; padding: 3px 10px; font-size: 11px; color: rgba(255,255,255,0.35); font-family: 'Inter', monospace; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .browser-screen { height: 172px; position: relative; overflow: hidden; }

        /* Mockup UI elements inside browser screen */
        .mock-nav { position: absolute; top: 0; left: 0; right: 0; height: 28px; background: rgba(0,0,0,0.25); display: flex; align-items: center; padding: 0 14px; gap: 8px; }
        .mock-nav-dot { width: 24px; height: 5px; border-radius: 3px; background: rgba(255,255,255,0.3); }
        .mock-nav-dot.wide { width: 44px; }
        .mock-nav-dot.end { margin-left: auto; width: 56px; height: 18px; border-radius: 4px; background: rgba(255,255,255,0.2); }
        .mock-hero { position: absolute; top: 40px; left: 20px; right: 20px; }
        .mock-h1 { height: 10px; border-radius: 5px; background: rgba(255,255,255,0.7); margin-bottom: 6px; }
        .mock-h1.short { width: 60%; }
        .mock-p { height: 6px; border-radius: 3px; background: rgba(255,255,255,0.3); margin-bottom: 4px; }
        .mock-p.w80 { width: 80%; }
        .mock-p.w60 { width: 60%; }
        .mock-btn { margin-top: 10px; display: inline-block; width: 64px; height: 18px; border-radius: 20px; background: rgba(255,255,255,0.5); }
        .mock-cards { position: absolute; bottom: 12px; left: 14px; right: 14px; display: flex; gap: 8px; }
        .mock-card-sm { flex: 1; height: 32px; border-radius: 6px; background: rgba(255,255,255,0.12); }

        /* Card body */
        .project-body { padding: 22px 24px 24px; }
        .project-cat { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 999px; background: rgba(230,57,70,0.12); border: 1px solid rgba(230,57,70,0.2); color: var(--accent-red); font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 12px; }
        .project-title { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 700; color: #fff; margin: 0 0 8px; line-height: 1.2; }
        .project-desc { font-size: 13.5px; color: rgba(255,255,255,0.5); line-height: 1.55; margin: 0 0 16px; }
        .project-metric { display: flex; align-items: baseline; gap: 6px; margin-bottom: 16px; padding: 10px 14px; background: rgba(255,255,255,0.03); border-radius: 10px; border: 1px solid rgba(255,255,255,0.06); }
        .metric-value { font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 700; color: var(--accent-red); }
        .metric-label { font-size: 12px; color: rgba(255,255,255,0.45); }
        .project-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 18px; }
        .project-tag { padding: 4px 10px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 6px; font-size: 11px; color: rgba(255,255,255,0.5); font-weight: 500; }
        .project-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 2px; }
        .project-link { min-height: 40px; display: inline-flex; align-items: center; justify-content: center; gap: 7px; padding: 0 14px; border-radius: 999px; font-size: 12.5px; font-weight: 700; color: rgba(255,255,255,0.78); text-decoration: none; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.04); transition: color 0.2s ease, border-color 0.2s ease, background 0.2s ease, transform 0.2s ease; }
        .project-link:hover { color: #fff; border-color: rgba(230,57,70,0.34); background: rgba(230,57,70,0.12); transform: translateY(-1px); }
        .project-link.visit { color: #09090B; background: #FFFFFF; border-color: #FFFFFF; box-shadow: 0 4px 16px rgba(255, 255, 255, 0.15); }
        .project-link.visit:hover { color: #000000; background: #E4E4E7; border-color: #E4E4E7; }
        .project-link svg { width: 14px; height: 14px; flex-shrink: 0; transition: transform 0.2s ease; }
        .project-link:hover svg { transform: translate(2px, -2px); }

        /* ── Social Reel Card (Instagram / TikTok Style) ── */
        .project-card.reel-card {
            position: relative;
            border-radius: 22px;
            overflow: hidden;
            background: #09090b;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.7);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s ease, border-color 0.35s ease;
        }

        .project-card.reel-card.hidden-card {
            display: none !important;
        }

        .project-card.reel-card.is-reel {
            aspect-ratio: 9 / 16;
            max-width: 285px;
            width: 100%;
            min-height: 470px;
            max-height: 510px;
            margin-left: auto;
            margin-right: auto;
        }

        .project-card.reel-card.is-film-16-9 {
            aspect-ratio: 16 / 9;
            max-width: 520px;
            width: 100%;
            min-height: 290px;
            margin-left: auto;
            margin-right: auto;
        }

        .project-card.reel-card:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: rgba(244, 63, 94, 0.55);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.8), 0 0 35px rgba(244, 63, 94, 0.25);
        }

        .reel-bg-video {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
            pointer-events: none;
            background: #09090b;
        }

        .reel-bg-placeholder {
            position: absolute;
            inset: 0;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at center, rgba(244,63,94,0.18) 0%, rgba(9,9,11,0.98) 75%);
        }

        .reel-scrim {
            position: absolute;
            inset: 0;
            z-index: 2;
            pointer-events: none;
            background: linear-gradient(180deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.05) 25%, rgba(0,0,0,0.2) 55%, rgba(0,0,0,0.92) 100%);
        }

        .reel-overlay-top {
            position: relative;
            z-index: 10;
            padding: 13px 13px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            pointer-events: auto;
        }

        .reel-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 9px;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.65);
            border: 1px solid rgba(255, 255, 255, 0.16);
            backdrop-filter: blur(10px);
            font-size: 10px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .reel-dot-live {
            width: 6.5px;
            height: 6.5px;
            border-radius: 50%;
            background: #f43f5e;
            box-shadow: 0 0 8px #f43f5e;
            animation: pulseDot 1.8s infinite;
        }

        @keyframes pulseDot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.35); opacity: 0.6; }
        }

        .reel-duration-pill {
            font-size: 10px;
            font-family: monospace;
            color: rgba(255, 255, 255, 0.85);
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(8px);
            padding: 3px 8px;
            border-radius: 999px;
            font-weight: 600;
        }

        .reel-sound-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 9px;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.65);
            border: 1px solid rgba(255, 255, 255, 0.16);
            backdrop-filter: blur(10px);
            font-size: 10px;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .reel-sound-pill:hover {
            background: rgba(244, 63, 94, 0.25);
            border-color: rgba(244, 63, 94, 0.5);
            color: #ffffff;
            transform: scale(1.04);
        }

        .reel-center-action {
            position: absolute;
            inset: 0;
            z-index: 5;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            cursor: pointer;
            outline: none;
            padding: 0;
        }

        .reel-center-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.45);
            border: 1.5px solid rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(12px);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
            opacity: 0;
            transform: scale(0.85);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .project-card.reel-card:hover .reel-center-btn {
            opacity: 1;
            transform: scale(1);
        }

        .reel-overlay-bottom {
            position: relative;
            z-index: 10;
            padding: 24px 15px 15px;
            margin-top: auto;
            pointer-events: auto;
        }

        .reel-client-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.95);
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(8px);
            padding: 3px 9px;
            border-radius: 999px;
            margin-bottom: 7px;
        }

        .reel-title {
            font-family: 'Syne', 'Inter', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.25;
            margin: 0 0 5px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.9);
        }

        .reel-desc {
            font-size: 11.5px;
            line-height: 1.45;
            color: rgba(255, 255, 255, 0.8);
            margin: 0 0 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-shadow: 0 1px 6px rgba(0, 0, 0, 0.9);
        }

        .reel-metric-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 9px;
            border-radius: 6px;
            background: rgba(244, 63, 94, 0.15);
            border: 1px solid rgba(244, 63, 94, 0.3);
            margin-bottom: 8px;
        }

        .reel-metric-num {
            font-family: 'Syne', sans-serif;
            font-size: 12px;
            font-weight: 700;
            color: #f43f5e;
        }

        .reel-metric-txt {
            font-size: 10.5px;
            color: rgba(255, 255, 255, 0.7);
        }

        .reel-tags-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 10px;
        }

        .reel-tag {
            font-size: 10.5px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.65);
        }

        .reel-actions-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 4px;
        }

        .reel-btn-play {
            flex: 1;
            min-height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0 12px;
            border-radius: 999px;
            background: #ffffff;
            color: #09090b;
            font-size: 12px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(255, 255, 255, 0.18);
        }

        .reel-btn-play:hover {
            background: #f43f5e;
            color: #ffffff;
            box-shadow: 0 4px 18px rgba(244, 63, 94, 0.45);
            transform: translateY(-1px);
        }

        .reel-btn-contact {
            width: 35px;
            height: 35px;
            flex-shrink: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(8px);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .reel-btn-contact:hover {
            background: rgba(255, 255, 255, 0.22);
            border-color: rgba(255, 255, 255, 0.35);
            color: #ffffff;
            transform: translateY(-1px);
        }

        /* ── Stats bar ── */
        .stats-bar { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1px; background: rgba(255,255,255,0.06); border-radius: 16px; overflow: hidden; margin-bottom: 80px; }
        @media (min-width: 640px) { .stats-bar { grid-template-columns: repeat(4, 1fr); } }
        .stat-item { background: rgba(255,255,255,0.02); padding: 28px 24px; text-align: center; }
        .stat-number { font-family: 'Syne', sans-serif; font-size: 32px; font-weight: 700; color: var(--accent-red); line-height: 1; margin-bottom: 6px; }
        .stat-label { font-size: 13px; color: rgba(255,255,255,0.45); font-weight: 500; }

        /* ── CTA Banner ── */
        .cta-banner { background: linear-gradient(135deg, var(--accent-red-dark), var(--accent-red)); border-radius: 24px; padding: 56px 48px; text-align: center; position: relative; overflow: hidden; }
        .cta-banner::before { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 20% 30%, rgba(255,255,255,0.08) 0%, transparent 50%), radial-gradient(circle at 80% 70%, rgba(0,0,0,0.2) 0%, transparent 50%); pointer-events: none; }
        @media (max-width: 640px) { .cta-banner { padding: 40px 24px; } }
    </style>

    <!-- Background orbs -->
    <div aria-hidden="true" class="fixed top-0 left-1/4 w-[500px] h-[500px] bg-red-accent rounded-full blur-[180px] pointer-events-none animate-pulse-glow opacity-20"></div>
    <div aria-hidden="true" class="fixed bottom-0 right-1/4 w-[400px] h-[400px] rounded-full blur-[150px] pointer-events-none opacity-15" style="background: var(--accent-red-dark);"></div>
    <div aria-hidden="true" class="absolute inset-0 grid-pattern pointer-events-none"></div>

    @include('partials.landing-nav')
    @include('partials.landing-nav-js')

    <!-- ── HERO ── -->
    <section class="relative pt-64 pb-16">
        <div class="relative z-10 max-w-7xl mx-auto container-pad text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 glass-card rounded-full mb-8 animate-fadeInUp">
                <span class="relative flex w-2 h-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-accent opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-accent"></span>
                </span>
                <span class="text-xs sm:text-sm text-white/85 font-medium">{{ __('landing.portfolio.badge') }}</span>
            </div>
            <h1 class="font-display text-white mb-6 animate-fadeInUp delay-100">
                {{ __('landing.portfolio.title_prefix') }} <span class="text-red-accent">{{ __('landing.portfolio.title_highlight') }}</span><br>{{ __('landing.portfolio.title_suffix') }}
            </h1>
            <p class="text-lg sm:text-xl text-white/60 max-w-2xl mx-auto mb-14 animate-fadeInUp delay-200">
                {{ __('landing.portfolio.subtitle') }}
            </p>

            <!-- Filter tabs -->
            <div class="filter-tabs animate-fadeInUp delay-300">
                <button class="filter-btn active" data-filter="all" aria-pressed="true">{{ __('landing.portfolio.filters.all') }}</button>
                <button class="filter-btn" data-filter="web" aria-pressed="false">{{ __('landing.portfolio.filters.web') }}</button>
                <button class="filter-btn" data-filter="ecommerce" aria-pressed="false">{{ __('landing.portfolio.filters.ecommerce') }}</button>
                <button class="filter-btn" data-filter="sistema" aria-pressed="false">{{ __('landing.portfolio.filters.sistema') }}</button>
                <button class="filter-btn" data-filter="films" aria-pressed="false">{{ __('landing.portfolio.filters.films') }}</button>
                <button class="filter-btn" data-filter="branding" aria-pressed="false">{{ __('landing.portfolio.filters.branding') }}</button>
            </div>
        </div>
    </section>

    <!-- ── PROJECTS GRID ── -->
    <section class="relative pb-24">
        <div class="relative z-10 max-w-7xl mx-auto container-pad">
            @php
                $catLabels = __('landing.portfolio.cat_labels');
            @endphp

            <div class="projects-grid" id="projectsGrid">
                @forelse($estudios as $estudio)
                    @if($estudio->categoria === 'films')
                        {{-- ================= SOCIAL REEL / FILM CARD ================= --}}
                        <div class="project-card reel-card {{ $estudio->is_reel ? 'is-reel' : 'is-film-16-9' }}"
                             data-category="{{ in_array($estudio->categoria, ['films', 'social'], true) ? 'films social' : $estudio->categoria }}">

                            <!-- Background Video: Optimized with preload metadata and smart viewport playback -->
                            @if($estudio->video_stream_url)
                                @if($estudio->is_direct_video)
                                    <video src="{{ $estudio->video_stream_url }}"
                                           muted
                                           loop
                                           playsinline
                                           preload="metadata"
                                           class="reel-bg-video">
                                    </video>
                                @else
                                    <iframe src="{{ $estudio->video_stream_url }}?autoplay=1&mute=1&loop=1&controls=0"
                                            class="reel-bg-video"
                                            style="border:none;pointer-events:none;">
                                    </iframe>
                                @endif
                            @else
                                <div class="reel-bg-placeholder">
                                    <svg width="36" height="36" fill="currentColor" viewBox="0 0 24 24" style="color:rgba(244,63,94,0.6);"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            @endif

                            <!-- Scrim gradient overlay (Instagram / TikTok look) -->
                            <div class="reel-scrim" aria-hidden="true"></div>

                            <!-- Top bar overlay -->
                            <div class="reel-overlay-top">
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <span class="reel-badge-pill">
                                        <span class="reel-dot-live"></span>
                                        <span>{{ $estudio->is_reel ? 'REEL 9:16' : 'FILM 16:9' }}</span>
                                    </span>
                                    @if($estudio->video_duration)
                                        <span class="reel-duration-pill">{{ $estudio->video_duration }}</span>
                                    @endif
                                </div>

                                <button type="button"
                                        class="reel-sound-pill"
                                        data-film-play
                                        data-video-url="{{ $estudio->video_stream_url }}"
                                        data-orientation="{{ $estudio->video_orientation ?? 'vertical' }}"
                                        data-title="{{ $estudio->titulo }}"
                                        data-type="{{ $estudio->is_direct_video ? 'direct' : 'embed' }}"
                                        data-embed-url="{{ $estudio->video_embed_url }}"
                                        title="Ver con audio">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M12 6L7 10H3v4h4l5 4V6z"/>
                                    </svg>
                                    <span>Con audio</span>
                                </button>
                            </div>

                            <!-- Center Hover Cue (Glass play button) -->
                            <button type="button"
                                    class="reel-center-action"
                                    data-film-play
                                    data-video-url="{{ $estudio->video_stream_url }}"
                                    data-orientation="{{ $estudio->video_orientation ?? 'vertical' }}"
                                    data-title="{{ $estudio->titulo }}"
                                    data-type="{{ $estudio->is_direct_video ? 'direct' : 'embed' }}"
                                    data-embed-url="{{ $estudio->video_embed_url }}"
                                    aria-label="Reproducir {{ $estudio->titulo }} con audio">
                                <div class="reel-center-btn">
                                    <svg width="22" height="22" fill="currentColor" viewBox="0 0 24 24" style="margin-left:2px;"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </button>

                            <!-- Bottom Content Overlay (Text, info and actions in front of video) -->
                            <div class="reel-overlay-bottom">
                                @if(($estudio->source_type ?? null) === 'client' && $estudio->client)
                                    <div class="reel-client-tag">
                                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <span>@<strong>{{ $estudio->client->name }}</strong></span>
                                    </div>
                                @elseif(($estudio->source_type ?? null) === 'website' && $estudio->linkedCaseStudy)
                                    <div class="reel-client-tag">
                                        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                        <span>Sitio: <strong>{{ $estudio->linkedCaseStudy->titulo }}</strong></span>
                                    </div>
                                @endif

                                <h3 class="reel-title">{{ $estudio->titulo }}</h3>

                                @if($estudio->descripcion)
                                    <p class="reel-desc">{{ $estudio->descripcion }}</p>
                                @endif

                                @if($estudio->metrica_valor)
                                    <div class="reel-metric-pill">
                                        <span class="reel-metric-num">{{ $estudio->metrica_valor }}</span>
                                        <span class="reel-metric-txt">{{ $estudio->metrica_label }}</span>
                                    </div>
                                @endif

                                @if($estudio->tags && count($estudio->tags))
                                    <div class="reel-tags-wrap">
                                        @foreach(array_slice($estudio->tags, 0, 3) as $tag)
                                            <span class="reel-tag">#{{ ltrim($tag, '#') }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Reel Actions -->
                                <div class="reel-actions-row">
                                    <button type="button"
                                            class="reel-btn-play"
                                            data-film-play
                                            data-video-url="{{ $estudio->video_stream_url }}"
                                            data-orientation="{{ $estudio->video_orientation ?? 'vertical' }}"
                                            data-title="{{ $estudio->titulo }}"
                                            data-type="{{ $estudio->is_direct_video ? 'direct' : 'embed' }}"
                                            data-embed-url="{{ $estudio->video_embed_url }}">
                                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        <span>Ver con audio</span>
                                    </button>

                                    <a href="{{ route('contact') }}"
                                       class="reel-btn-contact"
                                       title="{{ __('landing.portfolio.action_request_similar') }}">
                                        <svg width="12" height="12" viewBox="0 0 10 10" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M1.5 8.5L8.5 1.5M8.5 1.5H3M8.5 1.5v5.5"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- ================= STANDARD DESKTOP MOCKUP CARD ================= --}}
                        <div class="project-card" data-category="{{ $estudio->categoria }}">
                            <div class="browser-chrome">
                                <div class="browser-dots"><span class="browser-dot dot-red"></span><span class="browser-dot dot-yellow"></span><span class="browser-dot dot-green"></span></div>
                                <div class="browser-url">{{ $estudio->url_demo ?? 'kosta.studio' }}</div>
                            </div>
                            <div class="browser-screen" style="background: linear-gradient(135deg, {{ $estudio->gradient_inicio }}, {{ $estudio->gradient_fin }});">
                                @if($estudio->image_url)
                                    <button type="button" class="case-preview-trigger" data-case-preview data-image="{{ $estudio->image_url }}" data-title="{{ $estudio->titulo }}" aria-label="Ver imagen ampliada de {{ $estudio->titulo }}">
                                        <img src="{{ $estudio->image_url }}" alt="{{ $estudio->titulo }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                                        <span class="case-preview-hint">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                                            Ampliar
                                        </span>
                                    </button>
                                @else
                                    <div class="mock-nav"><span class="mock-nav-dot wide"></span><span class="mock-nav-dot"></span><span class="mock-nav-dot"></span><span class="mock-nav-dot end"></span></div>
                                    <div class="mock-hero"><div class="mock-h1"></div><div class="mock-h1 short"></div><div class="mock-p w80"></div><div class="mock-p w60"></div><div class="mock-btn"></div></div>
                                    <div class="mock-cards"><div class="mock-card-sm"></div><div class="mock-card-sm"></div><div class="mock-card-sm"></div></div>
                                @endif
                            </div>

                            <div class="project-body">
                                <span class="project-cat">{{ $catLabels[$estudio->categoria] ?? $estudio->categoria }}</span>
                                <h3 class="project-title">{{ $estudio->titulo }}</h3>
                                @if($estudio->descripcion)
                                    <p class="project-desc">{{ $estudio->descripcion }}</p>
                                @endif
                                @if($estudio->metrica_valor)
                                    <div class="project-metric">
                                        <span class="metric-value">{{ $estudio->metrica_valor }}</span>
                                        <span class="metric-label">{{ $estudio->metrica_label }}</span>
                                    </div>
                                @endif
                                @if($estudio->tags)
                                    <div class="project-tags">
                                        @foreach($estudio->tags as $tag)
                                            <span class="project-tag">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="project-actions">
                                    @if($estudio->public_url)
                                        <a href="{{ $estudio->public_url }}" target="_blank" rel="noopener noreferrer" class="project-link visit">
                                            {{ $estudio->categoria === 'sistema' ? __('landing.portfolio.action_visit_system') : __('landing.portfolio.action_visit_web') }}
                                            <svg viewBox="0 0 10 10" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M1.5 8.5L8.5 1.5M8.5 1.5H3M8.5 1.5v5.5"/></svg>
                                        </a>
                                    @endif
                                    <a href="{{ route('contact') }}" class="project-link">
                                        {{ __('landing.portfolio.action_request_similar') }}
                                        <svg viewBox="0 0 10 10" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M1.5 8.5L8.5 1.5M8.5 1.5H3M8.5 1.5v5.5"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div style="grid-column:1/-1;text-align:center;padding:80px 0;">
                        <p style="color:rgba(255,255,255,0.35);font-size:16px;">{{ __('landing.portfolio.empty_case_studies') }}</p>
                    </div>
                @endforelse
            </div><!-- /projects-grid -->

            <!-- No results (filter JS) -->
            <div id="noResults" class="hidden text-center py-24">
                <p class="text-white/40 text-lg">{{ __('landing.portfolio.empty_category') }}</p>
            </div>
        </div>
    </section>

    <!-- ── STATS ── -->
    <section class="relative pb-24">
        <div class="relative z-10 max-w-7xl mx-auto container-pad">
            <div class="stats-bar">
                <div class="stat-item"><div class="stat-number">{{ __('landing.portfolio.stats.stat1_num') }}</div><div class="stat-label">{{ __('landing.portfolio.stats.stat1_label') }}</div></div>
                <div class="stat-item"><div class="stat-number">{{ __('landing.portfolio.stats.stat2_num') }}</div><div class="stat-label">{{ __('landing.portfolio.stats.stat2_label') }}</div></div>
                <div class="stat-item"><div class="stat-number">{{ __('landing.portfolio.stats.stat3_num') }}</div><div class="stat-label">{{ __('landing.portfolio.stats.stat3_label') }}</div></div>
                <div class="stat-item"><div class="stat-number">{{ __('landing.portfolio.stats.stat4_num') }}</div><div class="stat-label">{{ __('landing.portfolio.stats.stat4_label') }}</div></div>
            </div>
        </div>
    </section>

    <!-- ── CTA BANNER ── -->
    <section class="relative pb-32">
        <div class="relative z-10 max-w-7xl mx-auto container-pad">
            <div class="cta-banner">
                <div class="relative z-10">
                    <h2 class="font-display text-white mb-4" style="font-size: clamp(24px,4vw,40px);">{{ __('landing.portfolio.cta_banner.title') }}</h2>
                    <p class="text-white/75 text-lg mb-8 max-w-xl mx-auto">{{ __('landing.portfolio.cta_banner.subtitle') }}</p>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-3 bg-white text-gray-900 font-bold px-8 py-4 rounded-full text-base hover:bg-gray-100 transition-colors">
                        {{ __('landing.portfolio.cta_banner.btn') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    @include('partials.landing-footer')
    <x-case-study-viewer />
    <x-film-viewer />

    <script>
        // Project filter & High-performance Reel video controller
        const filterBtns = document.querySelectorAll('.filter-btn');
        const cards = document.querySelectorAll('.project-card');
        const grid = document.getElementById('projectsGrid');
        const noResults = document.getElementById('noResults');

        // Configuración: los reels pertenecen a la categoría especializada "Films & Reels"
        // para mantener "Todos" con carga ultrarrápida y libre de videos pesados simultáneos.
        const showReelsInAll = false;

        let videoObserver = null;

        const pauseVideo = (video) => {
            if (!video) return;
            try {
                video.pause();
            } catch (e) {}
        };

        const playVideo = (video) => {
            if (!video) return;
            video.muted = true;
            const p = video.play();
            if (p !== undefined) {
                p.catch(() => {});
            }
        };

        const setupVideoObserver = () => {
            if (videoObserver) {
                videoObserver.disconnect();
            }

            if (!('IntersectionObserver' in window)) {
                return;
            }

            // Solo reproduce los videos cuando estén dentro de la pantalla visible (al menos 35% visible)
            // y pausa automáticamente los que salgan del viewport o estén ocultos
            videoObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    const card = entry.target;
                    const video = card.querySelector('.reel-bg-video');
                    if (!video) return;

                    // Si la tarjeta está oculta por el filtro, mantener siempre pausada
                    if (card.classList.contains('hidden-card')) {
                        pauseVideo(video);
                        return;
                    }

                    if (entry.isIntersecting && entry.intersectionRatio >= 0.35) {
                        playVideo(video);
                    } else {
                        pauseVideo(video);
                    }
                });
            }, {
                threshold: [0, 0.35, 0.7]
            });

            document.querySelectorAll('.project-card.reel-card').forEach(card => {
                videoObserver.observe(card);

                // Hover para reproducción instantánea en escritorio
                card.addEventListener('mouseenter', () => {
                    if (!card.classList.contains('hidden-card')) {
                        const video = card.querySelector('.reel-bg-video');
                        playVideo(video);
                    }
                });
            });
        };

        const applyFilter = (filter) => {
            filterBtns.forEach(b => {
                const isActive = b.dataset.filter === filter;
                b.classList.toggle('active', isActive);
                b.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });

            // Grid column modes:
            // - films: cols-reels (hasta 4 columnas compactas para reels verticales)
            // - otras categorías: cols-3 (3 columnas)
            // - all: 2 columnas normales
            if (grid) {
                grid.classList.toggle('cols-reels', filter === 'films');
                grid.classList.toggle('cols-3', filter !== 'all' && filter !== 'films');
            }

            let visible = 0;
            cards.forEach(card => {
                const cats = (card.dataset.category || '').toLowerCase();
                const isReel = card.classList.contains('reel-card');

                let match = false;
                if (filter === 'films') {
                    // Pestaña Films & Reels: muestra exclusivamente los reels
                    match = isReel || cats.includes('films') || cats.includes('social');
                } else if (filter === 'all') {
                    // Pestaña Todos: proyectos web, sistemas, ecommerce y branding
                    match = showReelsInAll ? true : !isReel;
                } else {
                    // Categorías específicas (web, ecommerce, sistema, branding):
                    // Los reels NUNCA aparecen en ninguna de estas categorías
                    match = !isReel && cats.split(' ').includes(filter);
                }

                card.classList.toggle('hidden-card', !match);

                if (match) {
                    visible++;
                } else if (isReel) {
                    // Si el reel se oculta, pausar el video inmediatamente para liberar 100% CPU/GPU
                    const video = card.querySelector('.reel-bg-video');
                    pauseVideo(video);
                }
            });

            if (noResults) {
                noResults.classList.toggle('hidden', visible > 0);
            }

            // Si se entra a la pestaña films, refrescar el observador para reproducción fluida
            if (filter === 'films') {
                setTimeout(setupVideoObserver, 50);
            }
        };

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                applyFilter(btn.dataset.filter);
            });
        });

        // Pausar todos los videos de fondo cuando se abre el modal con sonido
        window.addEventListener('film-viewer-opened', () => {
            document.querySelectorAll('.reel-bg-video').forEach(pauseVideo);
        });

        // Reanudar observador cuando se cierra el modal
        window.addEventListener('film-viewer-closed', () => {
            setupVideoObserver();
        });

        // Inicializar observador
        setupVideoObserver();
        document.addEventListener('livewire:navigated', setupVideoObserver);

        // Activación automática si viene con parámetro de URL (?cat=films) o hash (#films)
        const urlParams = new URLSearchParams(window.location.search);
        const initialCat = urlParams.get('cat') || window.location.hash.replace('#', '') || 'all';
        applyFilter(initialCat);
    </script>
</div>
