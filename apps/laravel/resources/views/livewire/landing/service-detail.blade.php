<div>
    <!-- SEO / Head Meta via component -->
    <x-slot:title>{{ $service['title'] }} — Kosta Agency</x-slot:title>

    <style>
        .service-detail-hero {
            position: relative;
            padding-top: clamp(120px, 16vh, 180px);
            padding-bottom: clamp(60px, 10vh, 100px);
            overflow: hidden;
        }

        .sd-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 999px;
            background: rgba(230, 57, 70, 0.12);
            border: 1px solid rgba(230, 57, 70, 0.35);
            color: #FFFFFF;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .sd-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(34px, 5.5vw, 64px);
            font-weight: 800;
            line-height: 1.08;
            color: #FFFFFF;
            letter-spacing: -0.025em;
            margin-bottom: 24px;
            max-width: 950px;
        }

        .sd-subtitle {
            font-family: 'Inter', sans-serif;
            font-size: clamp(16px, 1.8vw, 20px);
            line-height: 1.65;
            color: rgba(255, 255, 255, 0.65);
            max-width: 780px;
            margin-bottom: 36px;
        }

        .sd-stat-card {
            background: rgba(18, 18, 20, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 24px 28px;
            backdrop-filter: blur(16px);
            transition: transform 0.3s ease, border-color 0.3s ease;
        }
        .sd-stat-card:hover {
            transform: translateY(-3px);
            border-color: rgba(230, 57, 70, 0.4);
        }

        .sd-card {
            background: rgba(18, 18, 22, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 22px;
            padding: 32px 28px;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .sd-card:hover {
            transform: translateY(-6px);
            border-color: rgba(230, 57, 70, 0.4);
            background: linear-gradient(145deg, rgba(230, 57, 70, 0.08), rgba(18, 18, 22, 0.95));
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.5), 0 0 30px rgba(230, 57, 70, 0.1);
        }

        .sd-pill-tag {
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.25s ease;
        }
        .sd-pill-tag:hover {
            background: rgba(230, 57, 70, 0.12);
            border-color: rgba(230, 57, 70, 0.35);
            color: #FFFFFF;
            transform: translateY(-2px);
        }

        .sd-step-box {
            background: rgba(18, 18, 20, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 18px;
            padding: 30px 24px;
            position: relative;
            transition: all 0.3s ease;
        }
        .sd-step-box:hover {
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-4px);
        }

        .sd-cta-banner {
            background:
                radial-gradient(ellipse at 10% 0%, rgba(230, 57, 70, 0.32) 0%, transparent 60%),
                radial-gradient(ellipse at 90% 100%, rgba(230, 57, 70, 0.2) 0%, transparent 50%),
                linear-gradient(145deg, rgba(35, 14, 18, 0.98) 0%, rgba(20, 10, 12, 0.98) 60%, rgba(12, 8, 10, 0.99) 100%);
            border: 1px solid rgba(230, 57, 70, 0.45);
            border-radius: 28px;
            padding: clamp(40px, 6vw, 70px) clamp(24px, 5vw, 60px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6), 0 0 50px rgba(230, 57, 70, 0.15);
        }
    </style>

    <!-- ── 1. HERO SECTION ── -->
    <section class="service-detail-hero">
        <!-- Ambient Red Glow Background -->
        <div aria-hidden="true" class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[450px] rounded-full blur-[200px] pointer-events-none" style="background: radial-gradient(circle, rgba(230, 57, 70, 0.22) 0%, rgba(28, 12, 16, 0.15) 50%, transparent 70%);"></div>

        <div class="relative max-w-7xl mx-auto container-pad">
            <!-- Breadcrumbs -->
            <nav style="display:flex;align-items:center;gap:10px;margin-bottom:28px;font-size:13px;color:rgba(255,255,255,0.4);">
                <a href="{{ route('landing') }}" wire:navigate style="color:rgba(255,255,255,0.6);text-decoration:none;transition:color .2s;">Inicio</a>
                <span>/</span>
                <a href="{{ route('landing') }}#servicios" wire:navigate style="color:rgba(255,255,255,0.6);text-decoration:none;transition:color .2s;">Servicios</a>
                <span>/</span>
                <span style="color:#FFFFFF;font-weight:600;">{{ $service['title'] }}</span>
            </nav>

            <!-- Pillar Badge -->
            <div class="sd-badge">
                <span style="width:7px;height:7px;border-radius:50%;background:#EF4444;box-shadow:0 0 10px #EF4444;"></span>
                <span>{{ $service['pillar'] }}</span>
            </div>

            <!-- Title -->
            <h1 class="sd-title">
                {!! $service['headline'] !!}
            </h1>

            <!-- Subtitle -->
            <p class="sd-subtitle">
                {{ $service['subtitle'] }}
            </p>

            <!-- Action Buttons -->
            <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:60px;">
                <a href="{{ route('contact', ['servicio' => $service['contact_name']]) }}" wire:navigate class="btn-primary" style="padding:16px 36px;font-size:15px;font-weight:700;box-shadow:0 10px 30px rgba(255,255,255,0.15);">
                    Solicitar Cotización de {{ $service['title'] }}
                    <svg style="width:16px;height:16px;margin-left:8px;" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a href="{{ route('portfolio') }}" wire:navigate class="btn-secondary" style="padding:16px 28px;font-size:15px;font-weight:600;">
                    Ver Casos de Éxito
                </a>
            </div>

            <!-- Key Metrics / Stats -->
            @if(!empty($service['stats']))
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    @foreach($service['stats'] as $st)
                        <div class="sd-stat-card">
                            <div style="font-family:'Syne',sans-serif;font-size:clamp(28px, 3.5vw, 40px);font-weight:800;color:#FFFFFF;letter-spacing:-0.03em;margin-bottom:6px;">
                                {{ $st['value'] }}
                            </div>
                            <div style="font-size:13.5px;color:rgba(255,255,255,0.55);font-weight:500;">
                                {{ $st['label'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- ── 2. WHAT'S INCLUDED / DELIVERABLES ── -->
    <section class="relative section-spacing" style="border-top: 1px solid rgba(255,255,255,0.06);background:rgba(10,10,12,0.4);">
        <div class="relative max-w-7xl mx-auto container-pad">
            <div class="max-w-2xl mb-14">
                <span class="eyebrow">Alcance & Soluciones</span>
                <h2 class="font-display text-white mb-6">
                    Todo lo que incluye nuestro servicio de <span class="text-red-accent">{{ $service['title'] }}</span>
                </h2>
                <p class="text-white/60 text-base sm:text-lg leading-relaxed">
                    Un modelo de entrega estructurado para que obtengas resultados tangibles, sin sorpresas ni costos ocultos.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 gap-6 lg:gap-8">
                @foreach($service['deliverables'] as $idx => $deliv)
                    <div class="sd-card">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                            <span style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700;color:var(--accent-red);background:rgba(230,57,70,0.12);border:1px solid rgba(230,57,70,0.25);border-radius:8px;padding:4px 10px;">
                                0{{ $idx + 1 }}
                            </span>
                            <div style="width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,0.05);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.6);">
                                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <h3 style="font-family:'Syne',sans-serif;font-size:20px;font-weight:700;color:#FFFFFF;margin-bottom:12px;line-height:1.25;">
                            {{ $deliv['title'] }}
                        </h3>
                        <p style="font-size:14.5px;color:rgba(255,255,255,0.6);line-height:1.65;margin:0;">
                            {{ $deliv['desc'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ── 3. TECH STACK & TOOLS ── -->
    <section class="relative section-spacing" style="border-top: 1px solid rgba(255,255,255,0.06);">
        <div class="relative max-w-7xl mx-auto container-pad">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="eyebrow">Stack & Herramientas</span>
                <h2 class="font-display text-white mb-4">
                    Tecnología y estándares <span class="text-red-accent">sin compromisos</span>
                </h2>
                <p class="text-white/60 text-base leading-relaxed">
                    Utilizamos herramientas probadas en la industria para garantizar rendimiento, escalabilidad y propiedad total.
                </p>
            </div>

            <div style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center;max-width:900px;margin:0 auto;">
                @foreach($service['all_tags'] ?? $service['tags'] as $tech)
                    <span class="sd-pill-tag">
                        {{ $tech }}
                    </span>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ── 4. STEP-BY-STEP PROCESS ── -->
    <section class="relative section-spacing" style="border-top: 1px solid rgba(255,255,255,0.06);background:rgba(10,10,12,0.5);">
        <div class="relative max-w-7xl mx-auto container-pad">
            <div class="max-w-2xl mb-14">
                <span class="eyebrow">Metodología Kosta</span>
                <h2 class="font-display text-white mb-6">
                    Cómo trabajamos en <span class="text-red-accent">{{ $service['title'] }}</span>
                </h2>
                <p class="text-white/60 text-base sm:text-lg leading-relaxed">
                    Un proceso iterativo y transparente de 4 fases para garantizar entregas a tiempo y con la máxima calidad.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($service['process'] as $step)
                    <div class="sd-step-box">
                        <div style="font-family:'Syne',sans-serif;font-size:32px;font-weight:800;color:rgba(230,57,70,0.5);line-height:1;margin-bottom:16px;">
                            {{ $step['step'] }}
                        </div>
                        <h4 style="font-family:'Syne',sans-serif;font-size:17px;font-weight:700;color:#FFFFFF;margin-bottom:10px;line-height:1.3;">
                            {{ $step['title'] }}
                        </h4>
                        <p style="font-size:13.5px;color:rgba(255,255,255,0.55);line-height:1.6;margin:0;">
                            {{ $step['desc'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ── 5. RELATED PROJECTS / PORTFOLIO LINK ── -->
    <section class="relative section-spacing" style="border-top: 1px solid rgba(255,255,255,0.06);">
        <div class="relative max-w-7xl mx-auto container-pad">
            <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:20px;margin-bottom:36px;">
                <div>
                    <span class="eyebrow">Casos Reales</span>
                    <h2 class="font-display text-white">
                        Resultados en <span class="text-red-accent">{{ $service['title'] }}</span>
                    </h2>
                </div>
                <a href="{{ route('portfolio') }}" wire:navigate class="btn-secondary" style="font-size:14px;padding:10px 20px;">
                    Ver todos los proyectos ↗
                </a>
            </div>

            @if($relatedStudies->isNotEmpty())
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedStudies as $st)
                        <div style="background:#131316;border:1px solid rgba(255,255,255,0.08);border-radius:18px;overflow:hidden;transition:transform .3s ease, border-color .3s ease;"
                             onmouseenter="this.style.borderColor='rgba(230,57,70,0.4)';this.style.transform='translateY(-4px)'"
                             onmouseleave="this.style.borderColor='rgba(255,255,255,0.08)';this.style.transform='translateY(0)'">
                            <div style="height:180px;position:relative;background:linear-gradient(135deg, {{ $st->gradient_inicio }}, {{ $st->gradient_fin }});overflow:hidden;">
                                @if($st->imagen)
                                    <img src="{{ Storage::url($st->imagen) }}" alt="{{ $st->titulo }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.7);font-family:'Syne',sans-serif;font-weight:700;">
                                        {{ $st->titulo }}
                                    </div>
                                @endif
                            </div>
                            <div style="padding:22px;">
                                <h4 style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;color:#FFFFFF;margin-bottom:8px;">
                                    {{ $st->titulo }}
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
                <div style="background:rgba(18,18,22,0.6);border:1px dashed rgba(255,255,255,0.1);border-radius:18px;padding:48px 24px;text-align:center;">
                    <div style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;color:#FFFFFF;margin-bottom:8px;">
                        Proyectos en producción
                    </div>
                    <p style="font-size:14px;color:rgba(255,255,255,0.55);max-width:500px;margin:0 auto 20px;">
                        Estamos finalizando nuevos casos de éxito para este servicio. Conoce nuestras soluciones globales en el portafolio.
                    </p>
                    <a href="{{ route('portfolio') }}" wire:navigate class="btn-primary" style="padding:10px 22px;font-size:13px;">
                        Explorar Galería Completa
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- ── 6. CONVERSION CTA BANNER ── -->
    <section class="relative section-spacing">
        <div class="relative max-w-7xl mx-auto container-pad">
            <div class="sd-cta-banner text-center max-w-5xl mx-auto">
                <span class="eyebrow" style="color:rgba(255,255,255,0.7);">Siguiente Paso</span>
                <h2 class="font-display text-white mb-6" style="font-size:clamp(30px, 4.5vw, 50px);line-height:1.15;">
                    ¿Listo para llevar tu <span class="text-red-accent">{{ $service['title'] }}</span> al siguiente nivel?
                </h2>
                <p class="text-white/75 text-base sm:text-lg max-w-2xl mx-auto mb-10 leading-relaxed">
                    Cuéntanos tu reto. Diseñamos una propuesta clara con tiempos, arquitectura y presupuesto transparente en menos de 24 horas.
                </p>
                <div style="display:flex;align-items:center;justify-content:center;gap:16px;flex-wrap:wrap;">
                    <a href="{{ route('contact', ['servicio' => $service['contact_name']]) }}" wire:navigate class="btn-primary" style="padding:16px 36px;font-size:15px;font-weight:700;">
                        Iniciar Proyecto de {{ $service['title'] }}
                    </a>
                    <a href="https://wa.me/573000000000?text={{ urlencode('Hola Kosta, me interesa el servicio de ' . $service['title']) }}" target="_blank" rel="noopener noreferrer" class="btn-secondary" style="padding:16px 28px;font-size:15px;font-weight:600;">
                        Hablar por WhatsApp ↗
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ── 7. OTHER SERVICES ── -->
    <section class="relative section-spacing" style="border-top:1px solid rgba(255,255,255,0.06);background:#070708;">
        <div class="relative max-w-7xl mx-auto container-pad">
            <div class="mb-10 text-center">
                <span class="eyebrow">Soluciones Complementarias</span>
                <h3 class="font-display text-white" style="font-size:28px;">
                    Explora otros servicios de <span class="text-red-accent">Kosta</span>
                </h3>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($otherServices as $other)
                    <a href="{{ route('services.show', $other['slug']) }}" wire:navigate
                       style="background:rgba(18,18,22,0.7);border:1px solid rgba(255,255,255,0.07);border-radius:18px;padding:24px 22px;text-decoration:none;display:flex;flex-direction:column;justify-content:space-between;transition:all .3s ease;"
                       onmouseenter="this.style.borderColor='rgba(230,57,70,0.4)';this.style.transform='translateY(-4px)'"
                       onmouseleave="this.style.borderColor='rgba(255,255,255,0.07)';this.style.transform='translateY(0)'">
                        <div>
                            <span style="font-size:10.5px;font-weight:700;color:rgba(255,255,255,0.4);letter-spacing:0.12em;text-transform:uppercase;display:block;margin-bottom:8px;">
                                {{ $other['pillar'] }}
                            </span>
                            <h4 style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;color:#FFFFFF;margin-bottom:8px;">
                                {{ $other['title'] }}
                            </h4>
                            <p style="font-size:13px;color:rgba(255,255,255,0.5);line-height:1.5;margin:0 0 16px;">
                                {{ \Illuminate\Support\Str::limit($other['subtitle'], 90) }}
                            </p>
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;font-size:12.5px;font-weight:600;color:var(--accent-red);">
                            <span>{{ $other['btn_text'] ?? 'Conocer servicio' }}</span>
                            <svg style="width:14px;height:14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</div>
