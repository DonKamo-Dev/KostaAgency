<style>
    .film-viewer {
        width: 100vw;
        max-width: 100vw;
        height: 100vh;
        max-height: 100vh;
        margin: 0;
        padding: 0;
        border: none;
        background: transparent;
        overflow: hidden;
    }
    .film-viewer::backdrop {
        background: rgba(4, 4, 6, 0.94);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }
    .film-viewer[open] {
        display: flex;
        align-items: center;
        justify-content: center;
        animation: filmFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .film-modal-container {
        position: relative;
        display: flex;
        flex-direction: column;
        background: #09090b;
        border: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(244, 63, 94, 0.15);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.25s ease;
    }
    .film-modal-container.is-vertical {
        width: min(92vw, 420px);
        height: min(88vh, 740px);
        aspect-ratio: 9 / 16;
    }
    .film-modal-container.is-horizontal {
        width: min(94vw, 980px);
        max-height: 90vh;
        aspect-ratio: 16 / 9;
    }
    .film-modal-header {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 20;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 16px;
        background: linear-gradient(180deg, rgba(0,0,0,0.85) 0%, transparent 100%);
        pointer-events: auto;
    }
    .film-modal-title {
        font-family: 'Syne', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: #ffffff;
        text-shadow: 0 2px 8px rgba(0,0,0,0.8);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 65%;
    }
    .film-modal-close {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .film-modal-close:hover {
        background: #f43f5e;
        border-color: #f43f5e;
        transform: scale(1.05);
    }
    .film-modal-stage {
        flex: 1;
        width: 100%;
        height: 100%;
        position: relative;
        background: #000000;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .film-modal-video {
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: #000;
    }
    .film-modal-iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    @keyframes filmFadeIn {
        from { opacity: 0; transform: scale(0.96); }
        to { opacity: 1; transform: scale(1); }
    }
    @media (max-width: 640px) {
        .film-modal-container.is-vertical {
            width: 100vw;
            height: 100dvh;
            border-radius: 0;
            border: none;
        }
        .film-modal-container.is-horizontal {
            width: 100vw;
            height: auto;
            border-radius: 0;
            border: none;
        }
    }
</style>

<dialog class="film-viewer" data-film-viewer aria-labelledby="film-viewer-title">
    <div class="film-modal-container is-vertical" data-film-container>
        <div class="film-modal-header">
            <div class="film-modal-title" id="film-viewer-title" data-film-title>Cargando Film...</div>
            <button class="film-modal-close" type="button" data-film-close aria-label="Cerrar reproductor">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="film-modal-stage" data-film-stage>
            <video class="film-modal-video" data-film-player controls preload="auto" playsinline></video>
            <iframe class="film-modal-iframe" data-film-iframe style="display:none;" allow="autoplay; fullscreen" allowfullscreen></iframe>
        </div>
    </div>
</dialog>

<script>
    (() => {
        const initFilmViewer = () => {
            const viewer = document.querySelector('[data-film-viewer]');
            if (!viewer || viewer.dataset.initialized === 'true') return;

            viewer.dataset.initialized = 'true';
            const container = viewer.querySelector('[data-film-container]');
            const video = viewer.querySelector('[data-film-player]');
            const iframe = viewer.querySelector('[data-film-iframe]');
            const titleEl = viewer.querySelector('[data-film-title]');
            const closeBtn = viewer.querySelector('[data-film-close]');

            const closeViewer = () => {
                if (video) {
                    video.pause();
                    video.removeAttribute('src');
                    video.load();
                }
                if (iframe) {
                    iframe.removeAttribute('src');
                    iframe.style.display = 'none';
                }
                viewer.close();
            };

            closeBtn.addEventListener('click', closeViewer);
            viewer.addEventListener('click', (e) => {
                if (e.target === viewer) closeViewer();
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && viewer.open) {
                    closeViewer();
                }
            });
        };

        if (!window.__filmViewerBound) {
            window.__filmViewerBound = true;

            document.addEventListener('click', (event) => {
                const trigger = event.target.closest('[data-film-play]');
                if (!trigger) return;

                event.preventDefault();

                const viewer = document.querySelector('[data-film-viewer]');
                if (!viewer) return;

                const container = viewer.querySelector('[data-film-container]');
                const video = viewer.querySelector('[data-film-player]');
                const iframe = viewer.querySelector('[data-film-iframe]');
                const titleEl = viewer.querySelector('[data-film-title]');

                const videoUrl = trigger.dataset.videoUrl || '';
                const orientation = trigger.dataset.orientation || 'vertical';
                const title = trigger.dataset.title || 'Film';
                const isDirect = trigger.dataset.type === 'direct';
                const embedUrl = trigger.dataset.embedUrl || '';

                titleEl.textContent = title;

                // Adjust container orientation aspect ratio
                container.classList.toggle('is-vertical', orientation === 'vertical');
                container.classList.toggle('is-horizontal', orientation !== 'vertical');

                if (isDirect && videoUrl) {
                    if (iframe) iframe.style.display = 'none';
                    if (video) {
                        video.style.display = 'block';
                        video.src = videoUrl;
                        video.currentTime = 0;
                        video.play().catch(() => {});
                    }
                } else if (embedUrl) {
                    if (video) {
                        video.pause();
                        video.style.display = 'none';
                    }
                    if (iframe) {
                        iframe.style.display = 'block';
                        iframe.src = embedUrl;
                    }
                } else if (videoUrl) {
                    // Fallback to direct video
                    if (iframe) iframe.style.display = 'none';
                    if (video) {
                        video.style.display = 'block';
                        video.src = videoUrl;
                        video.currentTime = 0;
                        video.play().catch(() => {});
                    }
                }

                viewer.showModal();
            });
        }

        initFilmViewer();
        document.addEventListener('livewire:navigated', initFilmViewer);
    })();
</script>
