<footer class="relative z-10 border-t border-white/5 py-10">
    <div class="max-w-7xl mx-auto container-pad flex flex-col sm:flex-row justify-between items-center gap-6">
        <a href="{{ route('landing') }}" class="flex items-center" aria-label="Kosta - {{ __('landing.nav.home') }}">
            <img src="{{ asset('img/WEB-Kosta-White.svg') }}" alt="Kosta" class="h-7 w-auto opacity-90 hover:opacity-100 transition-opacity" style="max-height: 30px;">
        </a>
        <p class="text-xs text-white/30 text-center sm:text-left">© {{ date('Y') }} {{ __('landing.footer.agency_full') }}. {{ __('landing.footer.rights') }}</p>
        <div class="flex items-center gap-6 text-xs text-white/40">
            <a href="{{ route('landing') }}" class="hover:text-white transition-colors @if(request()->routeIs('landing')) text-white/70 @endif">{{ __('landing.nav.home') }}</a>
            <a href="/#servicios" class="hover:text-white transition-colors @if(request()->is('servicios*')) text-white/70 @endif">{{ __('landing.nav.services') }}</a>
            <a href="{{ route('portfolio') }}" class="hover:text-white transition-colors @if(request()->routeIs('portfolio')) text-white/70 @endif">{{ __('landing.nav.portfolio') }}</a>
            <a href="{{ route('contact') }}" class="hover:text-white transition-colors @if(request()->routeIs('contact')) text-white/70 @endif">{{ __('landing.nav.contact') }}</a>
            <div class="lang-switcher ml-1">
                <a href="{{ route('locale.switch', 'es') }}" class="lang-btn {{ app()->getLocale() === 'es' ? 'active' : '' }}">ES</a>
                <span class="lang-divider">/</span>
                <a href="{{ route('locale.switch', 'en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
            </div>
        </div>
    </div>
</footer>
