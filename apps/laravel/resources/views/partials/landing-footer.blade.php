<footer class="relative z-10 border-t border-white/5 py-10">
    <div class="max-w-7xl mx-auto container-pad flex flex-col sm:flex-row justify-between items-center gap-6">
        <a href="{{ route('landing') }}" class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center border border-white/20" style="background: linear-gradient(135deg, #18181B, #27272A);">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="font-display font-bold text-white text-lg tracking-tight">Kosta</span>
        </a>
        <p class="text-xs text-white/30">© {{ date('Y') }} Kosta Studio Films. Todos los derechos reservados.</p>
        <div class="flex items-center gap-6 text-xs text-white/40">
            <a href="{{ route('landing') }}" class="hover:text-white transition-colors @if(request()->routeIs('landing')) text-white/70 @endif">Inicio</a>
            <a href="/#servicios" class="hover:text-white transition-colors @if(request()->is('servicios*')) text-white/70 @endif">Servicios</a>
            <a href="{{ route('portfolio') }}" class="hover:text-white transition-colors @if(request()->routeIs('portfolio')) text-white/70 @endif">Portafolio</a>
            <a href="{{ route('contact') }}" class="hover:text-white transition-colors @if(request()->routeIs('contact')) text-white/70 @endif">Contacto</a>
        </div>
    </div>
</footer>
