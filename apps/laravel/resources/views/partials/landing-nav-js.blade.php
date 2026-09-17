<script>
    const toggle   = document.getElementById('menuToggle');
    const menu     = document.getElementById('mobileMenu');
    const closeBtn = document.getElementById('menuClose');

    function openMenu() {
        menu.hidden = false;
        requestAnimationFrame(() => menu.classList.add('open'));
        toggle.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        menu.classList.remove('open');
        toggle.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
        setTimeout(() => { menu.hidden = true; }, 350);
    }

    toggle.addEventListener('click', () => menu.hidden ? openMenu() : closeMenu());
    closeBtn.addEventListener('click', closeMenu);
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
