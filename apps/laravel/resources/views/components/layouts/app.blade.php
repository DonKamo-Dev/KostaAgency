<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kamo') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

        <style>
            /* ===== Design Tokens - Red & Black System ===== */
            :root {
                --bg-base: #0A0A0A;
                --bg-elevated: #111111;
                --bg-card: rgba(20, 20, 20, 0.7);
                --bg-card-solid: #141414;
                --bg-input: rgba(10, 10, 10, 0.6);

                --red-primary: #E63946;
                --red-bright: #FF4757;
                --red-glow: rgba(230, 57, 70, 0.4);
                --red-soft: rgba(230, 57, 70, 0.1);
                --red-dark: #8B1A25;

                --text-primary: #F5F5F5;
                --text-secondary: rgba(245, 245, 245, 0.7);
                --text-muted: rgba(245, 245, 245, 0.5);
                --text-subtle: rgba(245, 245, 245, 0.35);

                --border-subtle: rgba(245, 245, 245, 0.06);
                --border-default: rgba(245, 245, 245, 0.1);
                --border-red: rgba(230, 57, 70, 0.2);
                --border-red-strong: rgba(230, 57, 70, 0.5);

                --positive: #10B981;
                --negative: #EF4444;
                --warning: #F59E0B;

                --sidebar-width: 280px;
                --bottom-nav-height: 72px;
            }

            * {
                font-family: 'Inter', system-ui, sans-serif;
                box-sizing: border-box;
            }

            body {
                margin: 0;
                background: var(--bg-base);
                color: var(--text-primary);
                min-height: 100vh;
                overflow-x: hidden;
            }

            .font-display {
                font-family: 'Space Grotesk', sans-serif;
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
                width: 400px;
                height: 400px;
                background: radial-gradient(circle, var(--red-glow) 0%, transparent 70%);
                opacity: 0.15;
            }

            .ambient-glow-br {
                bottom: -200px;
                left: -100px;
                width: 400px;
                height: 400px;
                background: radial-gradient(circle, rgba(139, 26, 37, 0.2) 0%, transparent 70%);
                opacity: 0.2;
            }

            /* ===== App Shell Layout (Sidebar Left) ===== */
            .app-shell {
                display: grid;
                grid-template-columns: var(--sidebar-width) 1fr;
                min-height: 100vh;
                position: relative;
                z-index: 1;
            }

            @media (max-width: 1023px) {
                .app-shell {
                    grid-template-columns: 1fr;
                    padding-bottom: var(--bottom-nav-height);
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
                box-shadow: -1px 0 0 rgba(230, 57, 70, 0.06);
            }

            /* Decorative accent line on right edge */
            .sidebar::before {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                width: 1px;
                height: 200px;
                background: linear-gradient(180deg, transparent, var(--red-primary), transparent);
                opacity: 0.4;
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
                background: linear-gradient(135deg, var(--red-dark) 0%, var(--red-primary) 50%, #FF4757 100%);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow:
                    0 4px 20px rgba(230, 57, 70, 0.35),
                    inset 0 1px 0 rgba(255, 255, 255, 0.15);
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
                font-family: 'Space Grotesk', sans-serif;
                font-size: 19px;
                font-weight: 700;
                color: var(--text-primary);
                letter-spacing: -0.02em;
            }

            .sidebar-brand-tag {
                font-size: 10px;
                color: var(--red-primary);
                margin-top: 2px;
                text-transform: uppercase;
                letter-spacing: 0.12em;
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
                background: linear-gradient(90deg, transparent, rgba(230, 57, 70, 0.04));
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
                color: var(--red-primary);
                filter: drop-shadow(0 0 8px rgba(230, 57, 70, 0.65));
            }

            .nav-link.active {
                background: linear-gradient(90deg, transparent 0%, rgba(230, 57, 70, 0.12) 100%);
                color: var(--red-primary);
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
                background: var(--red-primary);
                border-radius: 0 3px 3px 0;
                box-shadow: 0 0 12px rgba(230, 57, 70, 0.5);
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
                gap: 8px;
                padding: 6px 12px 6px 38px;
                border-radius: 8px;
                color: var(--text-muted);
                text-decoration: none;
                font-size: 12.5px;
                font-weight: 500;
                transition: all 0.2s ease;
                margin: 1px 0;
                position: relative;
            }
            .nav-sublink::before {
                content: '';
                position: absolute;
                left: 20px;
                top: 50%;
                transform: translateY(-50%);
                width: 5px;
                height: 5px;
                border-radius: 50%;
                background: currentColor;
                opacity: 0.5;
            }
            .nav-sublink:hover {
                color: var(--text-primary);
                background: rgba(255,255,255,0.04);
            }
            .nav-sublink:hover::before { opacity: 1; }
            .nav-sublink.active {
                color: var(--red-primary);
            }
            .nav-sublink.active::before {
                background: var(--red-primary);
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
                border-color: rgba(230, 57, 70, 0.24);
                transform: translateY(-1px);
            }

            .sidebar-user-avatar {
                width: 38px;
                height: 38px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--red-primary), var(--red-dark));
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
                color: white;
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

            /* ===== Mobile Top Bar ===== */
            .mobile-topbar {
                display: none;
                position: sticky;
                top: 0;
                background: rgba(17, 17, 17, 0.98);
                border-bottom: 1px solid var(--border-subtle);
                padding: 16px 20px;
                z-index: 40;
                align-items: center;
                justify-content: space-between;
            }

            @media (max-width: 1023px) {
                .mobile-topbar { display: flex; }
            }

            /* ===== Bottom Navigation (Mobile) ===== */
            .bottom-nav {
                display: none;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                height: var(--bottom-nav-height);
                background: rgba(17, 17, 17, 0.98);
                border-top: 1px solid var(--border-subtle);
                z-index: 50;
                padding: 8px 12px env(safe-area-inset-bottom);
            }

            @media (max-width: 1023px) {
                .bottom-nav {
                    display: grid;
                    grid-template-columns: repeat(5, 1fr);
                    gap: 4px;
                }
            }

            .bottom-nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 4px;
                padding: 8px 4px;
                color: var(--text-muted);
                text-decoration: none;
                font-size: 10px;
                font-weight: 500;
                border-radius: 12px;
                transition: all 0.2s ease;
                position: relative;
            }

            .bottom-nav-item svg {
                width: 22px;
                height: 22px;
                transition: all 0.2s ease;
            }

            .bottom-nav-item.active {
                color: var(--red-primary);
            }

            .bottom-nav-item:hover svg {
                transform: scale(1.15);
                filter: drop-shadow(0 0 8px rgba(230, 57, 70, 0.55));
                color: var(--red-primary);
            }

            .bottom-nav-item.active svg {
                transform: scale(1.1);
                filter: drop-shadow(0 0 8px var(--red-glow));
            }

            .bottom-nav-item.active::before {
                content: '';
                position: absolute;
                top: 0;
                left: 50%;
                transform: translateX(-50%);
                width: 32px;
                height: 3px;
                background: var(--red-primary);
                border-radius: 0 0 3px 3px;
                box-shadow: 0 0 12px var(--red-glow);
            }

            /* ===== Main Content ===== */
            .main-content {
                padding: 32px 40px;
                max-width: 100%;
                overflow-x: hidden;
            }

            @media (max-width: 1023px) {
                .main-content {
                    padding: 24px 20px;
                }
            }

            /* ===== Card Component ===== */
            .card {
                background: rgba(20, 20, 20, 0.95);
                border: 1px solid var(--border-subtle);
                border-radius: 16px;
                padding: 24px;
                transition: all 0.3s ease;
            }

            .card:hover {
                border-color: var(--border-red);
            }

            .card-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 20px;
            }

            .card-title {
                font-family: 'Space Grotesk', sans-serif;
                font-size: 16px;
                font-weight: 600;
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
                background: var(--red-primary);
                color: white;
                box-shadow: 0 4px 16px rgba(230, 57, 70, 0.3);
            }

            .btn-primary:hover {
                transform: translateY(-1px);
                box-shadow: 0 6px 24px rgba(230, 57, 70, 0.4);
            }

            .btn-secondary {
                background: var(--bg-card-solid);
                color: var(--text-primary);
                border: 1px solid var(--border-default);
            }

            .btn-secondary:hover {
                border-color: var(--border-red);
                color: var(--red-primary);
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
                font-family: 'Space Grotesk', sans-serif;
                font-size: 32px;
                font-weight: 700;
                color: var(--text-primary);
                line-height: 1.1;
                letter-spacing: -0.02em;
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

            /* CRUD Table */
            .crud-table-wrapper {
                background: rgba(20, 20, 20, 0.7);
                border: 1px solid var(--border-subtle);
                border-radius: 16px;
                overflow: hidden;
            }

            .crud-table {
                width: 100%;
                border-collapse: collapse;
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
                font-family: 'Space Grotesk', sans-serif;
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
                background: var(--red-primary);
                color: white;
                padding: 11px 24px;
                border-radius: 10px;
                font-weight: 600;
                font-size: 14px;
                border: none;
                cursor: pointer;
                transition: all 0.2s ease;
                box-shadow: 0 4px 16px rgba(230, 57, 70, 0.3);
            }

            .btn-modal-primary:hover {
                background: var(--red-bright);
                transform: translateY(-1px);
                box-shadow: 0 6px 20px rgba(230, 57, 70, 0.4);
            }

            .btn-modal-secondary {
                background: transparent;
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
                border-color: var(--border-red);
                color: var(--text-primary);
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
                <div class="sidebar-brand">
                    <div class="sidebar-brand-logo">
                        <svg style="width: 22px; height: 22px;" fill="none" stroke="white" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="sidebar-brand-name">Kamo</div>
                        <div class="sidebar-brand-tag">Agencia Digital</div>
                    </div>
                </div>

                <!-- Nav Section: Principal -->
                <div class="sidebar-nav">
                    <div class="sidebar-section-label">Principal</div>
                    <a wire:navigate href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
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
                    Todos los casos
                </a>
                <a wire:navigate href="{{ route('case-studies.index') }}?cat=web"
                   class="nav-sublink {{ request()->get('cat') === 'web' ? 'active' : '' }}">
                    Páginas Web
                </a>
                <a wire:navigate href="{{ route('case-studies.index') }}?cat=ecommerce"
                   class="nav-sublink {{ request()->get('cat') === 'ecommerce' ? 'active' : '' }}">
                    E-commerce
                </a>
                <a wire:navigate href="{{ route('case-studies.index') }}?cat=branding"
                   class="nav-sublink {{ request()->get('cat') === 'branding' ? 'active' : '' }}">
                    Branding
                </a>
                <a wire:navigate href="{{ route('case-studies.index') }}?cat=social"
                   class="nav-sublink {{ request()->get('cat') === 'social' ? 'active' : '' }}">
                    Redes Sociales
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
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div class="sidebar-brand-logo" style="width: 36px; height: 36px;">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="white" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="font-display" style="font-size: 18px; font-weight: 700;">Kamo</span>
                </div>
                @auth
                    <div class="sidebar-user-avatar" style="width: 36px; height: 36px; font-size: 13px;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endauth
            </div>

            <!-- Main Content -->
            <main id="main" class="main-content">
                {{ $slot }}
            </main>

            <!-- Bottom Navigation (Mobile) -->
            <nav class="bottom-nav">
                <a wire:navigate href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Inicio</span>
                </a>
                <a wire:navigate href="{{ route('quotes.index') }}" class="bottom-nav-item {{ request()->routeIs('quotes.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Cotiz.</span>
                </a>
                <a wire:navigate href="{{ route('bills.index') }}" class="bottom-nav-item {{ request()->routeIs('bills.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>C. Cobro</span>
                </a>
                <a wire:navigate href="{{ route('invoices.index') }}" class="bottom-nav-item {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-4-7 4V5a2 2 0 012-2h10a2 2 0 012 2v16z"/>
                    </svg>
                    <span>Facturas</span>
                </a>
                <a wire:navigate href="{{ route('expenses.index') }}" class="bottom-nav-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                    <span>Gastos</span>
                </a>
            </nav>
        </div>
        {{-- Global Thinking Orb HUD (Livewire & background transitions) --}}
        <div id="global-thinking-hud" class="global-thinking-hud">
            <thinking-orb state="working" size="20" label="Procesando..." pill></thinking-orb>
        </div>

        <script>
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
