<div class="bg-dark-gradient min-h-screen relative">
    <style>
        .hero-section { padding-top: 120px; padding-bottom: 36px; }
        @media (min-width: 768px) { .hero-section { padding-top: 128px; padding-bottom: 32px; } }
        @media (min-width: 1024px) { .hero-section { padding-top: 116px; padding-bottom: 24px; } }

        .hero-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 64px;
            align-items: center;
        }
        @media (min-width: 1024px) { .hero-grid { grid-template-columns: 1.3fr 1fr; gap: 80px; } }

        .hero-mockup-col { order: 2; }
        @media (max-width: 1023px) { .hero-mockup-col { display: none; } }

        @media (max-width: 640px) {
            .hero-text-col {
                width: min(100%, 342px);
                max-width: min(342px, calc(100vw - 48px));
                overflow: hidden;
            }

            .hero-text-col h1 {
                font-size: clamp(32px, 10vw, 40px);
                line-height: 1.08;
            }

            .hero-text-col p {
                font-size: 15px;
                line-height: 1.55;
                max-width: min(342px, calc(100vw - 48px));
            }

            .cta-group,
            .cta-group .btn-primary,
            .cta-group .btn-secondary {
                max-width: min(342px, calc(100vw - 48px));
            }

            .logo-item { font-size: 18px; }
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
        }
        @media (min-width: 768px) { .service-card { padding: 48px 40px; } }

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
            font-family: 'Syne', sans-serif;
            font-size: 22px; font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 12px;
            letter-spacing: 0;
            line-height: 1.2;
            position: relative; z-index: 1;
        }
        @media (min-width: 768px) { .service-title { font-size: 24px; } }

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
    </style>

    <div aria-hidden="true" class="fixed top-0 left-1/4 w-[500px] h-[500px] bg-red-accent rounded-full blur-[180px] pointer-events-none animate-pulse-glow opacity-30"></div>
    <div aria-hidden="true" class="fixed bottom-0 right-1/4 w-[400px] h-[400px] rounded-full blur-[150px] pointer-events-none opacity-20" style="background: var(--accent-red-dark);"></div>
    <div aria-hidden="true" class="absolute inset-0 grid-pattern pointer-events-none"></div>

    @include('partials.landing-nav')

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
                        <span class="text-xs sm:text-sm text-white/85 font-medium">Aceptando 3 nuevos proyectos en {{ now()->locale('es')->isoFormat('MMMM') }}</span>
                    </div>

                    <h1 id="hero-heading" class="font-display text-white mb-8 animate-fadeInUp delay-100">
                        Diseño que <span class="text-red-accent">convierte.</span> Estrategia que <span class="text-gradient">escala.</span>
                    </h1>

                    <p class="text-base sm:text-xl text-white/65 mb-10 max-w-xl leading-relaxed animate-fadeInUp delay-200">
                        Somos una agencia digital especializada en transformar negocios a través de
                        <strong class="text-white/90 font-medium">branding poderoso, desarrollo web premium y campañas que generan ROI real.</strong>
                    </p>

                    <div class="cta-group mb-8 animate-fadeInUp delay-300">
                        <a href="#contacto" class="btn-primary">
                            Empieza tu proyecto
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                        <a href="#servicios" class="btn-secondary">Ver servicios</a>
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
                                <div class="flex items-center gap-1" aria-label="5 estrellas">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-4 h-4 text-red-accent" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-xs text-white/55 mt-1">50+ clientes satisfechos</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hero-mockup-col relative animate-fadeInUp delay-200">
                    <div class="relative w-full max-w-lg ml-auto">
                        <div aria-hidden="true" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 rounded-full blur-[100px]" style="background: var(--accent-red-glow); opacity: 0.5;"></div>

                        <div class="relative glass-card rounded-3xl p-6 shadow-2xl" style="border: 1px solid rgba(230, 57, 70, 0.2); background: rgba(20, 20, 20, 0.6);">
                            <div class="flex items-center gap-2 mb-4 pb-3 border-b border-white/5">
                                <span class="w-3 h-3 rounded-full bg-red-accent/60"></span>
                                <span class="w-3 h-3 rounded-full bg-yellow-500/60"></span>
                                <span class="w-3 h-3 rounded-full bg-green-500/60"></span>
                                <div class="ml-3 px-3 py-1 rounded-md bg-white/5 text-xs text-white/40 flex-1 text-center font-mono">kamo.agency</div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="p-4 rounded-xl bg-white/5 border border-white/5">
                                    <div class="text-xs text-white/50 mb-1 uppercase tracking-wider">Conversiones</div>
                                    <div class="font-display text-2xl font-bold text-white">+247%</div>
                                    <div class="text-xs text-red-accent mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M5 10l5-5 5 5H5z"/></svg>
                                        vs. mes anterior
                                    </div>
                                </div>
                                <div class="p-4 rounded-xl bg-white/5 border border-white/5">
                                    <div class="text-xs text-white/50 mb-1 uppercase tracking-wider">ROI</div>
                                    <div class="font-display text-2xl font-bold text-white">4.8x</div>
                                    <div class="text-xs text-red-accent mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M5 10l5-5 5 5H5z"/></svg>
                                        Retorno
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 rounded-xl bg-white/5 border border-white/5">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs text-white/60 font-medium">Crecimiento mensual</span>
                                    <span class="text-xs text-red-accent font-bold">↗ 127%</span>
                                </div>
                                <div class="flex items-end gap-2 h-24">
                                    <div class="flex-1 rounded-t" style="height: 30%; background: linear-gradient(to top, var(--accent-red-dark), rgba(230, 57, 70, 0.4));"></div>
                                    <div class="flex-1 rounded-t" style="height: 45%; background: linear-gradient(to top, var(--accent-red-dark), rgba(230, 57, 70, 0.5));"></div>
                                    <div class="flex-1 rounded-t" style="height: 60%; background: linear-gradient(to top, var(--accent-red-dark), rgba(230, 57, 70, 0.6));"></div>
                                    <div class="flex-1 rounded-t" style="height: 50%; background: linear-gradient(to top, var(--accent-red-dark), rgba(230, 57, 70, 0.55));"></div>
                                    <div class="flex-1 rounded-t" style="height: 75%; background: linear-gradient(to top, var(--accent-red-dark), rgba(230, 57, 70, 0.7));"></div>
                                    <div class="flex-1 rounded-t" style="height: 85%; background: linear-gradient(to top, var(--accent-red-dark), rgba(230, 57, 70, 0.8));"></div>
                                    <div class="flex-1 rounded-t" style="height: 100%; background: linear-gradient(to top, var(--accent-red), var(--accent-red-bright));"></div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-6 -left-6 glass-card rounded-2xl p-4 shadow-xl animate-float hidden sm:block" style="background: rgba(20, 20, 20, 0.85); border: 1px solid rgba(230, 57, 70, 0.3);">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: var(--accent-red-soft);">
                                    <svg class="w-5 h-5 text-red-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-white">Proyecto entregado</div>
                                    <div class="text-xs text-white/50">Hace 2 minutos</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- LOGO CLOUD -->
    <section class="relative py-6 sm:py-8 border-y border-white/5" aria-label="Marcas que confían en nosotros">
        <div class="max-w-7xl mx-auto container-pad">
            <p class="text-center text-xs font-semibold text-white/40 uppercase tracking-[0.2em] mb-10">
                Marcas que confían en nosotros
            </p>
            <div class="flex flex-wrap items-center justify-center gap-x-10 gap-y-8 sm:gap-x-16">
                <span class="logo-item">Lumina</span>
                <span class="logo-item">Verdex</span>
                <span class="logo-item">Norte&amp;Co</span>
                <span class="logo-item">Atlas</span>
                <span class="logo-item">Pulse</span>
                <span class="logo-item">Studio7</span>
            </div>
        </div>
    </section>

    <!-- STATS -->
    <section class="stats-section section-spacing" aria-label="Resultados">
        <div class="relative max-w-7xl mx-auto container-pad">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12 md:gap-8 lg:gap-12">
                <div class="text-center">
                    <div class="stats-number">150+</div>
                    <div class="stats-label mt-3">Proyectos entregados</div>
                </div>
                <div class="text-center">
                    <div class="stats-number">98%</div>
                    <div class="stats-label mt-3">Clientes satisfechos</div>
                </div>
                <div class="text-center">
                    <div class="stats-number">$2.5M+</div>
                    <div class="stats-label mt-3">Generados para clientes</div>
                </div>
                <div class="text-center">
                    <div class="stats-number">8 años</div>
                    <div class="stats-label mt-3">Construyendo marcas</div>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES -->
    <section id="servicios" class="relative section-spacing" aria-labelledby="services-heading">
        <div aria-hidden="true" class="absolute top-40 right-0 w-96 h-96 rounded-full blur-[150px]" style="background: var(--accent-red-soft);"></div>

        <div class="relative max-w-7xl mx-auto container-pad">
            <div class="max-w-3xl mb-20 sm:mb-24">
                <span class="eyebrow">Nuestros servicios</span>
                <h2 id="services-heading" class="font-display text-white mb-8">
                    Soluciones digitales <span class="text-red-accent">end-to-end</span>
                </h2>
                <p class="text-white/60 text-base sm:text-xl leading-relaxed">
                    Combinamos estrategia, diseño y tecnología para crear experiencias que destacan
                    y generan resultados medibles para tu negocio.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @php
                    $services = [
                        ['title' => 'Branding & Identidad', 'desc' => 'Identidad visual completa, logos distintivos, sistemas de marca y guías que comunican la esencia de tu negocio.', 'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01', 'tags' => ['Logo', 'Identidad', 'Brand Guidelines']],
                        ['title' => 'Desarrollo Web', 'desc' => 'Sitios web premium con UX/UI excepcional. Landing pages que convierten, sitios corporativos y plataformas custom.', 'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'tags' => ['Next.js', 'Laravel', 'Headless CMS']],
                        ['title' => 'E-commerce', 'desc' => 'Tiendas virtuales optimizadas para conversión. Shopify, WooCommerce o soluciones custom de alto rendimiento.', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', 'tags' => ['Shopify', 'WooCommerce', 'Custom']],
                        ['title' => 'Performance Marketing', 'desc' => 'Campañas en Meta Ads, Google Ads y TikTok. Optimizamos hasta el último centavo para maximizar tu ROI.', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'tags' => ['Meta Ads', 'Google Ads', 'Analytics']],
                        ['title' => 'Contenido & Video', 'desc' => 'Producción audiovisual profesional: reels virales, videos corporativos, motion graphics y estrategia de contenido.', 'icon' => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664zM21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'tags' => ['Reels', 'Motion', 'Storytelling']],
                        ['title' => 'Consultoría Estratégica', 'desc' => 'Asesoría experta en transformación digital, optimización de procesos y estrategias de crecimiento sostenible.', 'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', 'tags' => ['Strategy', 'Audit', 'Growth']],
                    ];
                @endphp

                @foreach($services as $service)
                    <article class="service-card">
                        <div class="service-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $service['icon'] }}"/>
                            </svg>
                        </div>
                        <h3 class="service-title">{{ $service['title'] }}</h3>
                        <p class="service-desc">{{ $service['desc'] }}</p>
                        <div class="service-tags">
                            @foreach($service['tags'] as $tag)
                                <span class="service-tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- PROCESS -->
    <section id="proceso" class="relative section-spacing">
        <div class="absolute inset-0" style="background: linear-gradient(180deg, transparent 0%, rgba(230, 57, 70, 0.03) 50%, transparent 100%);"></div>

        <div class="relative max-w-7xl mx-auto container-pad">
            <div class="text-center mb-20 sm:mb-24 max-w-3xl mx-auto">
                <span class="eyebrow">Nuestro proceso</span>
                <h2 class="font-display text-white mb-8">
                    De la idea al <span class="text-red-accent">lanzamiento</span>
                </h2>
                <p class="text-white/60 text-base sm:text-lg leading-relaxed">
                    Un proceso probado que combina estrategia, creatividad y ejecución impecable.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-12 sm:gap-10 lg:gap-8">
                @php
                    $steps = [
                        ['num' => '01', 'title' => 'Descubrimiento', 'desc' => 'Analizamos tu negocio, audiencia y objetivos para entender el panorama completo.', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                        ['num' => '02', 'title' => 'Estrategia', 'desc' => 'Definimos el roadmap creativo y técnico, alineando cada pieza con tus metas.', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                        ['num' => '03', 'title' => 'Ejecución', 'desc' => 'Diseñamos y desarrollamos cada pieza con atención meticulosa al detalle.', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
                        ['num' => '04', 'title' => 'Lanzamiento', 'desc' => 'Desplegamos con soporte continuo y optimización basada en resultados reales.', 'icon' => 'M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58'],
                    ];
                @endphp

                @foreach($steps as $i => $step)
                    <div class="text-center group relative">
                        @if($i < 3)
                            <div aria-hidden="true" class="hidden lg:block absolute top-10 left-[calc(50%+50px)] w-[calc(100%-100px)] h-px" style="background: linear-gradient(to right, var(--accent-red-soft), transparent);"></div>
                        @endif

                        <div class="relative w-20 h-20 mx-auto mb-8">
                            <div aria-hidden="true" class="absolute inset-0 rounded-2xl blur-2xl group-hover:blur-3xl transition-all duration-500" style="background: var(--accent-red-glow); opacity: 0.4;"></div>
                            <div class="relative w-full h-full glass-card rounded-2xl flex items-center justify-center" style="border: 1px solid rgba(230, 57, 70, 0.3);">
                                <svg class="w-8 h-8 text-red-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $step['icon'] }}"/>
                                </svg>
                            </div>
                            <span class="absolute -top-2 -right-2 w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background: var(--accent-red);">{{ $step['num'] }}</span>
                        </div>

                        <h3 class="text-lg sm:text-xl font-bold text-white mb-4">{{ $step['title'] }}</h3>
                        <p class="text-white/45 text-sm sm:text-base leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section id="testimonios" class="relative section-spacing">
        <div class="relative max-w-7xl mx-auto container-pad">
            <div class="text-center mb-20 sm:mb-24 max-w-3xl mx-auto">
                <span class="eyebrow">Testimonios</span>
                <h2 class="font-display text-white mb-6">
                    Lo que dicen nuestros <span class="text-red-accent">clientes</span>
                </h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @php
                    $testimonials = [
                        ['quote' => 'Trabajar con Kamo transformó completamente nuestra presencia digital. Triplicamos las conversiones en 3 meses.', 'name' => 'María González', 'role' => 'CEO, Lumina Studio', 'initial' => 'M'],
                        ['quote' => 'La atención al detalle y la estrategia detrás de cada decisión es impresionante. Súper recomendados.', 'name' => 'Andrés Pérez', 'role' => 'Founder, Verdex', 'initial' => 'A'],
                        ['quote' => 'Profesionales de primer nivel. Nos entregaron un sitio que no solo se ve bien, también vende.', 'name' => 'Laura Mendoza', 'role' => 'Marketing Lead, Atlas', 'initial' => 'L'],
                    ];
                @endphp

                @foreach($testimonials as $t)
                    <figure class="testimonial-card flex flex-col">
                        <div class="flex items-center gap-1 mb-4" aria-label="5 estrellas">
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
            <span class="eyebrow">Empecemos hoy</span>
            <h2 class="font-display text-white mb-8">
                ¿Listo para <span class="text-red-accent">escalar</span> tu negocio?
            </h2>
            <p class="text-base sm:text-xl text-white/65 mb-12 max-w-2xl mx-auto leading-relaxed">
                Cuéntanos sobre tu proyecto. Recibirás una propuesta inicial en menos de 48 horas, sin compromiso.
            </p>

            <div class="cta-group justify-center" style="max-width: 480px; margin: 0 auto;">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary">
                        Ir al Dashboard
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                @else
                    <a href="mailto:hola@kamo.agency" class="btn-primary">
                        Hablemos de tu proyecto
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="mailto:hola@kamo.agency" class="btn-secondary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Escríbenos
                    </a>
                @endauth
            </div>

            <div class="mt-12 sm:mt-14 flex flex-wrap items-center justify-center gap-x-8 gap-y-4 text-sm text-white/50">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Respuesta en 48h
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Sin compromiso
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Propuesta personalizada
                </span>
            </div>
        </div>
    </section>
    </main>

    @include('partials.landing-footer')

    @include('partials.landing-nav-js')

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
    </script>
</div>
