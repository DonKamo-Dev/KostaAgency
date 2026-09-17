import { resolvePreset, MODE_DRAWS } from 'thinking-orbs/engine';
import { presetSizeFor } from './thinking-orb-preset.js';

class ThinkingOrbElement extends HTMLElement {
    static get observedAttributes() {
        return ['state', 'size', 'speed', 'theme', 'label', 'pill', 'large'];
    }

    constructor() {
        super();
        this._canvas = null;
        this._animId = null;
        this._running = false;
        this._io = null;
        this._size = 20;
        this._dpr = 1;
        this._state = 'working';
    }

    connectedCallback() {
        this.render();
        this.start();
    }

    disconnectedCallback() {
        this.stop();
    }

    attributeChangedCallback(name, oldVal, newVal) {
        if (oldVal !== newVal && this._canvas && this.isConnected) {
            this.render();
            this.start();
        }
    }

    render() {
        const state = this.getAttribute('state') || 'working';
        const rawSize = this.getAttribute('size');
        const isLarge = this.hasAttribute('large') || (rawSize && parseInt(rawSize, 10) >= 48);
        const size = parseInt(rawSize || (isLarge ? '56' : '20'), 10);
        const label = this.getAttribute('label');
        const isPill = this.hasAttribute('pill') || !!label;

        this.innerHTML = '';
        this.style.display = isPill ? 'inline-flex' : 'inline-block';
        this.style.verticalAlign = 'middle';

        const wrapper = document.createElement('div');
        if (isPill) {
            wrapper.className = isLarge ? 'thinking-pill thinking-pill-lg' : 'thinking-pill';
        } else {
            wrapper.style.display = 'inline-flex';
            wrapper.style.alignItems = 'center';
            wrapper.style.justifyContent = 'center';
        }

        const canvas = document.createElement('canvas');
        const dpr = Math.min(2, window.devicePixelRatio || 1);
        canvas.width = Math.round(size * dpr);
        canvas.height = Math.round(size * dpr);
        canvas.style.width = `${size}px`;
        canvas.style.height = `${size}px`;
        canvas.style.display = 'block';
        canvas.setAttribute('role', 'img');
        canvas.setAttribute('aria-label', label || state);

        wrapper.appendChild(canvas);

        if (label) {
            const span = document.createElement('span');
            span.className = 't-shimmer';
            span.setAttribute('data-text', label);
            span.textContent = label;
            wrapper.appendChild(span);
        }

        this.appendChild(wrapper);
        this._canvas = canvas;
        this._size = size;
        this._dpr = dpr;
        this._state = state;
    }

    start() {
        if (!this._canvas) return;
        this.stop();

        const canvas = this._canvas;
        const ctx = canvas.getContext('2d');
        if (!ctx) return;

        const size = this._size;
        const dpr = this._dpr;
        const state = this._state;
        const speedMultiplier = parseFloat(this.getAttribute('speed') || '1');
        const themeAttr = this.getAttribute('theme');
        const isDark = themeAttr === 'light' ? false : (themeAttr === 'dark' ? true : document.documentElement.classList.contains('dark') || !document.documentElement.classList.contains('light'));

        const { mode, speed, opts } = resolvePreset(state, presetSizeFor(size));
        const drawFn = MODE_DRAWS[mode];
        if (!drawFn) return;

        const totalSpeed = speed * speedMultiplier;

        const draw = (t) => {
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            ctx.clearRect(0, 0, size, size);
            drawFn(ctx, size, t, isDark, opts);
        };

        const prefersReducedMotion = typeof window.matchMedia === 'function'
            && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReducedMotion) {
            draw(0.6);
            return;
        }

        const loop = () => {
            draw((performance.now() / 1000) * totalSpeed);
            if (this._running) {
                this._animId = requestAnimationFrame(loop);
            }
        };

        this._running = true;
        this._io = new IntersectionObserver(([entry]) => {
            if (entry.isIntersecting && document.visibilityState !== 'hidden') {
                if (!this._running) {
                    this._running = true;
                    this._animId = requestAnimationFrame(loop);
                }
            } else {
                this._running = false;
                if (this._animId) cancelAnimationFrame(this._animId);
            }
        });
        this._io.observe(canvas);

        this._animId = requestAnimationFrame(loop);
    }

    stop() {
        this._running = false;
        if (this._animId) {
            cancelAnimationFrame(this._animId);
            this._animId = null;
        }
        if (this._io) {
            this._io.disconnect();
            this._io = null;
        }
    }
}

if (!customElements.get('thinking-orb')) {
    customElements.define('thinking-orb', ThinkingOrbElement);
}

// Global HUD Controller
window.showThinkingLoader = function(label = 'Procesando...', state = 'working') {
    let hud = document.getElementById('global-thinking-hud');
    if (!hud) {
        hud = document.createElement('div');
        hud.id = 'global-thinking-hud';
        hud.className = 'global-thinking-hud';
        document.body.appendChild(hud);
    }
    hud.innerHTML = `<thinking-orb state="${state}" size="20" label="${label}" pill></thinking-orb>`;
    hud.classList.add('active');
};

window.hideThinkingLoader = function() {
    const hud = document.getElementById('global-thinking-hud');
    if (hud) {
        hud.classList.remove('active');
    }
};

let livewireTimer = null;
let fetchTimer = null;

function clearLoaderTimers() {
    clearTimeout(livewireTimer);
    clearTimeout(fetchTimer);
    livewireTimer = null;
    fetchTimer = null;
}

function dismissGlobalLoader() {
    clearLoaderTimers();
    window.hideThinkingLoader();
}

function installLivewireLoaderHook() {
    if (window.__kamoLivewireLoaderInstalled || !window.Livewire) {
        return;
    }

    window.__kamoLivewireLoaderInstalled = true;

    Livewire.hook('commit', ({ succeed, fail }) => {
        livewireTimer = setTimeout(() => {
            window.showThinkingLoader('Procesando...', 'working');
        }, 120);
        succeed(dismissGlobalLoader);
        fail(dismissGlobalLoader);
    });

    document.addEventListener('livewire:navigated', dismissGlobalLoader);
}

function installNavigationLoader() {
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (!link) return;
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
        if (link.target === '_blank' || link.hasAttribute('download')) return;
        if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;

        try {
            const url = new URL(href, window.location.origin);
            if (url.origin === window.location.origin && url.pathname !== window.location.pathname) {
                window.showThinkingLoader('Cargando...', 'working');
            }
        } catch (err) {}
    });
}

function installFetchLoader() {
    const originalFetch = window.fetch;
    let activeFetches = 0;

    window.fetch = async function (...args) {
        activeFetches++;
        if (activeFetches === 1) {
            fetchTimer = setTimeout(() => {
                if (activeFetches > 0) {
                    window.showThinkingLoader('Actualizando...', 'working');
                }
            }, 180);
        }
        try {
            return await originalFetch.apply(this, args);
        } finally {
            activeFetches = Math.max(0, activeFetches - 1);
            if (activeFetches === 0) {
                clearTimeout(fetchTimer);
                fetchTimer = null;
                window.hideThinkingLoader();
            }
        }
    };
}

if (!window.__kamoLoaderHooksInstalled) {
    window.__kamoLoaderHooksInstalled = true;
    document.addEventListener('livewire:init', installLivewireLoaderHook, { once: true });
    installLivewireLoaderHook();
    installNavigationLoader();
    installFetchLoader();
}

export { ThinkingOrbElement };
