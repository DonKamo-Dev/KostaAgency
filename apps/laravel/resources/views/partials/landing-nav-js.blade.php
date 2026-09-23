<script>
    const toggle   = document.getElementById('menuToggle');
    const menu     = document.getElementById('mobileMenu');
    const closeBtn = document.getElementById('menuClose');
    const drawer   = menu.querySelector('.mobile-drawer');
    let closeTimer;

    function openMenu() {
        clearTimeout(closeTimer);
        menu.hidden = false;
        requestAnimationFrame(() => menu.classList.add('open'));
        toggle.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        if (menu.hidden) return;

        clearTimeout(closeTimer);
        menu.classList.remove('open');
        toggle.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
        closeTimer = setTimeout(finishClose, 250);
    }

    function finishClose(event) {
        if (event && (event.target !== drawer || event.propertyName !== 'transform')) return;
        if (!menu.classList.contains('open')) {
            clearTimeout(closeTimer);
            menu.hidden = true;
        }
    }

    toggle.addEventListener('click', () => menu.hidden ? openMenu() : closeMenu());
    closeBtn.addEventListener('click', closeMenu);
    drawer.addEventListener('transitionend', finishClose);
    menu.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));

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
</script>
