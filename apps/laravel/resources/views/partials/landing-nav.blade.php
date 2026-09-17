<style>
    .container-pad { padding-left: 24px; padding-right: 24px; }
    @media (min-width: 640px) { .container-pad { padding-left: 40px; padding-right: 40px; } }
    @media (min-width: 1024px) { .container-pad { padding-left: 56px; padding-right: 56px; } }

    /* ── Mobile Menu ── */
    .mobile-menu { position: fixed; inset: 0; z-index: 60; pointer-events: none; }
    .mobile-menu.open { pointer-events: auto; }
    .mobile-drawer { position: absolute; inset: 0; width: 100%; height: 100%; background: rgba(10, 6, 8, 0.99); transform: translateX(100%); transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1); display: flex; flex-direction: column; }
    .mobile-menu.open .mobile-drawer { transform: translateX(0); }
    .mobile-drawer-header { display: flex; align-items: center; justify-content: space-between; padding: 0 20px; height: 64px; flex-shrink: 0; border-bottom: 1px solid rgba(255,255,255,0.06); }
    .mobile-drawer-body { flex: 1; overflow-y: auto; padding: 8px 28px 40px; display: flex; flex-direction: column; }
    .mobile-nav-link { display: block; font-size: 24px; font-weight: 700; font-family: 'Syne', sans-serif; color: rgba(255,255,255,0.7); text-decoration: none; padding: 20px 0; border-bottom: 1px solid rgba(255,255,255,0.06); transition: color 0.2s ease; }
    .mobile-nav-link:hover { color: var(--accent-red); }

    /* ── Hamburger ── */
    .hamburger-btn { display: flex; flex-direction: column; gap: 5px; width: 28px; padding: 4px 0; background: none; border: none; cursor: pointer; }
    @media (min-width: 768px) { .hamburger-btn { display: none; } }
    @media (max-width: 767px) { .landing-nav-cta { display: none !important; } }
    .hamburger-btn span { display: block; height: 2px; border-radius: 2px; background: white; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); transform-origin: center; }
    .hamburger-btn.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
    .hamburger-btn.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
    .hamburger-btn.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

    /* ── Ghost CTA ── */
    .btn-nav-ghost { display: inline-flex; align-items: center; gap: 6px; height: 40px; padding: 0 18px; border-radius: 999px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.13); color: rgba(255,255,255,0.9); font-size: 13.5px; font-weight: 500; text-decoration: none; white-space: nowrap; transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease; }
    .btn-nav-ghost:hover { background: rgba(255,255,255,0.11); border-color: rgba(255,255,255,0.22); color: #ffffff; }

    /* ── WhatsApp ── */
    .nav-whatsapp-btn { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.6); flex-shrink: 0; transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease; }
    .nav-whatsapp-btn:hover { background: rgba(37,211,102,0.12); border-color: rgba(37,211,102,0.38); color: #25D366; }
</style>

<a href="#main" class="skip-link">Saltar al contenido</a>

<!-- Navigation -->
<nav class="floating-site-nav" aria-label="Navegación principal">
    <div class="relative max-w-7xl mx-auto container-pad floating-site-nav-frame">
        <div class="flex justify-between items-center h-16 sm:h-[68px]">
            <a href="{{ route('landing') }}" class="flex items-center gap-3" aria-label="Kamo.Dev - Inicio">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, var(--accent-red-dark), var(--accent-red)); box-shadow: 0 4px 16px rgba(230,57,70,0.3);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="font-display text-2xl font-bold text-white">Kamo.Dev</span>
            </a>

            <div class="hidden md:flex items-center gap-8">
                <a href="/#servicios" class="nav-link text-sm">Servicios</a>
                <a href="{{ route('portfolio') }}" class="nav-link text-sm" @if(request()->routeIs('portfolio')) style="color:#fff;" @endif>Portafolio</a>
                <a href="{{ route('contact') }}" class="nav-link text-sm" @if(request()->routeIs('contact')) style="color:#fff;" @endif>Contacto</a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('contact') }}" class="btn-nav-ghost landing-nav-cta">
                    Empezar proyecto
                    <svg class="w-3 h-3" viewBox="0 0 10 10" fill="none" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M1.5 8.5L8.5 1.5M8.5 1.5H3M8.5 1.5v5.5"/>
                    </svg>
                </a>

                <a href="https://wa.me/573113894136" target="_blank" rel="noopener noreferrer" class="nav-whatsapp-btn flex" aria-label="Contáctanos por WhatsApp">
                    <svg class="w-[17px] h-[17px]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </a>

                <button class="hamburger-btn md:hidden" id="menuToggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="mobileMenu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Menu -->
<div id="mobileMenu" class="mobile-menu" role="dialog" aria-modal="true" aria-label="Menú de navegación" hidden>
    <div class="mobile-drawer">
        <div class="mobile-drawer-header">
            <a href="{{ route('landing') }}" class="flex items-center gap-3" aria-label="Kamo.Dev - Inicio">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, var(--accent-red-dark), var(--accent-red));">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="font-display text-2xl font-bold text-white">Kamo.Dev</span>
            </a>
            <button class="w-10 h-10 flex items-center justify-center text-white/60 hover:text-white transition-colors rounded-full hover:bg-white/10" id="menuClose" aria-label="Cerrar menú">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="mobile-drawer-body">
            <nav class="flex flex-col">
                <a href="{{ route('landing') }}" class="mobile-nav-link" @if(request()->routeIs('landing')) style="color:var(--accent-red)" @endif>Inicio</a>
                <a href="/#servicios" class="mobile-nav-link">Servicios</a>
                <a href="{{ route('portfolio') }}" class="mobile-nav-link" @if(request()->routeIs('portfolio')) style="color:var(--accent-red)" @endif>Portafolio</a>
                <a href="{{ route('contact') }}" class="mobile-nav-link" @if(request()->routeIs('contact')) style="color:var(--accent-red)" @endif>Contacto</a>
            </nav>

            <div class="pt-10 flex flex-col gap-3 mt-auto">
                <a href="{{ route('contact') }}" class="btn-primary justify-center">Empezar proyecto</a>
            </div>
        </div>
    </div>
</div>
