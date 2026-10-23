<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kosta') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('img/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400..900&family=Geist+Mono:wght@400..800&family=Google+Sans+Flex:opsz,wght@6..144,1..1000&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/site.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
        @livewireStyles

        <style>
            /* Livewire Loading States & Cloak */
            [x-cloak] { display: none !important; }
            [wire\:loading], [wire\:loading\.delay], [wire\:loading\.inline-block], [wire\:loading\.inline], [wire\:loading\.block], [wire\:loading\.flex], [wire\:loading\.table], [wire\:loading\.grid], [wire\:loading\.inline-flex] {
                display: none;
            }

            /* ===== Design Tokens - Luxury Monochrome System ===== */
            :root {
                --bg-base: #080808;
                --bg-elevated: #101010;
                --bg-card: rgba(18, 18, 18, 0.85);
                --bg-card-solid: #131313;
                --bg-input: rgba(12, 12, 12, 0.7);

                /* Luxury Monochromatic Palette */
                --red-primary: #FFFFFF;
                --red-bright: #F4F4F5;
                --red-glow: rgba(255, 255, 255, 0.12);
                --red-soft: rgba(255, 255, 255, 0.07);
                --red-dark: #27272A;

                --text-primary: #FAFAFA;
                --text-secondary: rgba(250, 250, 250, 0.75);
                --text-muted: rgba(250, 250, 250, 0.48);
                --text-subtle: rgba(250, 250, 250, 0.32);

                --border-subtle: rgba(255, 255, 255, 0.07);
                --border-default: rgba(255, 255, 255, 0.12);
                --border-red: rgba(255, 255, 255, 0.22);
                --border-red-strong: rgba(255, 255, 255, 0.45);

                --positive: #10B981;
                --negative: #EF4444;
                --warning: #F59E0B;

                --sidebar-width: 280px;
                --bottom-nav-height: 72px;
            }

            * {
                font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                box-sizing: border-box;
            }

            body {
                margin: 0;
                background: var(--bg-base);
                color: var(--text-primary);
                min-height: 100vh;
                overflow-x: hidden;
                font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            }

            h1, h2, h3, h4, h5, h6, .font-display {
                font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
            }

            /* ===== Ambient Glow ===== */
            .ambient-glow {
                position: fixed;
                pointer-events: none;
                z-index: 0;
            }

            .ambient-glow-tl {
                top: -200px;
                right: -200px;
                width: 450px;
                height: 450px;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
                opacity: 0.25;
            }

            .ambient-glow-br {
                bottom: -200px;
                left: -100px;
                width: 450px;
                height: 450px;
                background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
                opacity: 0.2;
            }

            /* ===== App Shell Layout (Sidebar Left) ===== */
            .app-shell {
                display: grid;
                grid-template-columns: var(--sidebar-width) minmax(0, 1fr);
                min-height: 100vh;
                position: relative;
                z-index: 1;
                max-width: 100vw;
            }

            /* ===== Mobile-First App Navigation System ===== */
            @media (min-width: 1024px) {
                .mobile-topbar,
                .app-bottom-nav,
                .mobile-action-sheet,
                .mobile-drawer-sheet,
                .mobile-sheet-backdrop {
                    display: none !important;
                }
            }

            @media (max-width: 1023px) {
                html, body {
                    max-width: 100vw !important;
                    overflow-x: hidden !important;
                }

                .app-shell {
                    display: block !important;
                    grid-template-columns: minmax(0, 1fr) !important;
                    padding-top: 60px !important;
                    padding-bottom: calc(88px + env(safe-area-inset-bottom, 12px)) !important;
                    min-height: 100vh;
                    width: 100% !important;
                    max-width: 100vw !important;
                    min-width: 0 !important;
                    overflow-x: hidden !important;
                    box-sizing: border-box !important;
                }

                .sidebar {
                    display: none !important;
                }

                .main-content {
                    padding: 16px 14px !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    min-width: 0 !important;
                    overflow-x: hidden !important;
                    box-sizing: border-box !important;
                }

                /* Fixed Mobile Top Bar */
                .mobile-topbar {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 60px;
                    background: rgba(12, 12, 12, 0.92);
                    backdrop-filter: blur(24px) saturate(180%);
                    -webkit-backdrop-filter: blur(24px) saturate(180%);
                    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 0 16px;
                    z-index: 80;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
                }

                .mobile-topbar-brand {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    text-decoration: none;
                }

                .mobile-topbar-brand img {
                    height: 30px;
                    width: auto;
                    object-fit: contain;
                }

                .mobile-topbar-actions {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                .mobile-topbar-btn {
                    width: 38px;
                    height: 38px;
                    border-radius: 10px;
                    background: rgba(255, 255, 255, 0.06);
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #FAFAFA;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    text-decoration: none;
                    position: relative;
                }

                .mobile-topbar-btn:active {
                    transform: scale(0.92);
                    background: rgba(255, 255, 255, 0.12);
                }

                .mobile-badge-dot {
                    position: absolute;
                    top: 6px;
                    right: 6px;
                    width: 8px;
                    height: 8px;
                    border-radius: 50%;
                    background: #FFFFFF;
                    box-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
                }

                /* Floating Pill Mobile Bottom App Nav */
                .app-bottom-nav {
                    position: fixed;
                    bottom: calc(6px + env(safe-area-inset-bottom, 0px));
                    left: 0;
                    right: 0;
                    margin: 0 auto;
                    transform: none;
                    width: calc(100vw - 24px);
                    max-width: 390px;
                    height: 58px;
                    padding: 5px 6px;
                    border-radius: 9999px;
                    background: rgba(18, 18, 22, 0.58);
                    backdrop-filter: blur(24px) saturate(190%);
                    -webkit-backdrop-filter: blur(24px) saturate(190%);
                    border: 1px solid rgba(255, 255, 255, 0.14);
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 2px;
                    z-index: 90;
                    box-sizing: border-box;
                    box-shadow:
                        0 12px 32px rgba(0, 0, 0, 0.55),
                        0 2px 8px rgba(0, 0, 0, 0.35),
                        inset 0 1px 0 rgba(255, 255, 255, 0.14);
                }

                .bottom-nav-item {
                    flex: 1 1 0;
                    min-width: 0;
                    height: 46px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    gap: 3px;
                    color: rgba(250, 250, 250, 0.6);
                    text-decoration: none;
                    padding: 4px 2px;
                    border-radius: 9999px;
                    font-size: 9.5px;
                    line-height: 1.05;
                    font-weight: 600;
                    letter-spacing: -0.01em;
                    white-space: nowrap;
                    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
                    -webkit-tap-highlight-color: transparent;
                    position: relative;
                    background: transparent;
                    border: none;
                    cursor: pointer;
                }

                .bottom-nav-item svg {
                    width: 19px;
                    height: 19px;
                    flex-shrink: 0;
                    transition: transform 0.2s ease, color 0.2s ease;
                }

                .bottom-nav-item:active {
                    transform: scale(0.92);
                }

                .bottom-nav-item.active {
                    color: #FFFFFF;
                    font-weight: 700;
                    background: rgba(255, 255, 255, 0.12);
                    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.14);
                }

                .bottom-nav-item.active svg {
                    color: #FFFFFF;
                    filter: drop-shadow(0 2px 6px rgba(255, 255, 255, 0.35));
                }

                /* Central FAB Button inside Pill */
                .bottom-nav-fab-wrap {
                    flex: 0 0 auto;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    position: relative;
                    padding: 0 3px;
                }

                .bottom-nav-fab {
                    width: 44px;
                    height: 44px;
                    border-radius: 9999px;
                    background: #FFFFFF;
                    color: #080808;
                    border: 1px solid rgba(255, 255, 255, 0.9);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-top: 0;
                    box-shadow:
                        0 4px 16px rgba(255, 255, 255, 0.25),
                        0 2px 8px rgba(0, 0, 0, 0.4);
                    cursor: pointer;
                    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.2s ease;
                    -webkit-tap-highlight-color: transparent;
                    z-index: 95;
                }

                .bottom-nav-fab:active {
                    transform: scale(0.9);
                    background: #E4E4E7;
                }

                .bottom-nav-fab svg {
                    width: 21px;
                    height: 21px;
                    transition: transform 0.25s ease;
                }

                .bottom-nav-fab.is-open svg {
                    transform: rotate(45deg);
                }

                .bottom-nav-fab-label {
                    display: none;
                }

                /* Backdrop for sheets */
                .mobile-sheet-backdrop {
                    position: fixed;
                    inset: 0;
                    background: rgba(0, 0, 0, 0.75);
                    backdrop-filter: blur(10px);
                    -webkit-backdrop-filter: blur(10px);
                    z-index: 110;
                    opacity: 0;
                    pointer-events: none;
                    transition: opacity 0.25s ease;
                }

                .mobile-sheet-backdrop.active {
                    opacity: 1;
                    pointer-events: auto;
                }

                /* Bottom Sheets (Quick Action + Drawer) */
                .mobile-action-sheet,
                .mobile-drawer-sheet {
                    position: fixed;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: #111111;
                    border-top: 1px solid rgba(255, 255, 255, 0.15);
                    border-radius: 24px 24px 0 0;
                    padding: 16px 20px calc(24px + env(safe-area-inset-bottom, 16px));
                    z-index: 120;
                    transform: translateY(105%);
                    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                    max-height: 85vh;
                    overflow-y: auto;
                    box-shadow: 0 -20px 50px rgba(0, 0, 0, 0.8);
                }

                .mobile-action-sheet.active,
                .mobile-drawer-sheet.active {
                    transform: translateY(0);
                }

                .sheet-handle {
                    width: 38px;
                    height: 4px;
                    background: rgba(255, 255, 255, 0.25);
                    border-radius: 999px;
                    margin: 0 auto 16px;
                }

                .sheet-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 20px;
                    padding-bottom: 12px;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
                }

                .sheet-title {
                    font-size: 17px;
                    font-weight: 700;
                    color: #FAFAFA;
                    letter-spacing: -0.02em;
                }

                .sheet-close-btn {
                    width: 30px;
                    height: 30px;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.08);
                    border: none;
                    color: rgba(250, 250, 250, 0.7);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                }

                /* Grid in Quick Action Sheet */
                .sheet-grid {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 12px;
                }

                .sheet-grid-item {
                    background: rgba(255, 255, 255, 0.04);
                    border: 1px solid rgba(255, 255, 255, 0.08);
                    border-radius: 16px;
                    padding: 16px 14px;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    text-decoration: none;
                    color: #FAFAFA;
                    transition: all 0.2s ease;
                    -webkit-tap-highlight-color: transparent;
                }

                .sheet-grid-item:active {
                    transform: scale(0.96);
                    background: rgba(255, 255, 255, 0.09);
                    border-color: rgba(255, 255, 255, 0.2);
                }

                .sheet-grid-icon {
                    width: 38px;
                    height: 38px;
                    border-radius: 10px;
                    background: rgba(255, 255, 255, 0.08);
                    border: 1px solid rgba(255, 255, 255, 0.12);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #FFFFFF;
                    flex-shrink: 0;
                }

                .sheet-grid-label {
                    font-size: 13px;
                    font-weight: 600;
                    line-height: 1.25;
                }

                .sheet-grid-desc {
                    font-size: 11px;
                    color: rgba(250, 250, 250, 0.45);
                    margin-top: 2px;
                }

                /* Full Drawer Menu Items */
                .drawer-nav-group {
                    margin-bottom: 20px;
                }

                .drawer-nav-heading {
                    font-size: 11px;
                    text-transform: uppercase;
                    letter-spacing: 0.12em;
                    color: rgba(250, 250, 250, 0.4);
                    font-weight: 700;
                    margin-bottom: 8px;
                    padding-left: 4px;
                }

                .drawer-nav-item {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    padding: 12px 14px;
                    border-radius: 12px;
                    color: rgba(250, 250, 250, 0.75);
                    text-decoration: none;
                    font-size: 14px;
                    font-weight: 500;
                    transition: all 0.2s ease;
                    margin-bottom: 4px;
                    background: transparent;
                    border: 1px solid transparent;
                }

                .drawer-nav-item:active {
                    background: rgba(255, 255, 255, 0.06);
                    transform: scale(0.98);
                }

                .drawer-nav-item.active {
                    background: rgba(255, 255, 255, 0.08);
                    color: #FFFFFF;
                    font-weight: 600;
                    border-color: rgba(255, 255, 255, 0.12);
                }

                .drawer-nav-item svg {
                    width: 20px;
                    height: 20px;
                    color: rgba(250, 250, 250, 0.6);
                    flex-shrink: 0;
                }

                .drawer-nav-item.active svg {
                    color: #FFFFFF;
                }

                .drawer-nav-badge {
                    margin-left: auto;
                    padding: 2px 8px;
                    border-radius: 999px;
                    font-size: 11px;
                    font-weight: 700;
                    background: rgba(255, 255, 255, 0.12);
                    color: #FFFFFF;
                }
            }

            /* ===== Sidebar (Desktop - LEFT) ===== */
            .sidebar {
                position: sticky;
                top: 0;
                left: 0;
                width: var(--sidebar-width);
                height: 100vh;
                background: #0F0F0F;
                border-right: 1px solid var(--border-subtle);
                padding: 28px 16px;
                display: flex;
                flex-direction: column;
                z-index: 50;
                overflow: hidden;
                box-shadow: -1px 0 0 rgba(255, 255, 255, 0.05);
            }

            /* Decorative accent line on right edge */
            .sidebar::before {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                width: 1px;
                height: 200px;
                background: linear-gradient(180deg, transparent, rgba(255, 255, 255, 0.25), transparent);
                opacity: 0.6;
            }

            @media (max-width: 1023px) {
                .sidebar { display: none; }
            }

            .sidebar-nav {
                flex: 1;
                min-height: 0;
                overflow-y: auto;
                overflow-x: hidden;
                padding: 0 2px 22px;
            }

            .sidebar-nav::-webkit-scrollbar {
                width: 4px;
            }

            .sidebar-nav::-webkit-scrollbar-thumb {
                background: rgba(245, 245, 245, 0.12);
                border-radius: 999px;
            }

            .sidebar-footer {
                flex-shrink: 0;
                padding: 16px 4px 0;
                margin-top: 8px;
                border-top: 1px solid var(--border-subtle);
                background: linear-gradient(180deg, rgba(15, 15, 15, 0), #0F0F0F 18px);
            }

            .sidebar-brand {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 8px 12px 24px;
                border-bottom: 1px solid var(--border-subtle);
                margin-bottom: 20px;
                position: relative;
            }

            .sidebar-brand-logo {
                width: 42px;
                height: 42px;
                background: linear-gradient(135deg, #18181B 0%, #27272A 50%, #3F3F46 100%);
                border: 1px solid rgba(255, 255, 255, 0.15);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow:
                    0 4px 20px rgba(0, 0, 0, 0.6),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
                position: relative;
                overflow: hidden;
            }

            .sidebar-brand-logo::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, transparent 0%, rgba(255, 255, 255, 0.15) 50%, transparent 100%);
                opacity: 0;
                transition: opacity 0.4s ease;
            }

            .sidebar-brand:hover .sidebar-brand-logo::before {
                opacity: 1;
            }

            .sidebar-brand-name {
                font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif;
                font-size: 19px;
                font-weight: 700;
                color: var(--text-primary);
                letter-spacing: -0.02em;
            }

            .sidebar-brand-tag {
                font-size: 10px;
                color: rgba(255, 255, 255, 0.5);
                margin-top: 2px;
                text-transform: uppercase;
                letter-spacing: 0.14em;
                font-weight: 600;
            }

            .sidebar-section-label {
                font-size: 10px;
                color: var(--text-subtle);
                text-transform: uppercase;
                letter-spacing: 0.15em;
                font-weight: 700;
                padding: 16px 16px 8px;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .sidebar-section-label::after {
                content: '';
                flex: 1;
                height: 1px;
                background: linear-gradient(to right, var(--border-subtle), transparent);
            }

            .nav-link {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 11px 14px;
                border-radius: 10px;
                color: var(--text-secondary);
                text-decoration: none;
                font-size: 13.5px;
                font-weight: 500;
                transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
                position: relative;
                margin: 1px 0;
                white-space: nowrap;
                overflow: hidden;
            }

            .nav-link::before {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: 10px;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05));
                opacity: 0;
                transition: opacity 0.25s ease;
                pointer-events: none;
            }

            .nav-link:hover {
                color: var(--text-primary);
                transform: translateX(2px);
            }

            .nav-link:hover::before {
                opacity: 1;
            }

            .nav-link:hover svg {
                color: #FFFFFF;
                filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.4));
            }

            .nav-link.active {
                background: linear-gradient(90deg, rgba(255, 255, 255, 0.04) 0%, rgba(255, 255, 255, 0.10) 100%);
                color: #FFFFFF;
                border: 1px solid rgba(255, 255, 255, 0.12);
                font-weight: 600;
            }

            .nav-link.active::before {
                opacity: 0;
            }

            .nav-link.active::after {
                content: '';
                position: absolute;
                left: -16px;
                top: 50%;
                transform: translateY(-50%);
                width: 3px;
                height: 28px;
                background: #FFFFFF;
                border-radius: 0 3px 3px 0;
                box-shadow: 0 0 12px rgba(255, 255, 255, 0.5);
            }

            .nav-link.active svg {
                color: var(--red-primary);
            }

            .nav-link svg {
                width: 18px;
                height: 18px;
                flex-shrink: 0;
                transition: color 0.25s cubic-bezier(0.16, 1, 0.3, 1), filter 0.25s ease;
                color: var(--text-muted);
            }

            /* Sub-links inside collapsible nav group */
            .nav-group-btn {
                width: 100%;
                text-align: left;
                background: none;
                border: none;
                cursor: pointer;
                font-family: 'Inter', sans-serif;
            }
            .nav-sublink {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 7px 12px 7px 22px;
                border-radius: 8px;
                color: var(--text-muted);
                text-decoration: none;
                font-size: 12.5px;
                font-weight: 500;
                transition: all 0.2s ease;
                margin: 2px 0;
                position: relative;
                list-style: none !important;
            }
            .nav-sublink::before,
            .nav-sublink::after,
            .nav-sublink:hover::before,
            .nav-sublink:hover::after,
            .nav-sublink.active::before,
            .nav-sublink.active::after {
                content: none !important;
                display: none !important;
                width: 0 !important;
                height: 0 !important;
                opacity: 0 !important;
                visibility: hidden !important;
                pointer-events: none !important;
            }
            .nav-sublink svg {
                width: 14px;
                height: 14px;
                flex-shrink: 0;
                opacity: 0.65;
                transition: all 0.2s ease;
            }
            .nav-sublink:hover {
                color: var(--text-primary);
                background: rgba(255,255,255,0.05);
            }
            .nav-sublink:hover svg {
                opacity: 1;
                transform: scale(1.1);
            }
            .nav-sublink.active {
                color: var(--red-primary);
                background: rgba(229, 9, 20, 0.08);
                font-weight: 600;
            }
            .nav-sublink.active svg {
                color: var(--red-primary);
                opacity: 1;
            }
            .nav-chevron { transition: transform 0.25s ease; }
            .nav-chevron.open { transform: rotate(180deg); }

            /* Badge for nav items (optional, for future use) */
            .nav-link-badge {
                margin-left: auto;
                padding: 2px 8px;
                background: var(--red-soft);
                color: var(--red-primary);
                border-radius: 999px;
                font-size: 10px;
                font-weight: 700;
            }

            .sidebar-user {
                width: 100%;
                padding: 12px;
                background:
                    linear-gradient(135deg, rgba(22, 22, 22, 0.94), rgba(10, 10, 10, 0.88)),
                    rgba(17, 17, 17, 0.92);
                border: 1px solid rgba(245, 245, 245, 0.08);
                border-radius: 16px;
                display: flex;
                align-items: center;
                gap: 10px;
                position: relative;
                overflow: hidden;
                z-index: 2;
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                box-shadow:
                    0 10px 24px rgba(0, 0, 0, 0.28),
                    inset 0 1px 0 rgba(255, 255, 255, 0.04);
                transition: border-color 0.25s ease, transform 0.25s ease;
            }

            .sidebar-user::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 1px;
                background: linear-gradient(90deg, transparent, rgba(230, 57, 70, 0.3), transparent);
            }

            .sidebar-user:hover {
                border-color: rgba(255, 255, 255, 0.2);
                transform: translateY(-1px);
            }

            .sidebar-user-avatar {
                width: 38px;
                height: 38px;
                border-radius: 50%;
                background: linear-gradient(135deg, #27272A, #09090B);
                border: 1px solid rgba(255, 255, 255, 0.2);
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
                color: #FFFFFF;
                font-size: 14px;
                flex-shrink: 0;
            }

            .sidebar-user-info {
                flex: 1;
                min-width: 0;
                line-height: 1.2;
            }

            .sidebar-user-name {
                font-size: 13px;
                font-weight: 600;
                color: var(--text-primary);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .sidebar-user-email {
                font-size: 11px;
                color: var(--text-muted);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                margin-top: 3px;
            }

            .sidebar-user-form {
                flex-shrink: 0;
                display: flex;
            }

            .sidebar-logout {
                width: 34px;
                height: 34px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(245, 245, 245, 0.07);
                color: var(--text-muted);
                cursor: pointer;
                padding: 0;
                border-radius: 10px;
                transition: all 0.2s ease;
            }

            .sidebar-logout:hover {
                background: rgba(230, 57, 70, 0.14);
                border-color: rgba(230, 57, 70, 0.28);
                color: var(--red-primary);
            }

            /* ===== Main Content ===== */
            .main-content {
                padding: 32px 40px;
                max-width: 100%;
                min-width: 0;
                overflow-x: hidden;
                box-sizing: border-box;
            }

            @media (max-width: 1023px) {
                .main-content {
                    padding: 16px 14px;
                }
            }

            /* ===== Card Component ===== */
            .card {
                background: rgba(20, 20, 20, 0.95);
                border: 1px solid var(--border-subtle);
                border-radius: 16px;
                padding: 24px;
                transition: all 0.3s ease;
                min-width: 0;
                max-width: 100%;
                box-sizing: border-box;
            }

            .card:hover {
                border-color: var(--border-red);
            }

            .card-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 20px;
                flex-wrap: wrap;
                gap: 10px;
            }

            .card-title {
                font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif;
                font-size: 16px;
                font-weight: 700;
                color: var(--text-primary);
            }

            /* ===== Buttons ===== */
            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 10px 18px;
                border-radius: 10px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.25s ease;
                text-decoration: none;
                border: none;
            }

            .btn-primary {
                background: #FFFFFF;
                color: #0A0A0A;
                font-weight: 700;
                box-shadow: 0 4px 16px rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.9);
            }

            .btn-primary:hover {
                background: #E4E4E7;
                color: #000000;
                transform: translateY(-1px);
                box-shadow: 0 6px 24px rgba(255, 255, 255, 0.18);
            }

            .btn-secondary {
                background: rgba(255, 255, 255, 0.05);
                color: var(--text-primary);
                border: 1px solid var(--border-default);
            }

            .btn-secondary:hover {
                border-color: rgba(255, 255, 255, 0.3);
                background: rgba(255, 255, 255, 0.1);
                color: #FFFFFF;
            }

            /* ===== Scrollbar ===== */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }

            ::-webkit-scrollbar-track {
                background: transparent;
            }

            ::-webkit-scrollbar-thumb {
                background: rgba(245, 245, 245, 0.1);
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: var(--border-red);
            }

            /* ===== CRUD PAGE STYLES (shared) ===== */
            .crud-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 32px;
                flex-wrap: wrap;
                gap: 16px;
            }

            .crud-title {
                font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif;
                font-size: 32px;
                font-weight: 800;
                color: var(--text-primary);
                line-height: 1.1;
                letter-spacing: -0.025em;
                margin: 0;
            }

            .crud-subtitle {
                color: var(--text-muted);
                font-size: 14px;
                margin-top: 6px;
            }

            .crud-toolbar {
                display: flex;
                gap: 16px;
                margin-bottom: 24px;
                flex-wrap: wrap;
                align-items: center;
            }

            .crud-search-wrapper {
                position: relative;
                flex: 1;
                min-width: 240px;
                max-width: 480px;
            }

            .crud-search-input {
                width: 100%;
                padding: 11px 16px 11px 42px;
                background: rgba(20, 20, 20, 0.7);
                border: 1px solid var(--border-default);
                border-radius: 10px;
                color: var(--text-primary);
                font-size: 14px;
                outline: none;
                transition: border-color 0.15s ease, box-shadow 0.15s ease;
            }

            .crud-search-input::placeholder { color: var(--text-subtle); }

            .crud-search-input:focus {
                border-color: var(--red-primary);
                box-shadow: 0 0 0 3px var(--red-soft);
            }

            .crud-search-icon {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                width: 16px;
                height: 16px;
                color: var(--text-muted);
                pointer-events: none;
            }

            /* CRUD Table - Fully Responsive with Smooth Touch Scroll */
            .crud-table-wrapper {
                background: rgba(20, 20, 20, 0.7);
                border: 1px solid var(--border-subtle);
                border-radius: 16px;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                width: 100%;
                max-width: 100%;
                position: relative;
            }

            .crud-table {
                width: 100%;
                min-width: 680px;
                border-collapse: collapse;
            }

            .doc-item-badge {
                display: inline-flex;
                align-items: center;
                padding: 3px 10px;
                background: rgba(255, 255, 255, 0.08);
                border: 1px solid rgba(255, 255, 255, 0.12);
                border-radius: 999px;
                font-size: 11px;
                font-weight: 700;
                color: #FAFAFA;
                letter-spacing: 0.04em;
            }

            .doc-form-actions {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                margin-top: 24px;
                padding-top: 20px;
                border-top: 1px solid var(--border-subtle);
                flex-wrap: wrap;
            }

            /* Document Items Responsive Classes */
            .doc-items-box {
                border: 1px solid var(--border-subtle);
                border-radius: 12px;
                overflow: visible;
                margin-bottom: 18px;
            }

            .doc-items-table-header {
                display: grid;
                grid-template-columns: 28px 1fr 90px 130px 110px 32px;
                gap: 8px;
                background: rgba(255, 255, 255, 0.025);
                border-bottom: 1px solid var(--border-subtle);
                padding: 10px 16px;
                border-radius: 12px 12px 0 0;
            }

            .doc-items-table-header span {
                font-size: 11px;
                color: var(--text-muted);
                text-transform: uppercase;
                letter-spacing: .08em;
                font-weight: 700;
            }

            .doc-items-list {
                padding: 6px 0;
            }

            .doc-item-row {
                display: grid;
                grid-template-columns: 28px 1fr 90px 130px 110px 32px;
                gap: 8px;
                align-items: start;
                padding: 8px 16px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            }

            .doc-item-row:last-child {
                border-bottom: none;
            }

            .doc-item-index {
                color: var(--text-subtle);
                font-size: 12px;
                font-weight: 600;
                text-align: center;
                padding-top: 11px;
            }

            .doc-item-inputs-mobile {
                display: contents;
            }

            .doc-item-cell-subtotal {
                text-align: right;
                padding-top: 11px;
            }

            .doc-item-cell-action {
                padding-top: 5px;
                text-align: right;
            }

            .doc-items-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 16px;
                border-top: 1px solid var(--border-subtle);
                background: rgba(0,0,0,.12);
                border-radius: 0 0 12px 12px;
                flex-wrap: wrap;
                gap: 12px;
            }

            .doc-page-card {
                background: #161616;
                border: 1px solid var(--border-default);
                border-radius: 16px;
                padding: 24px;
            }

            .expense-month-summary {
                transition: all 0.2s ease;
            }

            /* Responsive System Overrides for Mobile (<= 768px) */
            @media (max-width: 768px) {
                .card {
                    padding: 16px 14px !important;
                    border-radius: 14px !important;
                    min-width: 0 !important;
                    max-width: 100% !important;
                    overflow: hidden !important;
                }
                .card-header {
                    margin-bottom: 14px !important;
                    gap: 8px !important;
                }
                .crud-header {
                    flex-direction: column !important;
                    align-items: stretch !important;
                    gap: 12px !important;
                    margin-bottom: 20px !important;
                }
                .crud-title {
                    font-size: 24px !important;
                }
                .crud-header .btn {
                    width: 100% !important;
                    justify-content: center !important;
                }
                .crud-header > div:last-child {
                    width: 100% !important;
                    flex-wrap: wrap !important;
                }
                .crud-toolbar {
                    flex-direction: column !important;
                    align-items: stretch !important;
                    gap: 10px !important;
                    margin-bottom: 16px !important;
                }
                .crud-search-wrapper {
                    max-width: 100% !important;
                    width: 100% !important;
                    min-width: 0 !important;
                }
                .crud-search-input {
                    font-size: 16px !important;
                    min-height: 46px !important;
                }
                .crud-table-wrapper {
                    border-radius: 14px !important;
                }
                .crud-table th {
                    padding: 12px 14px !important;
                    font-size: 10px !important;
                }
                .crud-table td {
                    padding: 12px 14px !important;
                    font-size: 13px !important;
                }
                [id$="Toast"] {
                    bottom: calc(76px + env(safe-area-inset-bottom, 0px)) !important;
                    left: 14px !important;
                    right: 14px !important;
                    max-width: calc(100vw - 28px) !important;
                    box-sizing: border-box !important;
                }

                /* Mobile Form Action Buttons */
                .doc-form-actions {
                    flex-direction: column-reverse !important;
                    align-items: stretch !important;
                    gap: 8px !important;
                }
                .doc-form-actions button,
                .doc-form-actions a,
                .doc-form-actions .btn-modal-primary,
                .doc-form-actions .btn-modal-secondary {
                    width: 100% !important;
                    min-height: 48px !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    text-align: center !important;
                    font-size: 15px !important;
                }

                /* Mobile Document Items: Card Mode */
                .doc-items-table-header {
                    display: none !important;
                }
                .doc-item-row {
                    display: flex !important;
                    flex-direction: column !important;
                    gap: 10px !important;
                    padding: 14px !important;
                    background: rgba(255, 255, 255, 0.02) !important;
                    border: 1px solid var(--border-subtle) !important;
                    border-radius: 12px !important;
                    margin: 8px !important;
                }
                .doc-item-row-header-mobile {
                    display: flex !important;
                    justify-content: space-between !important;
                    align-items: center !important;
                    width: 100% !important;
                }
                .doc-item-inputs-mobile {
                    display: grid !important;
                    grid-template-columns: 1fr 1.3fr !important;
                    gap: 10px !important;
                    width: 100% !important;
                }
                .doc-item-inputs-mobile label {
                    display: block !important;
                }
                .doc-item-index,
                .doc-item-cell-action {
                    display: none !important;
                }
                .doc-item-cell-subtotal {
                    display: flex !important;
                    justify-content: space-between !important;
                    align-items: center !important;
                    padding-top: 8px !important;
                    border-top: 1px dashed var(--border-subtle) !important;
                    width: 100% !important;
                }
                .doc-item-cell-subtotal::before {
                    content: 'Subtotal';
                    font-size: 11px;
                    color: var(--text-muted);
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: .06em;
                }
                .doc-items-footer {
                    flex-direction: column-reverse !important;
                    align-items: stretch !important;
                    padding: 16px 14px !important;
                }
                .doc-items-footer button {
                    width: 100% !important;
                    justify-content: center !important;
                    min-height: 44px !important;
                }
                .doc-items-footer .doc-total-wrap {
                    display: flex !important;
                    justify-content: space-between !important;
                    align-items: baseline !important;
                    width: 100% !important;
                }
                .doc-page-card {
                    padding: 16px 14px !important;
                    border-radius: 14px !important;
                }

                .expense-month-summary {
                    padding: 16px 18px !important;
                    flex-direction: column !important;
                    align-items: flex-start !important;
                    gap: 8px !important;
                }

                /* Modals on Mobile: Bottom-Sheet Experience */
                .modal-backdrop {
                    padding: 0 !important;
                    align-items: flex-end !important;
                }
                .modal-card {
                    width: 100% !important;
                    max-width: 100% !important;
                    max-height: 88vh !important;
                    max-height: 88dvh !important;
                    border-radius: 22px 22px 0 0 !important;
                    margin: 0 !important;
                }
                .modal-header {
                    padding: 16px 18px !important;
                }
                .modal-body {
                    padding: 16px 18px !important;
                }
                .modal-footer {
                    padding: 12px 18px !important;
                    flex-direction: column-reverse !important;
                    gap: 8px !important;
                }
                .modal-footer .btn-modal-primary,
                .modal-footer .btn-modal-secondary {
                    width: 100% !important;
                    min-height: 48px !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    font-size: 15px !important;
                }
                .form-input,
                .form-textarea,
                .form-select {
                    font-size: 16px !important; /* Prevents iOS auto-zoom */
                    min-height: 46px !important;
                    padding: 12px 14px !important;
                }
            }

            .crud-table thead {
                background: rgba(255, 255, 255, 0.02);
                border-bottom: 1px solid var(--border-subtle);
            }

            .crud-table th {
                padding: 14px 20px;
                text-align: left;
                font-size: 11px;
                font-weight: 700;
                color: var(--text-muted);
                text-transform: uppercase;
                letter-spacing: 0.1em;
                white-space: nowrap;
            }

            .crud-table tbody tr {
                border-bottom: 1px solid var(--border-subtle);
                transition: background 0.15s ease;
            }

            .crud-table tbody tr:last-child { border-bottom: none; }

            .crud-table tbody tr:hover {
                background: rgba(230, 57, 70, 0.04);
            }

            .crud-table td {
                padding: 14px 20px;
                font-size: 14px;
                color: var(--text-primary);
            }

            .crud-table td.muted { color: var(--text-muted); }

            .crud-row-name {
                font-weight: 600;
                color: var(--text-primary);
            }

            .crud-row-meta {
                font-size: 12px;
                color: var(--text-muted);
                margin-top: 2px;
            }

            /* Action buttons */
            .action-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 32px;
                height: 32px;
                border-radius: 8px;
                border: 1px solid transparent;
                background: transparent;
                color: var(--text-muted);
                cursor: pointer;
                transition: all 0.2s ease;
                margin-left: 4px;
            }

            .action-btn:hover {
                background: rgba(255, 255, 255, 0.05);
                color: var(--text-primary);
            }

            .action-btn.danger:hover {
                background: var(--red-soft);
                color: var(--red-primary);
                border-color: var(--border-red);
            }

            .action-btn svg {
                width: 16px;
                height: 16px;
            }

            /* Empty State */
            .empty-state-cell {
                padding: 64px 20px !important;
                text-align: center;
            }

            .empty-state-icon {
                width: 64px;
                height: 64px;
                margin: 0 auto 16px;
                background: var(--red-soft);
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--red-primary);
            }

            .empty-state-title {
                font-size: 16px;
                font-weight: 600;
                color: var(--text-primary);
                margin-bottom: 6px;
            }

            .empty-state-desc {
                font-size: 14px;
                color: var(--text-muted);
            }

            /* Pagination */
            .crud-pagination {
                margin-top: 20px;
            }

            .crud-pagination nav {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 6px;
                flex-wrap: wrap;
            }

            .crud-pagination a,
            .crud-pagination span {
                padding: 8px 14px !important;
                background: var(--bg-card) !important;
                border: 1px solid var(--border-subtle) !important;
                border-radius: 8px !important;
                color: var(--text-secondary) !important;
                font-size: 13px !important;
                font-weight: 500 !important;
                text-decoration: none !important;
                transition: all 0.2s ease !important;
            }

            .crud-pagination a:hover {
                border-color: var(--border-red) !important;
                color: var(--red-primary) !important;
            }

            .crud-pagination span[aria-current="page"] {
                background: var(--red-primary) !important;
                border-color: var(--red-primary) !important;
                color: white !important;
            }

            /* ===== MODAL ===== */
            .modal-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.85);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 100;
                padding: 16px;
                animation: modalFade 0.15s ease;
            }

            @keyframes modalFade {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            @keyframes modalSlideUp {
                from { opacity: 0; transform: translateY(20px) scale(0.96); }
                to { opacity: 1; transform: translateY(0) scale(1); }
            }

            .modal-card {
                background: linear-gradient(180deg, #161616 0%, #0F0F0F 100%);
                border: 1px solid var(--border-default);
                border-radius: 20px;
                width: 100%;
                max-width: 540px;
                max-height: 90vh;
                overflow-y: auto;
                box-shadow:
                    0 24px 80px rgba(0, 0, 0, 0.6),
                    0 0 0 1px rgba(230, 57, 70, 0.1);
                animation: modalSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .modal-card.modal-lg { max-width: 720px; }
            .modal-card.modal-xl { max-width: 960px; }

            .modal-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 22px 28px;
                border-bottom: 1px solid var(--border-subtle);
                position: sticky;
                top: 0;
                background: linear-gradient(180deg, #161616 0%, #161616 100%);
                z-index: 1;
            }

            .modal-title {
                font-family: 'Google Sans Flex', -apple-system, BlinkMacSystemFont, sans-serif;
                font-size: 20px;
                font-weight: 700;
                color: var(--text-primary);
                letter-spacing: -0.01em;
                margin: 0;
            }

            .modal-close {
                background: transparent;
                border: none;
                color: var(--text-muted);
                cursor: pointer;
                padding: 8px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
            }

            .modal-close:hover {
                background: var(--red-soft);
                color: var(--red-primary);
            }

            .modal-body {
                padding: 24px 28px;
            }

            .modal-footer {
                padding: 18px 28px;
                border-top: 1px solid var(--border-subtle);
                display: flex;
                gap: 12px;
                justify-content: flex-end;
                position: sticky;
                bottom: 0;
                background: #0F0F0F;
            }

            /* Form inputs */
            .form-group { margin-bottom: 18px; }

            .form-label {
                display: block;
                font-size: 13px;
                font-weight: 600;
                color: var(--text-secondary);
                margin-bottom: 6px;
            }

            .form-label .required {
                color: var(--red-primary);
                margin-left: 2px;
            }

            .form-input,
            .form-textarea,
            .form-select {
                width: 100%;
                padding: 11px 14px;
                background: rgba(10, 10, 10, 0.6);
                border: 1px solid var(--border-default);
                border-radius: 10px;
                color: var(--text-primary);
                font-size: 14px;
                font-family: 'Inter', sans-serif;
                outline: none;
                transition: all 0.2s ease;
            }

            .form-input::placeholder,
            .form-textarea::placeholder { color: var(--text-subtle); }

            /* ===== Tipografía Numérica Moderna (Geist Mono / Geist) ===== */
            .font-number,
            .tabular-nums,
            .hero-kpi-value,
            .stat-card-value,
            .activity-amount,
            .numeric-input,
            .money-input,
            .metric-value,
            .table-number {
                font-family: 'Geist Mono', 'Geist', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
                font-feature-settings: 'tnum' 1, 'zero' 1;
                font-variant-numeric: tabular-nums;
                letter-spacing: -0.025em;
            }

            .numeric-input {
                appearance: textfield;
                -moz-appearance: textfield;
                font-variant-numeric: tabular-nums;
            }

            .numeric-input::-webkit-outer-spin-button,
            .numeric-input::-webkit-inner-spin-button {
                appearance: none;
                -webkit-appearance: none;
                margin: 0;
            }

            .money-input {
                font-variant-numeric: tabular-nums;
                letter-spacing: -0.02em;
            }

            .form-input:focus,
            .form-textarea:focus,
            .form-select:focus {
                border-color: var(--red-primary);
                box-shadow: 0 0 0 3px var(--red-soft);
                background: rgba(10, 10, 10, 0.8);
            }

            .form-textarea {
                resize: vertical;
                min-height: 80px;
            }

            .form-error {
                color: var(--red-primary);
                font-size: 12px;
                margin-top: 4px;
                display: block;
            }

            .form-grid-2 {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 16px;
            }

            @media (max-width: 640px) {
                .form-grid-2 { grid-template-columns: 1fr; }
            }

            /* Modal CTAs */
            .btn-modal-primary {
                background: #FFFFFF;
                color: #0A0A0A;
                padding: 11px 24px;
                border-radius: 10px;
                font-weight: 700;
                font-size: 14px;
                border: 1px solid rgba(255, 255, 255, 0.9);
                cursor: pointer;
                transition: all 0.2s ease;
                box-shadow: 0 4px 16px rgba(255, 255, 255, 0.12);
            }

            .btn-modal-primary:hover {
                background: #E4E4E7;
                color: #000000;
                transform: translateY(-1px);
                box-shadow: 0 6px 20px rgba(255, 255, 255, 0.18);
            }

            .btn-modal-secondary {
                background: rgba(255, 255, 255, 0.04);
                color: var(--text-secondary);
                padding: 11px 24px;
                border-radius: 10px;
                font-weight: 600;
                font-size: 14px;
                border: 1px solid var(--border-default);
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .btn-modal-secondary:hover {
                border-color: rgba(255, 255, 255, 0.3);
                background: rgba(255, 255, 255, 0.08);
                color: #FFFFFF;
            }

            /* Status Badges */
            .badge {
                display: inline-flex;
                align-items: center;
                padding: 4px 10px;
                border-radius: 999px;
                font-size: 11px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                white-space: nowrap;
            }

            .badge-pending { background: rgba(245, 158, 11, 0.15); color: #F59E0B; }
            .badge-paid { background: rgba(16, 185, 129, 0.15); color: #10B981; }
            .badge-converted { background: rgba(96, 165, 250, 0.15); color: #60A5FA; }
            .badge-cancelled { background: rgba(239, 68, 68, 0.15); color: #EF4444; }

            /* Body scroll lock when modal open */
            body.modal-open { overflow: hidden; }
        </style>
    </head>
    <body class="antialiased">
        <a href="#main" class="skip-link">Saltar al contenido principal</a>

        <!-- Ambient Background Glow -->
        <div class="ambient-glow ambient-glow-tl"></div>
        <div class="ambient-glow ambient-glow-br"></div>

        <div class="app-shell">
            <!-- Sidebar (Desktop - LEFT) -->
            <aside class="sidebar">
                <!-- Brand -->
                <a href="{{ route('dashboard') }}" class="sidebar-brand" style="text-decoration: none; padding: 12px 14px 24px; display: block;">
                    <img src="{{ asset('img/WEB-Kosta-White.svg') }}" alt="Kosta Studio Films" style="height: 46px; width: auto; max-width: 195px; object-fit: contain;">
                </a>

                <!-- Nav Section: Principal -->
                <div class="sidebar-nav">
                    <div class="sidebar-section-label">Principal</div>
                    <a wire:navigate href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('kamo') }}" target="_blank" class="nav-link {{ request()->routeIs('kamo', 'perfil', 'cv') ? 'active' : '' }}" title="Ver Perfil Kamo">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Kamo
                        <svg style="width: 12px; height: 12px; margin-left: auto; opacity: 0.4;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>

                <!-- Nav Section: Finanzas -->
                <div class="sidebar-section-label" style="margin-top: 16px;">Finanzas</div>
                <a wire:navigate href="{{ route('quotes.index') }}" class="nav-link {{ request()->routeIs('quotes.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Cotizaciones
                </a>
                <a wire:navigate href="{{ route('bills.index') }}" class="nav-link {{ request()->routeIs('bills.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Cuentas de Cobro
                </a>
                <a wire:navigate href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-4-7 4V5a2 2 0 012-2h10a2 2 0 012 2v16z"/>
                    </svg>
                    Facturas
                </a>
                <a wire:navigate href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                    Gastos
                </a>

                <!-- Nav Section: Negocio -->
                <div class="sidebar-section-label" style="margin-top: 16px;">Negocio</div>
                <a wire:navigate href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Clientes
                </a>
                <a wire:navigate href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.index') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Servicios
                </a>
                <a wire:navigate href="{{ route('leads.index') }}" class="nav-link {{ request()->routeIs('leads.*') ? 'active' : '' }}" style="position: relative;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span>Prospectos (Leads)</span>
                    @php
                        $nuevosCount = 0;
                        try {
                            if (\Illuminate\Support\Facades\Schema::hasTable('leads') && \Illuminate\Support\Facades\Schema::hasColumn('leads', 'estado')) {
                                $nuevosCount = \App\Models\Lead::where('estado', 'nuevo')->count();
                            }
                        } catch (\Throwable $e) {
                            $nuevosCount = 0;
                        }
                    @endphp
                    @if($nuevosCount > 0)
                        <span style="margin-left: auto; background: #e63946; color: #fff; font-size: 11px; font-weight: 700; padding: 2px 7px; border-radius: 999px;">{{ $nuevosCount }}</span>
                    @endif
                </a>
                <a wire:navigate href="{{ route('automations.index') }}" class="nav-link {{ request()->routeIs('automations.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Automatizaciones</span>
                </a>

                <!-- Nav Section: Sitio Web -->
                <div class="sidebar-section-label" style="margin-top: 16px;">Sitio Web</div>

                <a wire:navigate href="{{ route('case-studies.index') }}" class="nav-link {{ request()->routeIs('case-studies.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Casos de Estudio
                </a>
                <a wire:navigate href="{{ route('case-studies.index') }}"
                   class="nav-sublink {{ request()->routeIs('case-studies.*') && !request()->get('cat') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span>Todos los casos</span>
                </a>
                <a wire:navigate href="{{ route('case-studies.index') }}?cat=web"
                   class="nav-sublink {{ request()->get('cat') === 'web' ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                    <span>Páginas Web</span>
                </a>
                <a wire:navigate href="{{ route('case-studies.index') }}?cat=ecommerce"
                   class="nav-sublink {{ request()->get('cat') === 'ecommerce' ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>E-commerce</span>
                </a>
                <a wire:navigate href="{{ route('case-studies.index') }}?cat=sistema"
                   class="nav-sublink {{ request()->get('cat') === 'sistema' ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                    <span>Sistemas</span>
                </a>
                <a wire:navigate href="{{ route('case-studies.index') }}?cat=films"
                   class="nav-sublink {{ in_array(request()->get('cat'), ['films', 'social'], true) ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span>Films & Reels</span>
                </a>
                <a wire:navigate href="{{ route('case-studies.index') }}?cat=branding"
                   class="nav-sublink {{ request()->get('cat') === 'branding' ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    <span>Branding</span>
                </a>
                </div>

                <!-- User Card -->
                <div class="sidebar-footer">
                    @auth
                        <div class="sidebar-user">
                            <div class="sidebar-user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="sidebar-user-info">
                                <div class="sidebar-user-name" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</div>
                                <div class="sidebar-user-email" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="sidebar-user-form">
                                @csrf
                                <button type="submit" class="sidebar-logout" title="Cerrar sesión" aria-label="Cerrar sesión">
                                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </aside>

            <!-- Mobile Top Bar -->
            <div class="mobile-topbar">
                <a href="{{ route('dashboard') }}" class="mobile-topbar-brand" wire:navigate>
                    <img src="{{ asset('img/WEB-Kosta-White.svg') }}" alt="Kosta">
                </a>
                <div class="mobile-topbar-actions">
                    <a href="{{ route('leads.index') }}" class="mobile-topbar-btn" title="Prospectos Web" wire:navigate>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        @php
                            $unreadLeadsCount = \App\Models\Lead::where('estado', 'nuevo')->count();
                        @endphp
                        @if($unreadLeadsCount > 0)
                            <span class="mobile-badge-dot"></span>
                        @endif
                    </a>
                    <button type="button" class="mobile-topbar-btn" onclick="toggleDrawerSheet()" aria-label="Abrir Menú">
                        @auth
                            <span style="font-weight: 700; font-size: 13px;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                            </svg>
                        @endauth
                    </button>
                </div>
            </div>

            <!-- Main Content -->
            <main id="main" class="main-content">
                {{ $slot }}
            </main>

            <!-- Bottom Navigation (Mobile App-Style) -->
            <nav class="app-bottom-nav">
                <!-- 1. Inicio -->
                <a wire:navigate href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Inicio</span>
                </a>

                <!-- 2. Cotizaciones -->
                <a wire:navigate href="{{ route('quotes.index') }}" class="bottom-nav-item {{ request()->routeIs('quotes.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Cotiz.</span>
                </a>

                <!-- 3. Central FAB (+) -->
                <div class="bottom-nav-fab-wrap">
                    <button type="button" id="bottom-nav-fab-btn" class="bottom-nav-fab" onclick="toggleActionSheet()" aria-label="Crear nuevo...">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                    <span class="bottom-nav-fab-label">Crear</span>
                </div>

                <!-- 4. Finanzas (Cobros & Facturas) -->
                <a wire:navigate href="{{ route('bills.index') }}" class="bottom-nav-item {{ (request()->routeIs('bills.*') || request()->routeIs('invoices.*') || request()->routeIs('expenses.*')) ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Finanzas</span>
                </a>

                <!-- 5. Menú Más -->
                <button type="button" class="bottom-nav-item" onclick="toggleDrawerSheet()" aria-label="Abrir Menú Completo">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <span>Menú</span>
                </button>
            </nav>
        </div>

        <!-- Sheet Backdrop -->
        <div id="mobile-sheet-backdrop" class="mobile-sheet-backdrop" onclick="closeAllSheets()"></div>

        <!-- Quick Action Bottom Sheet (FAB) -->
        <div id="quick-action-sheet" class="mobile-action-sheet">
            <div class="sheet-handle"></div>
            <div class="sheet-header">
                <h3 class="sheet-title">Creación Rápida</h3>
                <button type="button" class="sheet-close-btn" onclick="closeAllSheets()" aria-label="Cerrar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="sheet-grid">
                <a href="{{ route('quotes.create') }}" class="sheet-grid-item" wire:navigate onclick="closeAllSheets()">
                    <div class="sheet-grid-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="sheet-grid-label">Cotización</div>
                        <div class="sheet-grid-desc">Propuesta formal</div>
                    </div>
                </a>

                <a href="{{ route('bills.index') }}" class="sheet-grid-item" wire:navigate onclick="closeAllSheets()">
                    <div class="sheet-grid-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="sheet-grid-label">Cuenta Cobro</div>
                        <div class="sheet-grid-desc">Cobro a cliente</div>
                    </div>
                </a>

                <a href="{{ route('invoices.create') }}" class="sheet-grid-item" wire:navigate onclick="closeAllSheets()">
                    <div class="sheet-grid-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-4-7 4V5a2 2 0 012-2h10a2 2 0 012 2v16z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="sheet-grid-label">Factura</div>
                        <div class="sheet-grid-desc">Facturación legal</div>
                    </div>
                </a>

                <a href="{{ route('expenses.index') }}" class="sheet-grid-item" wire:navigate onclick="closeAllSheets()">
                    <div class="sheet-grid-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                        </svg>
                    </div>
                    <div>
                        <div class="sheet-grid-label">Gasto</div>
                        <div class="sheet-grid-desc">Registrar egreso</div>
                    </div>
                </a>

                <a href="{{ route('clients.index') }}" class="sheet-grid-item" wire:navigate onclick="closeAllSheets()">
                    <div class="sheet-grid-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="sheet-grid-label">Cliente</div>
                        <div class="sheet-grid-desc">Nuevo contacto</div>
                    </div>
                </a>

                <a href="{{ route('case-studies.create') }}" class="sheet-grid-item" wire:navigate onclick="closeAllSheets()">
                    <div class="sheet-grid-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="sheet-grid-label">Caso Estudio</div>
                        <div class="sheet-grid-desc">Portafolio web</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Full Mobile Drawer Sheet (Menú) -->
        <div id="mobile-drawer-sheet" class="mobile-drawer-sheet">
            <div class="sheet-handle"></div>
            
            @auth
                <div class="flex items-center gap-3 p-3 rounded-2xl bg-white/[0.04] border border-white/[0.08] mb-5">
                    <div class="w-11 h-11 rounded-xl bg-white text-zinc-950 flex items-center justify-center font-bold text-base flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-white font-semibold text-sm truncate">{{ auth()->user()->name }}</p>
                        <p class="text-zinc-400 text-xs truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            @endauth

            <!-- Grupo 1: CRM & Operaciones -->
            <div class="drawer-nav-group">
                <div class="drawer-nav-heading">CRM & Operaciones</div>
                <a href="{{ route('leads.index') }}" class="drawer-nav-item {{ request()->routeIs('leads.*') ? 'active' : '' }}" wire:navigate onclick="closeAllSheets()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span>Prospectos / Leads</span>
                    @if(isset($unreadLeadsCount) && $unreadLeadsCount > 0)
                        <span class="drawer-nav-badge">{{ $unreadLeadsCount }}</span>
                    @endif
                </a>

                <a href="{{ route('automations.index') }}" class="drawer-nav-item {{ request()->routeIs('automations.*') ? 'active' : '' }}" wire:navigate onclick="closeAllSheets()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Automatizaciones</span>
                </a>

                <a href="{{ route('clients.index') }}" class="drawer-nav-item {{ request()->routeIs('clients.*') ? 'active' : '' }}" wire:navigate onclick="closeAllSheets()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Clientes</span>
                </a>

                <a href="{{ route('services.index') }}" class="drawer-nav-item {{ request()->routeIs('services.*') ? 'active' : '' }}" wire:navigate onclick="closeAllSheets()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Servicios de Agencia</span>
                </a>

                <a href="{{ route('case-studies.index') }}" class="drawer-nav-item {{ request()->routeIs('case-studies.*') ? 'active' : '' }}" wire:navigate onclick="closeAllSheets()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Casos de Estudio / Portafolio</span>
                </a>
            </div>

            <!-- Grupo 2: Finanzas -->
            <div class="drawer-nav-group">
                <div class="drawer-nav-heading">Facturación & Finanzas</div>
                <a href="{{ route('quotes.index') }}" class="drawer-nav-item {{ request()->routeIs('quotes.*') ? 'active' : '' }}" wire:navigate onclick="closeAllSheets()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Cotizaciones</span>
                </a>

                <a href="{{ route('bills.index') }}" class="drawer-nav-item {{ request()->routeIs('bills.*') ? 'active' : '' }}" wire:navigate onclick="closeAllSheets()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Cuentas de Cobro</span>
                </a>

                <a href="{{ route('invoices.index') }}" class="drawer-nav-item {{ request()->routeIs('invoices.*') ? 'active' : '' }}" wire:navigate onclick="closeAllSheets()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-4-7 4V5a2 2 0 012-2h10a2 2 0 012 2v16z"/>
                    </svg>
                    <span>Facturas</span>
                </a>

                <a href="{{ route('expenses.index') }}" class="drawer-nav-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}" wire:navigate onclick="closeAllSheets()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                    <span>Gastos de Operación</span>
                </a>
            </div>

            <!-- Grupo 3: Cuenta & Enlaces -->
            <div class="drawer-nav-group">
                <div class="drawer-nav-heading">Ajustes & Acceso</div>
                <a href="{{ route('profile') }}" class="drawer-nav-item {{ request()->routeIs('profile') ? 'active' : '' }}" wire:navigate onclick="closeAllSheets()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Mi Perfil</span>
                </a>

                <a href="{{ route('landing') }}" class="drawer-nav-item" target="_blank" rel="noopener">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>Ver Sitio Web Kosta</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 p-3 rounded-xl border border-red-500/20 bg-red-500/10 text-red-400 font-semibold text-sm transition-colors hover:bg-red-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Global Thinking Orb HUD (Livewire & background transitions) --}}
        <div id="global-thinking-hud" class="global-thinking-hud">
            <thinking-orb state="working" size="20" label="Procesando..." pill></thinking-orb>
        </div>

        <script>
        function toggleActionSheet() {
            const sheet = document.getElementById('quick-action-sheet');
            const backdrop = document.getElementById('mobile-sheet-backdrop');
            const fab = document.getElementById('bottom-nav-fab-btn');
            const isOpen = sheet.classList.contains('active');
            
            document.getElementById('mobile-drawer-sheet')?.classList.remove('active');

            if (isOpen) {
                sheet.classList.remove('active');
                backdrop.classList.remove('active');
                fab?.classList.remove('is-open');
            } else {
                sheet.classList.add('active');
                backdrop.classList.add('active');
                fab?.classList.add('is-open');
            }
        }

        function toggleDrawerSheet() {
            const drawer = document.getElementById('mobile-drawer-sheet');
            const backdrop = document.getElementById('mobile-sheet-backdrop');
            const fab = document.getElementById('bottom-nav-fab-btn');
            const isOpen = drawer.classList.contains('active');

            document.getElementById('quick-action-sheet')?.classList.remove('active');
            fab?.classList.remove('is-open');

            if (isOpen) {
                drawer.classList.remove('active');
                backdrop.classList.remove('active');
            } else {
                drawer.classList.add('active');
                backdrop.classList.add('active');
            }
        }

        function closeAllSheets() {
            document.getElementById('quick-action-sheet')?.classList.remove('active');
            document.getElementById('mobile-drawer-sheet')?.classList.remove('active');
            document.getElementById('mobile-sheet-backdrop')?.classList.remove('active');
            document.getElementById('bottom-nav-fab-btn')?.classList.remove('is-open');
        }

        document.addEventListener('livewire:navigated', () => {
            closeAllSheets();
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.nav-link').forEach(link => {
                const elements = link.querySelectorAll('svg path, svg polyline, svg line, svg circle, svg rect, svg ellipse');

                link.addEventListener('mouseenter', () => {
                    elements.forEach((el, i) => {
                        const len = el.getTotalLength ? Math.ceil(el.getTotalLength()) + 2 : 150;
                        el.style.transition = 'none';
                        el.style.strokeDasharray = len;
                        el.style.strokeDashoffset = len;
                        void el.getBoundingClientRect();
                        const delay = i * 0.12;
                        el.style.transition = `stroke-dashoffset 1.4s cubic-bezier(0.4, 0, 0.2, 1) ${delay}s`;
                        el.style.strokeDashoffset = '0';
                    });
                });

                link.addEventListener('mouseleave', () => {
                    elements.forEach(el => {
                        el.style.transition = 'none';
                        el.style.strokeDasharray = '';
                        el.style.strokeDashoffset = '';
                    });
                });
            });
        });
        </script>
        @livewireScripts
    </body>
</html>
