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
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 0 16px 0 22px;
        border-bottom: 1px solid rgba(255,255,255,.08);
        background: #111114;
    }
    .case-viewer-title { overflow: hidden; font-size: 14px; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
    .case-viewer-close {
        width: 38px;
        height: 38px;
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
        .case-preview-hint { opacity: 1; transform: none; }
    }
</style>

<dialog class="case-viewer" data-case-viewer aria-labelledby="case-viewer-title">
    <div class="case-viewer-bar">
        <div class="case-viewer-title" id="case-viewer-title" data-case-viewer-title></div>
        <button class="case-viewer-close" type="button" data-case-viewer-close aria-label="Cerrar visualizador">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-width="2" d="M6 6l12 12M18 6L6 18"/>
            </svg>
        </button>
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
                viewer.showModal();
            });
        }

        initializeCaseStudyViewer();
        document.addEventListener('livewire:navigated', initializeCaseStudyViewer);
    })();
</script>
