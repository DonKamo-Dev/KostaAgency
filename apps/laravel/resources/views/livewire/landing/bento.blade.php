<div class="bento-canvas-wrapper">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Syne:wght@600;700;800&display=swap');

        * {
            box-sizing: border-box;
        }

        .bento-canvas-wrapper {
            width: 100%;
            min-height: 100vh;
            background: var(--bento-bg);
            padding: 36px 20px 80px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--bento-text-main);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            transition: background-color 0.25s ease, color 0.25s ease;
        }

        .bento-main-container {
            width: 100%;
            max-width: 1160px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* ── Top Nav Bar ── */
        .bento-top-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 0 2px;
        }
        .nav-left-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-right-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-top-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: var(--radius-btn);
            background: var(--bento-surface);
            border: 1px solid var(--bento-border);
            color: var(--bento-text-muted);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: var(--bento-card-shadow);
            transition: all 0.2s ease;
        }
        .btn-top-back:hover {
            color: var(--bento-text-main);
            border-color: var(--bento-border-hover);
            transform: translateY(-1px);
        }
        .bento-url-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 14px;
            border-radius: var(--radius-badge);
            background: var(--bento-surface-subtle);
            border: 1px solid var(--bento-border);
            font-size: 12px;
            font-weight: 600;
            color: var(--bento-text-muted);
        }
        .live-dot-mono {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--bento-text-main);
            box-shadow: 0 0 8px var(--bento-text-main);
        }
        .btn-theme-toggle {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 14px;
            border-radius: var(--radius-btn);
            background: var(--bento-surface);
            border: 1px solid var(--bento-border);
            color: var(--bento-text-main);
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: var(--bento-card-shadow);
            transition: all 0.2s ease;
        }
        .btn-theme-toggle:hover {
            border-color: var(--bento-border-hover);
            transform: translateY(-1px);
            background: var(--bento-surface-hover);
        }
        .theme-toggle-icon {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-top-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            border-radius: var(--radius-btn);
            background: var(--bento-primary-btn-bg);
            color: var(--bento-primary-btn-text);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.2s ease;
        }
        .btn-top-cta:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        /* ── Top Split: Left Bio (1 col) + Right Cards (2x2 flush grid) ── */
        .bento-top-section {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 20px;
            align-items: start;
        }
        @media (max-width: 920px) {
            .bento-top-section {
                grid-template-columns: 1fr;
            }
        }

        /* ── Left Column: Architectural Profile Bio ── */
        .bio-column {
            display: flex;
            flex-direction: column;
            gap: 16px;
            padding: 4px 6px 10px 0;
        }
        .profile-avatar-frame {
            width: 170px;
            height: 170px;
            border-radius: 50%;
            padding: 4px;
            background: var(--bento-surface);
            border: 1px solid var(--bento-border);
            box-shadow: var(--bento-card-shadow);
            position: relative;
        }
        .avatar-photo-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            transform: scale(1.02);
            display: block;
            filter: grayscale(15%);
            transition: filter 0.3s ease, transform 0.3s ease;
        }
        .avatar-photo-img:hover {
            filter: grayscale(0%);
            transform: scale(1.04);
        }
        .avatar-live-indicator {
            position: absolute;
            bottom: 6px;
            right: 6px;
            padding: 3px 8px;
            border-radius: var(--radius-badge);
            background: var(--bento-surface);
            border: 1px solid var(--bento-border);
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 10.5px;
            font-weight: 700;
            color: var(--bento-text-main);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            z-index: 2;
        }
        .avatar-live-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--bento-text-main);
            box-shadow: 0 0 6px var(--bento-text-main);
        }
        .bio-name-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .bio-name {
            font-family: 'Syne', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: var(--bento-text-main);
            letter-spacing: -0.03em;
            margin: 0;
            line-height: 1.15;
            white-space: nowrap;
        }
        .bio-role-badge {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 11px;
            font-weight: 700;
            color: var(--bento-text-main);
            background: var(--bento-surface-subtle);
            border: 1px solid var(--bento-border);
            padding: 4px 10px;
            border-radius: var(--radius-badge);
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-top: 8px;
            margin-bottom: 4px;
        }
        .bio-description {
            font-size: 13.5px;
            line-height: 1.6;
            color: var(--bento-text-muted);
            margin: 0;
        }

        /* ── Availability Highlight Card (Horizontal Mosaic Banner) ── */
        .availability-highlight-card {
            grid-column: 1 / -1;
            background: var(--bento-surface);
            border: 1px solid var(--bento-border);
            border-radius: var(--radius-card);
            padding: 14px 20px;
            box-shadow: var(--bento-card-shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            position: relative;
            overflow: hidden;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .availability-highlight-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--bento-card-shadow-hover);
            border-color: var(--bento-border-hover);
        }
        @media (max-width: 600px) {
            .availability-highlight-card {
                flex-direction: column;
                align-items: flex-start;
                padding: 16px;
            }
        }
        .availability-badge-header {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .status-radar {
            position: relative;
            width: 10px;
            height: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .radar-ping {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--bento-text-main);
            opacity: 0.6;
            animation: radar-wave 1.8s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
        .radar-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--bento-text-main);
            z-index: 1;
        }
        @keyframes radar-wave {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(2.6); opacity: 0; }
        }
        .availability-title {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--bento-text-subtle);
        }
        .availability-main-text {
            font-family: 'Syne', sans-serif;
            font-size: 14.5px;
            font-weight: 700;
            color: var(--bento-text-main);
            line-height: 1.3;
            margin: 0;
        }
        .btn-book-meeting {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: var(--radius-btn);
            background: var(--bento-primary-btn-bg);
            color: var(--bento-primary-btn-text);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: var(--bento-card-shadow);
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .btn-book-meeting:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow: var(--bento-card-shadow-hover);
        }

        /* ── Right Column: 2x2 Mosaic Cards Grid ── */
        .bento-cards-mosaic {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            align-items: stretch;
        }
        @media (max-width: 600px) {
            .bento-cards-mosaic {
                grid-template-columns: 1fr;
            }
        }

        /* ── Standard Bento Card ── */
        .bento-c-card {
            background: var(--bento-surface);
            border: 1px solid var(--bento-border);
            border-radius: var(--radius-card);
            box-shadow: var(--bento-card-shadow);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.2s ease;
            position: relative;
            overflow: hidden;
            min-height: 240px;
        }
        .bento-c-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--bento-card-shadow-hover);
            border-color: var(--bento-border-hover);
        }

        /* ── Card Header Rows ── */
        .card-top-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
        }
        .card-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-app-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: var(--bento-surface-subtle);
            border: 1px solid var(--bento-border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--bento-text-main);
        }
        .card-title-text {
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--bento-text-main);
            line-height: 1.2;
            margin: 0;
        }
        .card-sub-domain {
            font-size: 11.5px;
            font-weight: 500;
            color: var(--bento-text-subtle);
            margin: 0;
        }
        .card-follow-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: var(--radius-badge);
            background: var(--bento-surface-subtle);
            border: 1px solid var(--bento-border);
            color: var(--bento-text-main);
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .card-follow-pill:hover {
            background: var(--bento-surface-hover);
            border-color: var(--bento-border-hover);
        }

        /* ── Direct Project Links List ── */
        .project-links-list {
            display: flex;
            flex-direction: column;
            gap: 7px;
            margin-top: auto;
        }
        .project-link-row {
            background: var(--bento-surface-subtle);
            border: 1px solid var(--bento-border);
            border-radius: 8px;
            padding: 8px 11px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .project-link-row:hover {
            background: var(--bento-surface-hover);
            border-color: var(--bento-border-hover);
            transform: translateX(2px);
        }
        .project-row-left {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 0;
        }
        .project-pill-icon {
            width: 26px;
            height: 26px;
            border-radius: 6px;
            background: var(--bento-surface);
            border: 1px solid var(--bento-border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--bento-text-main);
        }
        .project-row-info {
            display: flex;
            flex-direction: column;
            gap: 1px;
            min-width: 0;
        }
        .project-row-name {
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: var(--bento-text-main);
            line-height: 1.15;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .project-row-tag {
            font-size: 10.5px;
            font-weight: 500;
            color: var(--bento-text-subtle);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .project-row-action {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }
        .project-status-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--bento-text-main);
        }

        /* ── 2x2 Showcase Grid Card ── */
        .showcase-2x2-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: auto;
        }
        .mini-card-item {
            background: var(--bento-surface-subtle);
            border: 1px solid var(--bento-border);
            border-radius: 8px;
            padding: 8px 10px;
            display: flex;
            flex-direction: column;
            gap: 2px;
            transition: all 0.2s ease;
        }
        .mini-card-item:hover {
            background: var(--bento-surface-hover);
            border-color: var(--bento-border-hover);
        }
        .mini-card-header {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .mini-card-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--bento-text-main);
            line-height: 1.2;
        }
        .mini-card-tag {
            font-size: 10px;
            font-weight: 600;
            color: var(--bento-text-subtle);
        }

        /* ── Social Split Container (Left Box in Row 2) ── */
        .social-split-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            min-height: 155px;
        }
        .social-mini-link-card {
            background: var(--bento-surface);
            border: 1px solid var(--bento-border);
            border-radius: var(--radius-card);
            box-shadow: var(--bento-card-shadow);
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .social-mini-link-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--bento-card-shadow-hover);
            border-color: var(--bento-border-hover);
        }
        .social-mini-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: var(--bento-surface-subtle);
            border: 1px solid var(--bento-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--bento-text-main);
        }
        .social-mini-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--bento-text-main);
            line-height: 1.3;
            margin-top: 8px;
        }
        .social-mini-sub {
            font-size: 10.5px;
            color: var(--bento-text-subtle);
        }

        .social-sub-split {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .social-sub-btn {
            flex: 1;
            background: var(--bento-surface);
            border: 1px solid var(--bento-border);
            border-radius: 8px;
            padding: 9px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            box-shadow: var(--bento-card-shadow);
            transition: all 0.2s ease;
        }
        .social-sub-btn:hover {
            transform: translateX(2px);
            border-color: var(--bento-border-hover);
            background: var(--bento-surface-hover);
        }
        .sub-btn-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sub-btn-icon {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            background: var(--bento-surface-subtle);
            border: 1px solid var(--bento-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--bento-text-main);
        }
        .sub-btn-name {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--bento-text-main);
        }

        /* ── Map Graphic Card (Right Box in Row 2) ── */
        .map-card-container {
            background: var(--bento-surface);
            border: 1px solid var(--bento-border);
            border-radius: var(--radius-card);
            box-shadow: var(--bento-card-shadow);
            overflow: hidden;
            min-height: 155px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 16px;
            transition: all 0.25s ease;
            text-decoration: none;
        }
        .map-card-container:hover {
            transform: translateY(-2px);
            box-shadow: var(--bento-card-shadow-hover);
            border-color: var(--bento-border-hover);
        }
        .map-bg-graphic {
            position: absolute;
            inset: 0;
            background-color: var(--bento-surface-subtle);
            background-image: 
                radial-gradient(var(--bento-border-hover) 15%, transparent 16%),
                radial-gradient(var(--bento-border) 15%, transparent 16%);
            background-size: 40px 40px;
            background-position: 0 0, 20px 20px;
            opacity: 0.6;
        }
        .map-svg-roads {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0.35;
        }
        .map-location-bubble {
            position: relative;
            z-index: 2;
            background: var(--bento-surface);
            backdrop-filter: blur(8px);
            border: 1px solid var(--bento-border);
            border-radius: 8px;
            padding: 8px 12px;
            box-shadow: var(--bento-card-shadow);
            width: fit-content;
        }
        .map-bubble-title {
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: var(--bento-text-main);
        }
        .map-bubble-sub {
            font-size: 10.5px;
            font-weight: 600;
            color: var(--bento-text-subtle);
        }
        .map-pulsing-pin {
            position: absolute;
            top: 26px;
            right: 32px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--bento-text-main);
            box-shadow: 0 0 0 4px var(--bento-border-hover);
            animation: map-ping 2s infinite;
            z-index: 2;
        }
        @keyframes map-ping {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.3); }
        }

        /* ── Horizontal Tech Stack Boxes ── */
        .tech-boxes-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            width: 100%;
        }
        @media (max-width: 720px) {
            .tech-boxes-strip {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        .tech-box-item {
            background: var(--bento-surface);
            border: 1px solid var(--bento-border);
            border-radius: var(--radius-card);
            box-shadow: var(--bento-card-shadow);
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }
        .tech-box-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--bento-card-shadow-hover);
            border-color: var(--bento-border-hover);
        }
        .tech-box-icon {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: var(--bento-surface-subtle);
            border: 1px solid var(--bento-border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--bento-text-main);
        }
        .tech-box-content {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }
        .tech-box-title {
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: var(--bento-text-main);
            line-height: 1.1;
        }
        .tech-box-subtitle {
            font-size: 11px;
            font-weight: 500;
            color: var(--bento-text-subtle);
        }

        /* ── Bottom Section: How Can I Help? ── */
        .section-separator-title {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--bento-text-main);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .bottom-cards-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 16px;
        }
        @media (max-width: 700px) {
            .bottom-cards-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ── Monochrome Value Proposition Card ── */
        .card-value-dark {
            background: var(--bento-surface);
            border: 1px solid var(--bento-border);
            border-radius: var(--radius-card);
            padding: 24px;
            color: var(--bento-text-main);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 190px;
            box-shadow: var(--bento-card-shadow);
            transition: all 0.25s ease;
        }
        .card-value-dark:hover {
            transform: translateY(-2px);
            border-color: var(--bento-border-hover);
            box-shadow: var(--bento-card-shadow-hover);
        }
        .card-value-text {
            font-family: 'Syne', sans-serif;
            font-size: 19px;
            font-weight: 700;
            line-height: 1.35;
            letter-spacing: -0.02em;
            color: var(--bento-text-main);
            margin: 0 0 16px 0;
        }
        .card-value-text span {
            color: var(--bento-text-muted);
            text-decoration: underline;
            text-decoration-color: var(--bento-border-hover);
            text-underline-offset: 4px;
        }
        .btn-work-together {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 20px;
            border-radius: var(--radius-btn);
            background: var(--bento-primary-btn-bg);
            color: var(--bento-primary-btn-text);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            width: fit-content;
            transition: all 0.2s ease;
        }
        .btn-work-together:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* ── Monochrome Email Direct Card ── */
        .card-email-box {
            background: var(--bento-surface);
            border: 1px solid var(--bento-border);
            border-radius: var(--radius-card);
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 190px;
            text-decoration: none;
            cursor: pointer;
            position: relative;
            box-shadow: var(--bento-card-shadow);
            transition: all 0.25s ease;
            text-align: left;
        }
        .card-email-box:hover {
            transform: translateY(-2px);
            border-color: var(--bento-border-hover);
            box-shadow: var(--bento-card-shadow-hover);
        }
        .email-arrow-icon {
            align-self: flex-end;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--bento-surface-subtle);
            border: 1px solid var(--bento-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--bento-text-main);
            transition: all 0.2s ease;
        }
        .card-email-box:hover .email-arrow-icon {
            background: var(--bento-surface-hover);
            transform: translate(2px, -2px);
        }
        .email-big-text {
            font-family: 'Syne', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: var(--bento-text-main);
            word-break: break-all;
            margin: 0;
        }
        .email-copy-hint {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--bento-text-subtle);
            margin-top: 6px;
        }

        /* Toast */
        #toast-copied {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 100;
            background: var(--bento-surface);
            color: var(--bento-text-main);
            border: 1px solid var(--bento-border);
            padding: 10px 18px;
            border-radius: var(--radius-btn);
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            opacity: 0;
            transform: translateY(10px);
            pointer-events: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        #toast-copied.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <div class="bento-main-container">

        {{-- Top Navigation --}}
        <header class="bento-top-nav">
            <div class="nav-left-group">
                <a href="{{ route('landing') }}" class="btn-top-back">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Volver a Kamo</span>
                </a>

                <div class="bento-url-pill">
                    <span class="live-dot-mono"></span>
                    <span>kamo.agency / yohan</span>
                </div>
            </div>

            <div class="nav-right-group">
                {{-- Theme Switch Button --}}
                <button type="button" id="theme-toggle-btn" class="btn-theme-toggle" aria-label="Cambiar tema" title="Alternar entre modo oscuro y claro">
                    <span class="theme-toggle-icon">
                        <!-- Sun Icon (visible in dark mode) -->
                        <svg class="icon-sun" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <circle cx="12" cy="12" r="5" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-width="2" d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                        </svg>
                        <!-- Moon Icon (visible in light mode) -->
                        <svg class="icon-moon" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                        </svg>
                    </span>
                    <span class="theme-label" style="font-size: 11.5px;">Tema</span>
                </button>

                <a href="https://wa.me/573113894136" target="_blank" rel="noopener noreferrer" class="btn-top-cta">
                    <span>Contactar</span>
                    <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                </a>
            </div>
        </header>

        {{-- Top Section: Left Profile Bio + Right 2x2 Flush Mosaic Cards --}}
        <div class="bento-top-section">

            {{-- 1. LEFT PROFILE BIO --}}
            <div class="bio-column">
                <div class="profile-avatar-frame">
                    <img src="{{ asset('img/profile-pic.jpg') }}?v={{ time() }}" alt="Yohan Blanco" class="avatar-photo-img">
                    <div class="avatar-live-indicator">
                        <span class="avatar-live-dot"></span>
                        <span>Activo</span>
                    </div>
                </div>

                <div>
                    <div class="bio-name-row">
                        <h1 class="bio-name">Yohan Blanco</h1>
                    </div>
                    <span class="bio-role-badge">WordPress Developer & Digital Marketing</span>
                </div>

                <p class="bio-description">
                    Especialista en desarrollo web de alto impacto con WordPress y estratega de pauta digital (Google & Meta Ads).
                </p>

                <p class="bio-description">
                    Mi gran diferenciador es el uso avanzado de Inteligencia Artificial como copiloto para acelerar código, optimizar procesos y construir plataformas completas en tiempo récord.
                </p>
            </div>

            {{-- 2. RIGHT MOSAIC CARDS --}}
            <div class="bento-cards-mosaic">

                {{-- Row 1 - Left: Proyectos Recientes Card (Dynamic from Database) --}}
                <div class="bento-c-card">
                    <div class="card-top-header">
                        <div class="card-header-left">
                            <div class="card-app-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title-text">Últimos Proyectos</h3>
                                <p class="card-sub-domain">kamo.agency/portafolio</p>
                            </div>
                        </div>
                        <a href="{{ route('portfolio') }}" class="card-follow-pill">
                            <span>Ver todos</span>
                            <svg width="8" height="8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                        </a>
                    </div>

                    {{-- Direct Project Links List (Dynamic from DB) --}}
                    <div class="project-links-list">
                        @forelse($proyectos as $proyecto)
                            <a href="{{ $proyecto->url_demo ? (str_starts_with($proyecto->url_demo, 'http') ? $proyecto->url_demo : 'https://' . $proyecto->url_demo) : route('portfolio') }}" target="_blank" rel="noopener noreferrer" class="project-link-row">
                                <div class="project-row-left">
                                    <div class="project-pill-icon">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                        </svg>
                                    </div>
                                    <div class="project-row-info">
                                        <span class="project-row-name">{{ $proyecto->titulo }}</span>
                                        <span class="project-row-tag">{{ Str::limit($proyecto->descripcion, 30) }}</span>
                                    </div>
                                </div>
                                <div class="project-row-action">
                                    <span class="project-status-dot" title="Activo"></span>
                                    <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                                </div>
                            </a>
                        @empty
                            <div style="padding: 16px 10px; text-align: center; color: var(--bento-text-subtle); font-size: 11.5px;">
                                No hay proyectos publicados activos aún.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Row 1 - Right: Proyectos & Métricas Destacadas Card --}}
                <div class="bento-c-card">
                    <div class="card-top-header">
                        <div class="card-header-left">
                            <div class="card-app-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title-text">Métricas & Hitos</h3>
                                <p class="card-sub-domain">@yohanblanco</p>
                            </div>
                        </div>
                        <span class="card-follow-pill">+10 Sitios</span>
                    </div>

                    <div class="showcase-2x2-grid">
                        <div class="mini-card-item">
                            <div class="mini-card-header">
                                <span class="mini-card-title">Proyectos USA</span>
                            </div>
                            <span class="mini-card-tag">3 en el Exterior</span>
                        </div>
                        <div class="mini-card-item">
                            <div class="mini-card-header">
                                <span class="mini-card-title">Citas & Reservas</span>
                            </div>
                            <span class="mini-card-tag">2 Plataformas</span>
                        </div>
                        <div class="mini-card-item">
                            <div class="mini-card-header">
                                <span class="mini-card-title">Corporativos</span>
                            </div>
                            <span class="mini-card-tag">WordPress & Headless</span>
                        </div>
                        <div class="mini-card-item">
                            <div class="mini-card-header">
                                <span class="mini-card-title">Google & Meta</span>
                            </div>
                            <span class="mini-card-tag">Pauta & Ads</span>
                        </div>
                    </div>
                </div>

                {{-- Row 2 - Left: Socials (LinkedIn + Behance/GitHub) --}}
                <div class="social-split-container">
                    {{-- LinkedIn --}}
                    <a href="https://www.linkedin.com/in/yohanblaro/" target="_blank" rel="noopener noreferrer" class="social-mini-link-card">
                        <div class="social-mini-icon">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="social-mini-title">LinkedIn</div>
                            <div class="social-mini-sub">linkedin.com/in/yohanblaro</div>
                        </div>
                    </a>

                    {{-- Behance & GitHub Split --}}
                    <div class="social-sub-split">
                        <a href="https://www.behance.net/desingkamo" target="_blank" rel="noopener noreferrer" class="social-sub-btn">
                            <div class="sub-btn-left">
                                <div class="sub-btn-icon">
                                    <svg width="19" height="19" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22 7h-7V5h7v2zm1.726 10c-.442 1.297-2.029 3-5.101 3-3.074 0-5.564-1.729-5.564-5.675 0-3.91 2.325-5.92 5.466-5.92 3.082 0 4.964 1.782 5.375 4.426.078.506.104 1.195.074 1.834h-8.082c.045 1.797 1.229 2.729 2.812 2.729 1.248 0 2.036-.566 2.352-1.394h2.668zm-7.694-4.148h5.304c-.063-1.425-.97-2.28-2.607-2.28-1.554 0-2.52.825-2.697 2.28zM2 3h6.425c2.453 0 4.148.973 4.148 3.125 0 1.233-.615 2.193-1.656 2.657 1.348.455 2.083 1.586 2.083 3.141 0 2.453-1.895 3.877-4.482 3.877H2V3zm3.172 6.848h2.934c1.17 0 1.85-.504 1.85-1.492 0-.986-.68-1.467-1.85-1.467H5.172v2.959zm0 5.426h3.268c1.293 0 2.023-.557 2.023-1.643 0-1.084-.73-1.641-2.023-1.641H5.172v3.284z"/>
                                    </svg>
                                </div>
                                <span class="sub-btn-name">Behance</span>
                            </div>
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        <a href="https://github.com/DonKamo-Dev" target="_blank" rel="noopener noreferrer" class="social-sub-btn">
                            <div class="sub-btn-left">
                                <div class="sub-btn-icon">
                                    <svg width="19" height="19" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.87 1.52 2.34 1.07 2.91.83.1-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2z"/>
                                    </svg>
                                </div>
                                <span class="sub-btn-name">GitHub</span>
                            </div>
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Row 2 - Right: Map Card --}}
                <div class="map-card-container">
                    <div class="map-bg-graphic"></div>
                    <svg class="map-svg-roads" viewBox="0 0 200 120" preserveAspectRatio="none">
                        <path d="M-10,30 Q60,50 120,20 T210,70" stroke="currentColor" stroke-width="6" fill="none"/>
                        <path d="M30,130 Q80,60 160,80 T220,10" stroke="currentColor" stroke-width="5" fill="none"/>
                        <path d="M100,-10 L110,130" stroke="currentColor" stroke-width="4" fill="none"/>
                    </svg>
                    <div class="map-pulsing-pin"></div>
                    <div class="map-location-bubble">
                        <div class="map-bubble-title">Cartagena, Colombia</div>
                        <div class="map-bubble-sub">UTC-5 (Caribe / EST / CST)</div>
                    </div>
                </div>

                {{-- Row 3 - Full Width: Live Availability Highlight Card --}}
                <div class="availability-highlight-card">
                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        <div class="availability-badge-header">
                            <div class="status-radar">
                                <span class="radar-ping"></span>
                                <span class="radar-dot"></span>
                            </div>
                            <span class="availability-title">Disponible para contratación</span>
                        </div>
                        <p class="availability-main-text">
                            Proyectos Remotos & Consultoría Técnica
                        </p>
                    </div>
                    {{-- Botón de agendamiento (Enlace temporal a Google Meet/Calendar) --}}
                    <a href="https://meet.google.com" target="_blank" rel="noopener noreferrer" class="btn-book-meeting" id="btn-book-meeting" title="Reserva tu cita por Google Meet">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Reserva tu cita</span>
                        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                    </a>
                </div>

            </div>

        </div>

        {{-- 3. THE 4 TECH & STACK BOXES (High Fidelity Monochrome SVGs) --}}
        <div class="tech-boxes-strip">
            {{-- Box 1: WordPress --}}
            <div class="tech-box-item">
                <div class="tech-box-icon">
                    <svg width="24" height="24" viewBox="0 0 122.52 122.523" fill="currentColor">
                        <path d="m8.708 61.26c0 20.802 12.089 38.779 29.619 47.298l-25.069-68.686c-2.916 6.536-4.55 13.769-4.55 21.388z"/>
                        <path d="m96.74 58.608c0-6.495-2.333-10.993-4.334-14.494-2.664-4.329-5.161-7.995-5.161-12.324 0-4.831 3.664-9.328 8.825-9.328.233 0 .454.029.681.042-9.35-8.566-21.807-13.796-35.489-13.796-18.36 0-34.513 9.42-43.91 23.688 1.233.037 2.395.063 3.382.063 5.497 0 14.006-.667 14.006-.667 2.833-.167 3.167 3.994.337 4.329 0 0-2.847.335-6.015.501l19.138 56.925 11.501-34.493-8.188-22.434c-2.83-.166-5.511-.501-5.511-.501-2.832-.166-2.5-4.496.332-4.329 0 0 8.679.667 13.843.667 5.496 0 14.006-.667 14.006-.667 2.835-.167 3.168 3.994.337 4.329 0 0-2.853.335-6.015.501l18.992 56.494 5.242-17.517c2.272-7.269 4.001-12.49 4.001-16.989z"/>
                        <path d="m62.184 65.857-15.768 45.819c4.708 1.384 9.687 2.141 14.846 2.141 6.12 0 11.989-1.058 17.452-2.979-.141-.225-.269-.464-.374-.724z"/>
                        <path d="m107.376 36.046c.226 1.674.354 3.471.354 5.404 0 5.333-.996 11.328-3.996 18.824l-16.053 46.413c15.624-9.111 26.133-26.038 26.133-45.426.001-9.137-2.333-17.729-6.438-25.215z"/>
                        <path d="m61.262 0c-33.779 0-61.262 27.481-61.262 61.26 0 33.783 27.483 61.263 61.262 61.263 33.778 0 61.265-27.48 61.265-61.263-.001-33.779-27.487-61.26-61.265-61.26zm0 119.715c-32.23 0-58.453-26.223-58.453-58.455 0-32.23 26.222-58.451 58.453-58.451 32.229 0 58.45 26.221 58.45 58.451 0 32.232-26.221 58.455-58.45 58.455z"/>
                    </svg>
                </div>
                <div class="tech-box-content">
                    <span class="tech-box-title">WordPress</span>
                    <span class="tech-box-subtitle">Core CMS & Custom</span>
                </div>
            </div>

            {{-- Box 2: Google Ads --}}
            <div class="tech-box-item">
                <div class="tech-box-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M21.35 11.1h-9.17v2.98h5.27c-.23 1.2-1.07 2.22-2.27 2.9v2.41h3.67c2.15-1.98 3.39-4.89 3.39-8.29 0-.67-.06-1.33-.17-1.98z"/>
                        <path d="M12.18 21c2.43 0 4.47-.8 5.96-2.18l-3.67-2.41c-.81.54-1.84.86-2.99.86-2.3 0-4.25-1.55-4.94-3.64H2.76v2.49C4.24 19.06 7.95 21 12.18 21z"/>
                        <path d="M7.24 13.63c-.18-.54-.28-1.11-.28-1.7s.1-1.16.28-1.7V7.74H2.76A9.974 9.974 0 0 0 1.6 11.93c0 1.61.39 3.14 1.16 4.49l4.48-2.79z"/>
                        <path d="M12.18 6.73c1.32 0 2.51.45 3.44 1.34l2.58-2.58C16.64 4.04 14.6 3.2 12.18 3.2 7.95 3.2 4.24 5.14 2.76 8.07l4.48 2.79c.69-2.09 2.64-3.64 4.94-3.64z"/>
                    </svg>
                </div>
                <div class="tech-box-content">
                    <span class="tech-box-title">Google Ads</span>
                    <span class="tech-box-subtitle">SEM & Search Pauta</span>
                </div>
            </div>

            {{-- Box 3: Meta Ads --}}
            <div class="tech-box-item">
                <div class="tech-box-icon">
                    <svg width="22" height="22" viewBox="0 0 36 36" fill="currentColor">
                        <path d="M18 15.7c-2.3-3.6-5.2-5.7-8.7-5.7-5 0-9.3 4-9.3 9.4 0 5.5 4.3 9.6 9.3 9.6 3.7 0 6.6-2.2 8.7-5.8 2.1 3.6 5 5.8 8.7 5.8 5 0 9.3-4.1 9.3-9.6 0-5.4-4.3-9.4-9.3-9.4-3.5 0-6.4 2.1-8.7 5.7zm-8.7 9.8c-3 0-5.5-2.3-5.5-5.9 0-3.5 2.5-5.7 5.5-5.7 2.4 0 4.6 1.8 6.5 4.8-1.9 4-4.1 6.8-6.5 6.8zm17.4 0c-2.4 0-4.6-2.8-6.5-6.8 1.9-3 4.1-4.8 6.5-4.8 3 0 5.5 2.2 5.5 5.7 0 3.6-2.5 5.9-5.5 5.9z"/>
                    </svg>
                </div>
                <div class="tech-box-content">
                    <span class="tech-box-title">Meta Ads</span>
                    <span class="tech-box-subtitle">FB & Instagram Ads</span>
                </div>
            </div>

            {{-- Box 4: AI Assisted --}}
            <div class="tech-box-item">
                <div class="tech-box-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="tech-box-content">
                    <span class="tech-box-title">AI Assisted</span>
                    <span class="tech-box-subtitle">Speed & Co-pilot</span>
                </div>
            </div>
        </div>

        {{-- Bottom Section: How Can I Help? --}}
        <div>
            <h2 class="section-separator-title">¿Cómo puedo ayudarte?</h2>
        </div>

        <div class="bottom-cards-grid">
            {{-- Monochrome Value Proposition Card --}}
            <div class="card-value-dark">
                <p class="card-value-text">
                    Si buscas un <span>WordPress Developer & Especialista Digital</span> que trabaje con la velocidad y precisión de la IA para construir plataformas y maximizar tus conversiones, conversemos.
                </p>
                <a href="https://wa.me/573113894136" target="_blank" rel="noopener noreferrer" class="btn-work-together">
                    <span>Hablemos por WhatsApp</span>
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                </a>
            </div>

            {{-- Monochrome Email Direct Card --}}
            <button type="button" id="copy-email-button" class="card-email-box">
                <div class="email-arrow-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7V17"/>
                    </svg>
                </div>
                <div>
                    <p class="email-big-text">yohanblaro18@gmail.com</p>
                    <p id="copy-hint-text" class="email-copy-hint">Haz clic para copiar email</p>
                </div>
            </button>
        </div>

    </div>

    {{-- Toast Notification --}}
    <div id="toast-copied">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        <span>Email copiado al portapapeles</span>
    </div>

    {{-- Interactive Theme & Clipboard Scripts --}}
    <script>
        function updateThemeIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            const sunIcon = document.querySelector('.icon-sun');
            const moonIcon = document.querySelector('.icon-moon');
            const themeLabel = document.querySelector('.theme-label');
            if (sunIcon && moonIcon) {
                if (isDark) {
                    sunIcon.style.display = 'block';
                    moonIcon.style.display = 'none';
                    if (themeLabel) themeLabel.textContent = 'Claro';
                } else {
                    sunIcon.style.display = 'none';
                    moonIcon.style.display = 'block';
                    if (themeLabel) themeLabel.textContent = 'Oscuro';
                }
            }
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
                try { localStorage.setItem('bento_theme', 'light'); } catch (e) {}
            } else {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
                try { localStorage.setItem('bento_theme', 'dark'); } catch (e) {}
            }
            updateThemeIcons();
        }

        document.getElementById('theme-toggle-btn')?.addEventListener('click', toggleTheme);
        document.addEventListener('DOMContentLoaded', updateThemeIcons);
        updateThemeIcons();

        function copyEmailToClipboard() {
            const email = 'yohanblaro18@gmail.com';
            navigator.clipboard.writeText(email).then(() => {
                const toast = document.getElementById('toast-copied');
                const hint = document.getElementById('copy-hint-text');
                if (hint) hint.textContent = '¡Email copiado al portapapeles! ✨';
                if (toast) {
                    toast.classList.add('show');
                    setTimeout(() => {
                        toast.classList.remove('show');
                        if (hint) hint.textContent = 'Haz clic para copiar email';
                    }, 2500);
                }
            });
        }

        document.getElementById('copy-email-button')?.addEventListener('click', copyEmailToClipboard);
    </script>
</div>
