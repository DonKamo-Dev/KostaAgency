<style>
    .case-preview-trigger {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        padding: 0;
        border: 0;
        background: transparent;
        cursor: zoom-in;
    }
    .case-preview-trigger img { transition: transform .45s ease, filter .45s ease; }
    .case-preview-trigger:hover img { transform: scale(1.025); filter: brightness(.72); }
    .case-preview-hint {
        position: absolute;
        right: 14px;
        bottom: 14px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 11px;
        border: 1px solid rgba(255,255,255,.18);
        border-radius: 999px;
        background: rgba(8,8,10,.72);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .02em;
        opacity: 0;
        transform: translateY(5px);
        transition: opacity .25s ease, transform .25s ease;
        backdrop-filter: blur(10px);
    }
    .case-preview-trigger:hover .case-preview-hint,
    .case-preview-trigger:focus-visible .case-preview-hint { opacity: 1; transform: translateY(0); }
    .case-preview-trigger:focus-visible { outline: 3px solid #e63946; outline-offset: -3px; }

    .project-title-link {
        background: none;
        border: none;
        padding: 0;
        margin: 0;
        font: inherit;
        font-weight: inherit;
        font-size: inherit;
        color: inherit;
        text-align: left;
        cursor: pointer;
        display: inline;
        transition: color .2s ease;
        text-decoration: none;
    }
    .project-title-link:hover {
        color: #e63946;
    }
    .project-title-link:focus-visible {
        outline: 2px solid #e63946;
        outline-offset: 2px;
        border-radius: 4px;
    }

    .case-viewer {
        width: min(96vw, 1500px);
        max-width: none;
        height: min(94vh, 1000px);
        max-height: none;
        padding: 0;
        border: 1px solid rgba(255,255,255,.14);
        border-radius: 18px;
        overflow: hidden;
        background: #0b0b0d;
        color: #fff;
        box-shadow: 0 35px 100px rgba(0,0,0,.72);
    }
    .case-viewer::backdrop { background: rgba(3,3,5,.9); backdrop-filter: blur(12px); }
    .case-viewer[open] { animation: caseViewerIn .22s ease-out; }
    .case-viewer-bar {
        min-height: 58px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 10px 16px 10px 22px;
        border-bottom: 1px solid rgba(255,255,255,.08);
        background: #111114;
    }
    .case-viewer-title {
        overflow: hidden;
        font-size: 15px;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    .case-viewer-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }
    .case-viewer-btn {
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 0 15px;
        border-radius: 999px;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: all .2s ease;
        line-height: 1;
    }
    .case-viewer-btn svg {
        width: 12px;
        height: 12px;
        transition: transform .2s ease;
    }
    .case-viewer-btn:hover svg {
        transform: translate(1.5px, -1.5px);
    }
    .case-viewer-btn-website {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.16);
        color: #ffffff;
    }
    .case-viewer-btn-website:hover {
        background: #ffffff;
        border-color: #ffffff;
        color: #09090b;
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(255, 255, 255, 0.18);
    }
    .case-viewer-btn-contact {
        background: #e63946;
        border: 1px solid #e63946;
        color: #ffffff;
        box-shadow: 0 4px 16px rgba(230, 57, 70, 0.35);
    }
    .case-viewer-btn-contact:hover {
        background: #ff4d5d;
        border-color: #ff4d5d;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(230, 57, 70, 0.5);
    }
    .case-viewer-close {
        width: 36px;
        height: 36px;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 50%;
        background: rgba(255,255,255,.06);
        color: #fff;
        cursor: pointer;
        transition: background .2s ease, transform .2s ease;
    }
    .case-viewer-close:hover { background: #e63946; transform: rotate(4deg); }
    .case-viewer-stage {
        height: calc(100% - 58px);
        display: grid;
        place-items: center;
        padding: clamp(12px, 2.5vw, 34px);
        overflow: auto;
        background: radial-gradient(circle at 50% 30%, #202025 0, #0b0b0d 62%);
    }
    .case-viewer-image { display: block; max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain; box-shadow: 0 20px 65px rgba(0,0,0,.45); }
    @keyframes caseViewerIn { from { opacity: 0; transform: scale(.975); } to { opacity: 1; transform: scale(1); } }
    @media (max-width: 640px) {
        .case-viewer { width: 100vw; height: 100dvh; border: 0; border-radius: 0; }
        .case-viewer-bar {
            height: auto;
            min-height: 54px;
            padding: 8px 12px;
            gap: 8px;
            flex-wrap: wrap;
        }
        .case-viewer-title {
            width: 100%;
            font-size: 13px;
        }
        .case-viewer-actions {
            width: 100%;
            justify-content: flex-end;
            gap: 6px;
        }
        .case-viewer-btn {
            height: 32px;
            padding: 0 10px;
            font-size: 11px;
            gap: 5px;
        }
        .case-viewer-close {
            width: 32px;
            height: 32px;
        }
        .case-preview-hint { opacity: 1; transform: none; }
    }
</style>

<dialog class="case-viewer" data-case-viewer aria-labelledby="case-viewer-title">
    <div class="case-viewer-bar">
        <div class="case-viewer-title" id="case-viewer-title" data-case-viewer-title></div>
        <div class="case-viewer-actions">
            <a href="#"
               target="_blank"
               rel="noopener noreferrer"
               class="case-viewer-btn case-viewer-btn-website"
               data-case-viewer-website
               aria-label="Ver sitio web en vivo">
                <span>Ver sitio web</span>
                <svg viewBox="0 0 10 10" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M1.5 8.5L8.5 1.5M8.5 1.5H3M8.5 1.5v5.5"/>
                </svg>
            </a>
            <a href="{{ route('contact') }}"
               class="case-viewer-btn case-viewer-btn-contact"
               data-case-viewer-contact>
                <span>Quiero una igual</span>
                <svg viewBox="0 0 10 10" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M1.5 8.5L8.5 1.5M8.5 1.5H3M8.5 1.5v5.5"/>
                </svg>
            </a>
            <button class="case-viewer-close" type="button" data-case-viewer-close aria-label="Cerrar visualizador">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-width="2" d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>
        </div>
    </div>
    <div class="case-viewer-stage">
        <img class="case-viewer-image" data-case-viewer-image src="" alt="">
    </div>
</dialog>

<script>
    (() => {
        const initializeCaseStudyViewer = () => {
            const viewer = document.querySelector('[data-case-viewer]');
            if (!viewer || viewer.dataset.initialized === 'true') return;

            viewer.dataset.initialized = 'true';
            const image = viewer.querySelector('[data-case-viewer-image]');

            viewer.querySelector('[data-case-viewer-close]').addEventListener('click', () => viewer.close());
            viewer.addEventListener('click', event => {
                if (event.target === viewer) viewer.close();
            });
            viewer.addEventListener('close', () => {
                image.removeAttribute('src');
                image.alt = '';
            });
        };

        if (!window.__caseStudyViewerClickBound) {
            window.__caseStudyViewerClickBound = true;
            document.addEventListener('click', event => {
                const trigger = event.target.closest('[data-case-preview]');
                if (!trigger) return;

                const viewer = document.querySelector('[data-case-viewer]');
                if (!viewer) return;

                const image = viewer.querySelector('[data-case-viewer-image]');
                image.src = trigger.dataset.image;
                image.alt = trigger.dataset.title;
                viewer.querySelector('[data-case-viewer-title]').textContent = trigger.dataset.title;

                const websiteBtn = viewer.querySelector('[data-case-viewer-website]');
                const websiteUrl = trigger.dataset.url ? trigger.dataset.url.trim() : '';
                if (websiteBtn) {
                    if (websiteUrl) {
                        websiteBtn.href = websiteUrl;
                        websiteBtn.style.display = 'inline-flex';
                    } else {
                        websiteBtn.style.display = 'none';
                    }
                }

                const contactBtn = viewer.querySelector('[data-case-viewer-contact]');
                if (contactBtn) {
                    const baseUrl = "{{ route('contact') }}";
                    contactBtn.href = baseUrl + '?servicio=web&proyecto=' + encodeURIComponent(trigger.dataset.title || '');
                }

                viewer.showModal();
            });
        }

        initializeCaseStudyViewer();
        document.addEventListener('livewire:navigated', initializeCaseStudyViewer);
    })();
</script>
