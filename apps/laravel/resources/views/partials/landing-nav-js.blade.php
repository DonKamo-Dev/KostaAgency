<script>
(() => {
    const toggle   = document.getElementById('menuToggle');
    const menu     = document.getElementById('mobileMenu');
    const closeBtn = document.getElementById('menuClose');
    const drawer   = menu ? menu.querySelector('.mobile-drawer') : null;
    const header   = document.querySelector('.floating-site-nav');
    let closeTimer;

    // Efecto flotante al hacer scroll
    function updateHeaderOnScroll() {
        if (!header) return;
        const isScrolled = window.scrollY > 20;
        header.classList.toggle('is-scrolled', isScrolled);
    }

    window.addEventListener('scroll', updateHeaderOnScroll, { passive: true });
    document.addEventListener('livewire:navigated', updateHeaderOnScroll);
    updateHeaderOnScroll();

    function openMenu() {
        if (!menu || !toggle) return;
        clearTimeout(closeTimer);
        menu.hidden = false;
        menu.classList.add('open');
        toggle.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        if (!menu || !toggle || menu.hidden) return;

        clearTimeout(closeTimer);
        menu.classList.remove('open');
        toggle.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
        closeTimer = setTimeout(finishClose, 250);
    }

    function finishClose(event) {
        if (event && (event.target !== drawer || event.propertyName !== 'transform')) return;
        if (menu && !menu.classList.contains('open')) {
            clearTimeout(closeTimer);
            menu.hidden = true;
        }
    }

    if (toggle) toggle.addEventListener('click', () => menu && menu.hidden ? openMenu() : closeMenu());
    if (closeBtn) closeBtn.addEventListener('click', closeMenu);
    if (drawer) drawer.addEventListener('transitionend', finishClose);
    if (menu) menu.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                const top = target.getBoundingClientRect().top + window.scrollY - 88;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        });
    });
})();
</script>
