<div class="bg-dark-gradient min-h-screen relative">
    <style>

        /* ── Filter tabs ── */
        .filter-tabs { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; margin-bottom: 56px; }
        .filter-btn { padding: 9px 22px; border-radius: 999px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.04); color: rgba(255,255,255,0.55); font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s ease; font-family: 'Inter', sans-serif; }
        .filter-btn:hover { border-color: rgba(255,255,255,0.2); color: rgba(255,255,255,0.85); background: rgba(255,255,255,0.07); }
        .filter-btn.active { background: var(--accent-red); border-color: var(--accent-red); color: #0A0A0A; font-weight: 700; }
        .filter-btn:focus-visible { outline: 2px solid #fff; outline-offset: 3px; }

        /* ── Projects grid ── */
        .projects-grid { display: grid; grid-template-columns: 1fr; gap: 28px; }
        @media (min-width: 640px) { .projects-grid { grid-template-columns: repeat(2, 1fr); } }
        /* "Todos": 2 cols so each mockup se ve grande */
        @media (min-width: 1024px) { .projects-grid { grid-template-columns: repeat(2, 1fr); } }
        /* Categoría filtrada: 3 cols */
        @media (min-width: 1024px) { .projects-grid.cols-3 { grid-template-columns: repeat(3, 1fr); } }

        /* ── Project card ── */
        .project-card { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.07); border-radius: 20px; overflow: hidden; transition: transform 0.4s cubic-bezier(0.16,1,0.3,1), border-color 0.3s ease, box-shadow 0.4s ease; }
        .project-card:hover { transform: translateY(-8px); border-color: rgba(230,57,70,0.3); box-shadow: 0 24px 64px rgba(0,0,0,0.35), 0 0 0 1px rgba(230,57,70,0.1); }
        .project-card.hidden-card { display: none; }

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

    <!-- ── HERO ── -->
    <section class="relative pt-64 pb-16">
        <div class="relative z-10 max-w-7xl mx-auto container-pad text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 glass-card rounded-full mb-8 animate-fadeInUp">
                <span class="relative flex w-2 h-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-accent opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-accent"></span>
                </span>
                <span class="text-xs sm:text-sm text-white/85 font-medium">+50 proyectos entregados</span>
            </div>
            <h1 class="font-display text-white mb-6 animate-fadeInUp delay-100">
                Proyectos que <span class="text-red-accent">hablan</span><br>por sí solos
            </h1>
            <p class="text-lg sm:text-xl text-white/60 max-w-2xl mx-auto mb-14 animate-fadeInUp delay-200">
                Cada proyecto es una historia de transformación. Aquí están los resultados que hemos logrado para nuestros clientes.
            </p>

            <!-- Filter tabs -->
            <div class="filter-tabs animate-fadeInUp delay-300">
                <button class="filter-btn active" data-filter="all" aria-pressed="true">Todos</button>
                <button class="filter-btn" data-filter="web" aria-pressed="false">Páginas Web</button>
                <button class="filter-btn" data-filter="ecommerce" aria-pressed="false">E-commerce</button>
                <button class="filter-btn" data-filter="social" aria-pressed="false">Redes Sociales</button>
                <button class="filter-btn" data-filter="branding" aria-pressed="false">Branding</button>
            </div>
        </div>
    </section>

    <!-- ── PROJECTS GRID ── -->
    <section class="relative pb-24">
        <div class="relative z-10 max-w-7xl mx-auto container-pad">
            @php
                $catLabels = ['web' => 'Página Web', 'ecommerce' => 'E-commerce', 'branding' => 'Branding', 'social' => 'Redes Sociales'];
            @endphp

            <div class="projects-grid" id="projectsGrid">
                @forelse($estudios as $estudio)
                    <div class="project-card" data-category="{{ $estudio->categoria }}">
                        <div class="browser-chrome">
                            <div class="browser-dots"><span class="browser-dot dot-red"></span><span class="browser-dot dot-yellow"></span><span class="browser-dot dot-green"></span></div>
                            <div class="browser-url">{{ $estudio->url_demo ?? 'kosta.studio' }}</div>
                        </div>
                        <div class="browser-screen" style="background: linear-gradient(135deg, {{ $estudio->gradient_inicio }}, {{ $estudio->gradient_fin }});">
                            @if($estudio->imagen)
                                <img src="{{ Storage::url($estudio->imagen) }}" alt="{{ $estudio->titulo }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
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
                                        {{ $estudio->categoria === 'social' ? 'Visitar red' : 'Visitar página' }}
                                        <svg viewBox="0 0 10 10" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M1.5 8.5L8.5 1.5M8.5 1.5H3M8.5 1.5v5.5"/></svg>
                                    </a>
                                @endif
                                <a href="{{ route('contact') }}" class="project-link">
                                    Solicitar proyecto similar
                                    <svg viewBox="0 0 10 10" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M1.5 8.5L8.5 1.5M8.5 1.5H3M8.5 1.5v5.5"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column:1/-1;text-align:center;padding:80px 0;">
                        <p style="color:rgba(255,255,255,0.35);font-size:16px;">No hay casos de estudio publicados aún.</p>
                    </div>
                @endforelse
            </div><!-- /projects-grid -->

            <!-- No results (filter JS) -->
            <div id="noResults" class="hidden text-center py-24">
                <p class="text-white/40 text-lg">No hay proyectos en esta categoría.</p>
            </div>
        </div>
    </section>

    <!-- ── STATS ── -->
    <section class="relative pb-24">
        <div class="relative z-10 max-w-7xl mx-auto container-pad">
            <div class="stats-bar">
                <div class="stat-item"><div class="stat-number">50+</div><div class="stat-label">Proyectos entregados</div></div>
                <div class="stat-item"><div class="stat-number">98%</div><div class="stat-label">Clientes satisfechos</div></div>
                <div class="stat-item"><div class="stat-number">8</div><div class="stat-label">Países atendidos</div></div>
                <div class="stat-item"><div class="stat-number">3×</div><div class="stat-label">ROI promedio</div></div>
            </div>
        </div>
    </section>

    <!-- ── CTA BANNER ── -->
    <section class="relative pb-32">
        <div class="relative z-10 max-w-7xl mx-auto container-pad">
            <div class="cta-banner">
                <div class="relative z-10">
                    <h2 class="font-display text-white mb-4" style="font-size: clamp(24px,4vw,40px);">¿Tu proyecto es el siguiente?</h2>
                    <p class="text-white/75 text-lg mb-8 max-w-xl mx-auto">Cuéntanos tu idea y en menos de 24 horas te enviamos una propuesta personalizada sin costo.</p>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-3 bg-white text-gray-900 font-bold px-8 py-4 rounded-full text-base hover:bg-gray-100 transition-colors">
                        Hablar con el equipo
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    @include('partials.landing-footer')
    @include('partials.landing-nav-js')

    <script>
        // Project filter
        const filterBtns = document.querySelectorAll('.filter-btn');
        const cards = document.querySelectorAll('.project-card');
        const grid = document.getElementById('projectsGrid');
        const noResults = document.getElementById('noResults');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => {
                    b.classList.remove('active');
                    b.setAttribute('aria-pressed', 'false');
                });
                btn.classList.add('active');
                btn.setAttribute('aria-pressed', 'true');
                const filter = btn.dataset.filter;

                // 2 cols para "Todos", 3 cols para categoría específica
                grid.classList.toggle('cols-3', filter !== 'all');

                let visible = 0;
                cards.forEach(card => {
                    const cats = card.dataset.category || '';
                    const match = filter === 'all' || cats.split(' ').includes(filter);
                    card.classList.toggle('hidden-card', !match);
                    if (match) visible++;
                });
                noResults.classList.toggle('hidden', visible > 0);
            });
        });
    </script>
</div>
