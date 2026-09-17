<div class="bento-canvas-wrapper">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&display=swap');

        * {
            box-sizing: border-box;
        }

        .bento-canvas-wrapper {
            width: 100%;
            min-height: 100vh;
            background: #F7F8FA;
            padding: 40px 20px 80px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0F172A;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .bento-main-container {
            width: 100%;
            max-width: 960px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* ── Top Nav Bar ── */
        .bento-top-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 4px;
        }
        .btn-top-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 999px;
            background: #FFFFFF;
            border: 1px solid rgba(0,0,0,0.07);
            color: #475569;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            transition: all 0.2s ease;
        }
        .btn-top-back:hover {
            color: #0F172A;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        .bento-url-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            background: #FFFFFF;
            border: 1px solid rgba(0,0,0,0.07);
            font-size: 11.5px;
            font-weight: 600;
            color: #64748B;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }
        .btn-top-cta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 999px;
            background: #E63946;
            color: #FFFFFF;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 3px 10px rgba(230, 57, 70, 0.25);
            transition: all 0.2s ease;
        }
        .btn-top-cta:hover {
            background: #D62839;
            transform: translateY(-1px);
        }

        /* ── Top Split: Left Bio (1 col) + Right Cards (2x2 flush grid) ── */
        .bento-top-section {
            display: grid;
            grid-template-columns: 290px 1fr;
            gap: 20px;
            align-items: start;
        }
        @media (max-width: 880px) {
            .bento-top-section {
                grid-template-columns: 1fr;
            }
        }

        /* ── Left Column: Unboxed Profile Bio ── */
        .bio-column {
            display: flex;
            flex-direction: column;
            gap: 14px;
            padding: 4px 8px 10px 0;
        }
        .profile-avatar-circle {
            width: 165px;
            height: 165px;
            border-radius: 50%;
            padding: 4px;
            background: linear-gradient(135deg, #E63946, #0F172A);
            box-shadow: 0 12px 36px rgba(230, 57, 70, 0.25);
            position: relative;
        }
        .avatar-photo-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            background: #FFFFFF;
        }
        .avatar-live-indicator {
            position: absolute;
            bottom: 8px;
            right: 8px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .live-dot-green {
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: #E63946;
        }
        .bio-name-row {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .bio-name {
            font-family: 'Outfit', sans-serif;
            font-size: 26px;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.03em;
            margin: 0;
            line-height: 1.1;
        }
        .bio-lightning {
            color: #E63946;
            font-size: 22px;
        }
        .bio-role-badge {
            display: inline-block;
            font-size: 13px;
            font-weight: 700;
            color: #E63946;
            margin-top: 2px;
        }
        .bio-description {
            font-size: 13px;
            line-height: 1.6;
            color: #475569;
            margin: 0;
        }

        /* ── Availability Highlight Card (Horizontal Mosaic Banner) ── */
        .availability-highlight-card {
            grid-column: 1 / -1;
            background: #FFFFFF;
            border: 1px solid #FECDD3;
            border-radius: 18px;
            padding: 12px 18px;
            box-shadow: 0 4px 18px rgba(230, 57, 70, 0.07);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            position: relative;
            overflow: hidden;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .availability-highlight-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(230, 57, 70, 0.12);
            border-color: #E63946;
        }
        @media (max-width: 600px) {
            .availability-highlight-card {
                flex-direction: column;
                align-items: flex-start;
                padding: 14px;
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
            background: #E63946;
            opacity: 0.75;
            animation: radar-wave 1.8s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
        .radar-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #E63946;
            z-index: 1;
        }
        @keyframes radar-wave {
            0% { transform: scale(1); opacity: 0.85; }
            100% { transform: scale(2.8); opacity: 0; }
        }
        .availability-title {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #E63946;
        }
        .availability-main-text {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 800;
            color: #0F172A;
            line-height: 1.3;
            margin: 0;
        }
        .availability-tags-row {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 0;
        }
        .avail-tag-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 9px;
            border-radius: 8px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            font-size: 11px;
            font-weight: 700;
            color: #0F172A;
            transition: all 0.2s ease;
        }
        .avail-tag-pill:hover {
            background: #0F172A;
            color: #FFFFFF;
            border-color: #0F172A;
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
            background: #FFFFFF;
            border: 1px solid rgba(0, 0, 0, 0.07);
            border-radius: 24px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.2s ease;
            position: relative;
            overflow: hidden;
            min-height: 225px;
        }
        .bento-c-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.06);
            border-color: rgba(0, 0, 0, 0.12);
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
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .card-title-text {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 800;
            color: #0F172A;
            line-height: 1.2;
            margin: 0;
        }
        .card-sub-domain {
            font-size: 11px;
            font-weight: 500;
            color: #94A3B8;
            margin: 0;
        }
        .card-follow-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #E63946;
            color: #FFFFFF;
            font-size: 10.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .card-follow-pill:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* ── Direct Project Links List ── */
        .project-links-list {
            display: flex;
            flex-direction: column;
            gap: 7px;
            margin-top: auto;
        }
        .project-link-row {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 8px 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .project-link-row:hover {
            background: #FFFFFF;
            border-color: #CBD5E1;
            transform: translateX(2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }
        .project-row-left {
            display: flex;
            align-items: center;
            gap: 9px;
        }
        .project-pill-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .project-row-info {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }
        .project-row-name {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 800;
            color: #0F172A;
            line-height: 1.1;
        }
        .project-row-tag {
            font-size: 9.5px;
            font-weight: 600;
            color: #64748B;
        }
        .project-row-action {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .project-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #E63946;
        }

        /* ── Visual Showcase Inner Box ── */
        .showcase-inner-dark {
            width: 100%;
            height: 120px;
            border-radius: 16px;
            background: #0F172A;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: auto;
            position: relative;
            overflow: hidden;
        }
        .showcase-inner-dark::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 110px;
            height: 110px;
            background: radial-gradient(circle, rgba(230, 57, 70, 0.3) 0%, transparent 70%);
            border-radius: 50%;
        }
        .showcase-avatar-sm {
            width: 48px;
            height: 48px;
            border-radius: 13px;
            background: #E63946;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            flex-shrink: 0;
        }
        .showcase-dark-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
            z-index: 1;
        }
        .showcase-dark-title {
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            font-weight: 800;
            color: #FFFFFF;
            line-height: 1.2;
        }
        .showcase-dark-title span {
            color: #E63946;
        }
        .showcase-dark-sub {
            font-size: 11px;
            color: #94A3B8;
            margin-top: 3px;
        }

        /* ── 2x2 Showcase Grid Card ── */
        .showcase-2x2-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
            margin-top: auto;
        }
        .mini-card-item {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 7px 9px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .mini-card-header {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .mini-card-title {
            font-size: 11px;
            font-weight: 800;
            color: #1E293B;
            line-height: 1.2;
        }
        .mini-card-tag {
            font-size: 9px;
            font-weight: 700;
            color: #E63946;
        }

        /* ── Social Split Container (Left Box in Row 2) ── */
        .social-split-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            min-height: 140px;
        }
        .social-mini-link-card {
            background: #FFFFFF;
            border: 1px solid rgba(0, 0, 0, 0.07);
            border-radius: 20px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
            padding: 14px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .social-mini-link-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        }
        .social-mini-icon {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .social-mini-title {
            font-size: 11.5px;
            font-weight: 700;
            color: #0F172A;
            line-height: 1.3;
            margin-top: 6px;
        }
        .social-mini-sub {
            font-size: 10px;
            color: #94A3B8;
        }

        .social-sub-split {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .social-sub-btn {
            flex: 1;
            background: #FFFFFF;
            border: 1px solid rgba(0, 0, 0, 0.07);
            border-radius: 14px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            transition: all 0.2s ease;
        }
        .social-sub-btn:hover {
            transform: translateX(2px);
            border-color: rgba(0,0,0,0.14);
        }
        .sub-btn-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sub-btn-icon {
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .sub-btn-name {
            font-size: 11.5px;
            font-weight: 700;
            color: #0F172A;
        }

        /* ── Map Graphic Card (Right Box in Row 2) ── */
        .map-card-container {
            background: #FFFFFF;
            border: 1px solid rgba(0, 0, 0, 0.07);
            border-radius: 20px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            min-height: 140px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 12px;
            transition: all 0.25s ease;
            text-decoration: none;
        }
        .map-card-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        }
        .map-bg-graphic {
            position: absolute;
            inset: 0;
            background-color: #F1F1F1;
            background-image: 
                radial-gradient(#D4D4D4 15%, transparent 16%),
                radial-gradient(#E0E0E0 15%, transparent 16%);
            background-size: 50px 50px;
            background-position: 0 0, 25px 25px;
        }
        .map-svg-roads {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0.55;
        }
        .map-location-bubble {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 10px;
            padding: 6px 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            width: fit-content;
        }
        .map-bubble-title {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 800;
            color: #0F172A;
        }
        .map-bubble-sub {
            font-size: 10px;
            font-weight: 600;
            color: #64748B;
        }
        .map-pulsing-pin {
            position: absolute;
            top: 28px;
            right: 36px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #E63946;
            box-shadow: 0 0 0 4px rgba(230, 57, 70, 0.25);
            animation: map-ping 2s infinite;
            z-index: 2;
        }
        @keyframes map-ping {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.25); }
        }

        /* ── Horizontal Tech Stack Boxes ── */
        .tech-boxes-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            width: 100%;
        }
        @media (max-width: 720px) {
            .tech-boxes-strip {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        .tech-box-item {
            background: #FFFFFF;
            border: 1px solid rgba(0, 0, 0, 0.07);
            border-radius: 18px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.02);
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }
        .tech-box-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            border-color: rgba(0, 0, 0, 0.12);
        }
        .tech-box-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .tech-box-content {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }
        .tech-box-title {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 800;
            color: #0F172A;
            line-height: 1.1;
        }
        .tech-box-subtitle {
            font-size: 10px;
            font-weight: 600;
            color: #64748B;
        }

        /* ── Bottom Section: How Can I Help? ── */
        .section-separator-title {
            font-family: 'Outfit', sans-serif;
            font-size: 17px;
            font-weight: 800;
            color: #0F172A;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 6px;
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

        /* ── Black Value Proposition Card ── */
        .card-value-dark {
            background: #0F172A;
            border-radius: 24px;
            padding: 24px;
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 180px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
        }
        .card-value-text {
            font-family: 'Outfit', sans-serif;
            font-size: 17px;
            font-weight: 700;
            line-height: 1.4;
            color: #FFFFFF;
            margin: 0 0 14px 0;
        }
        .card-value-text span {
            color: #E63946;
        }
        .btn-work-together {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 999px;
            background: #E63946;
            color: #FFFFFF;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            width: fit-content;
            transition: all 0.2s ease;
        }
        .btn-work-together:hover {
            background: #D62839;
            transform: translateY(-1px);
        }

        /* ── Pastel Email Card ── */
        .card-email-pastel {
            background: #FFF1F2;
            border: 0;
            border-radius: 24px;
            padding: 22px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 180px;
            text-decoration: none;
            cursor: pointer;
            position: relative;
            transition: all 0.25s ease;
        }
        .card-email-pastel:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(230, 57, 70, 0.12);
        }
        .email-arrow-icon {
            align-self: flex-end;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(230, 57, 70, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #E63946;
        }
        .email-big-text {
            font-family: 'Outfit', sans-serif;
            font-size: 16.5px;
            font-weight: 800;
            color: #0F172A;
            word-break: break-all;
            margin: 0;
        }
        .email-copy-hint {
            font-size: 11px;
            font-weight: 700;
            color: #E63946;
            margin-top: 4px;
        }

        /* Toast */
        #toast-copied {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 100;
            background: #0F172A;
            color: #FFFFFF;
            padding: 10px 18px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            opacity: 0;
            transform: translateY(10px);
            pointer-events: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        #toast-copied.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <div class="bento-main-container">

        {{-- Top Navigation --}}
        <header class="bento-top-nav">
            <a href="{{ route('landing') }}" class="btn-top-back">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Volver a Kamo</span>
            </a>

            <div class="bento-url-pill">
                <span class="live-dot-green" style="width: 6px; height: 6px;"></span>
                <span>kamo.agency / yohan</span>
            </div>

            <a href="https://wa.me/573113894136" target="_blank" rel="noopener noreferrer" class="btn-top-cta">
                <span>Contactar ⚡</span>
            </a>
        </header>

        {{-- Top Section: Left Profile Bio + Right 2x2 Flush Mosaic Cards --}}
        <div class="bento-top-section">

            {{-- 1. LEFT PROFILE BIO --}}
            <div class="bio-column">
                <div class="profile-avatar-circle">
                    <img src="{{ asset('img/profile-pic.jpg') }}?v={{ time() }}" alt="Yohan Blanco" class="avatar-photo-img">
                    <div class="avatar-live-indicator">
                        <div class="live-dot-green"></div>
                    </div>
                </div>

                <div>
                    <div class="bio-name-row">
                        <h1 class="bio-name">Yohan Blanco</h1>
                        <span class="bio-lightning">⚡</span>
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
                            <div class="card-app-icon" style="background: #0F172A;">
                                <svg width="18" height="18" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title-text">Últimos Proyectos</h3>
                                <p class="card-sub-domain">kamo.agency/portafolio</p>
                            </div>
                        </div>
                        <a href="{{ route('portfolio') }}" class="card-follow-pill" style="background: #0F172A;">
                            <span>Ver todos</span>
                            <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                        </a>
                    </div>

                    {{-- Direct Project Links List (Dynamic from DB) --}}
                    <div class="project-links-list">
                        @forelse($proyectos as $proyecto)
                            <a href="{{ $proyecto->url_demo ? (str_starts_with($proyecto->url_demo, 'http') ? $proyecto->url_demo : 'https://' . $proyecto->url_demo) : route('portfolio') }}" target="_blank" rel="noopener noreferrer" class="project-link-row">
                                <div class="project-row-left">
                                    <div class="project-pill-icon" style="background: #F8F8F8; color: #0F172A;">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                        </svg>
                                    </div>
                                    <div class="project-row-info">
                                        <span class="project-row-name">{{ $proyecto->titulo }}</span>
                                        <span class="project-row-tag">{{ Str::limit($proyecto->descripcion, 32) }}</span>
                                    </div>
                                </div>
                                <div class="project-row-action">
                                    <span class="project-status-dot" title="Activo"></span>
                                    <svg width="12" height="12" fill="none" stroke="#94A3B8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                                </div>
                            </a>
                        @empty
                            <div style="padding: 18px 10px; text-align: center; color: #94A3B8; font-size: 11.5px;">
                                No hay proyectos publicados activos aún.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Row 1 - Right: Proyectos & Métricas Destacadas Card --}}
                <div class="bento-c-card">
                    <div class="card-top-header">
                        <div class="card-header-left">
                            <div class="card-app-icon" style="background: #0F172A;">
                                <svg width="18" height="18" fill="none" stroke="#FFFFFF" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="card-title-text">Proyectos & Métricas</h3>
                                <p class="card-sub-domain">@yohanblanco</p>
                            </div>
                        </div>
                        <span class="card-follow-pill">+10 Sitios</span>
                    </div>

                    <div class="showcase-2x2-grid">
                        <div class="mini-card-item">
                            <div class="mini-card-header">
                                <span style="font-size: 12px; line-height: 1;">🇺🇸</span>
                                <span class="mini-card-title">Proyectos USA</span>
                            </div>
                            <span class="mini-card-tag">3 en el Exterior</span>
                        </div>
                        <div class="mini-card-item">
                            <div class="mini-card-header">
                                <svg width="12" height="12" fill="none" stroke="#0F172A" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="mini-card-title">Citas & Reservas</span>
                            </div>
                            <span class="mini-card-tag">2 Plataformas</span>
                        </div>
                        <div class="mini-card-item">
                            <div class="mini-card-header">
                                <svg width="12" height="12" fill="none" stroke="#0F172A" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span class="mini-card-title">Sitios Corporativos</span>
                            </div>
                            <span class="mini-card-tag">WordPress & IA</span>
                        </div>
                        <div class="mini-card-item">
                            <div class="mini-card-header">
                                <svg width="12" height="12" fill="none" stroke="#0F172A" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
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
                            <svg width="34" height="34" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                                <rect width="36" height="36" rx="8" fill="#0A66C2"/>
                                <path fill="#FFFFFF" d="M28 28h-4.4v-6.9c0-1.6 0-3.7-2.3-3.7-2.3 0-2.6 1.8-2.6 3.6V28h-4.4V13.8h4.2v1.9h.1c.6-1.1 2-2.3 4.2-2.3 4.5 0 5.3 3 5.3 6.8V28zM10.9 11.9c-1.4 0-2.6-1.2-2.6-2.6s1.2-2.6 2.6-2.6c1.4 0 2.6 1.2 2.6 2.6s-1.2 2.6-2.6 2.6zm2.2 16.1H8.7V13.8h4.4V28z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="social-mini-title">Connect on LinkedIn</div>
                            <div class="social-mini-sub">linkedin.com/in/yohanblaro</div>
                        </div>
                    </a>

                    {{-- Behance & GitHub Split --}}
                    <div class="social-sub-split">
                        <a href="https://www.behance.net/desingkamo" target="_blank" rel="noopener noreferrer" class="social-sub-btn">
                            <div class="sub-btn-left">
                                <div class="sub-btn-icon">
                                    <svg width="22" height="22" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="32" height="32" rx="7" fill="#0057FF"/>
                                        <g transform="translate(4, 4)">
                                            <path fill="#FFFFFF" d="M16.969 16.927a2.561 2.561 0 0 0 1.901.677 2.501 2.501 0 0 0 1.531-.475c.362-.235.636-.584.779-.99h2.585a5.091 5.091 0 0 1-1.9 2.896 5.292 5.292 0 0 1-3.091.88 5.839 5.839 0 0 1-2.284-.433 4.871 4.871 0 0 1-1.723-1.211 5.657 5.657 0 0 1-1.08-1.874 7.057 7.057 0 0 1-.383-2.393c-.005-.8.129-1.595.396-2.349a5.313 5.313 0 0 1 5.088-3.604 4.87 4.87 0 0 1 2.376.563c.661.362 1.231.87 1.668 1.485a6.2 6.2 0 0 1 .943 2.133c.194.821.263 1.666.205 2.508h-7.699c-.063.79.184 1.574.688 2.187ZM6.947 4.084a8.065 8.065 0 0 1 1.928.198 4.29 4.29 0 0 1 1.49.638c.418.303.748.711.958 1.182.241.579.357 1.203.341 1.83a3.506 3.506 0 0 1-.506 1.961 3.726 3.726 0 0 1-1.503 1.287 3.588 3.588 0 0 1 2.027 1.437c.464.747.697 1.615.67 2.494a4.593 4.593 0 0 1-.423 2.032 3.945 3.945 0 0 1-1.163 1.413 5.114 5.114 0 0 1-1.683.807 7.135 7.135 0 0 1-1.928.259H0V4.084h6.947Zm-.235 12.9c.308.004.616-.029.916-.099a2.18 2.18 0 0 0 .766-.332c.228-.158.411-.371.534-.619.142-.317.208-.663.191-1.009a2.08 2.08 0 0 0-.642-1.715 2.618 2.618 0 0 0-1.696-.505h-3.54v4.279h3.471Zm13.635-5.967a2.13 2.13 0 0 0-1.654-.619 2.336 2.336 0 0 0-1.163.259 2.474 2.474 0 0 0-.738.62 2.359 2.359 0 0 0-.396.792c-.074.239-.12.485-.137.734h4.769a3.239 3.239 0 0 0-.679-1.785l-.002-.001Zm-13.813-.648a2.254 2.254 0 0 0 1.423-.433c.399-.355.607-.88.56-1.413a1.916 1.916 0 0 0-.178-.891 1.298 1.298 0 0 0-.495-.533 1.851 1.851 0 0 0-.711-.274 3.966 3.966 0 0 0-.835-.073H3.241v3.631h3.293v-.014ZM21.62 5.122h-5.976v1.527h5.976V5.122Z"/>
                                        </g>
                                    </svg>
                                </div>
                                <span class="sub-btn-name">Behance</span>
                            </div>
                            <svg width="10" height="10" fill="none" stroke="#94A3B8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        <a href="https://github.com/DonKamo-Dev" target="_blank" rel="noopener noreferrer" class="social-sub-btn">
                            <div class="sub-btn-left">
                                <div class="sub-btn-icon">
                                    <svg width="22" height="22" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="32" height="32" rx="7" fill="#0F172A"/>
                                        <g transform="translate(4, 4)">
                                            <path fill="#FFFFFF" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                                        </g>
                                    </svg>
                                </div>
                                <span class="sub-btn-name">GitHub</span>
                            </div>
                            <svg width="10" height="10" fill="none" stroke="#94A3B8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Row 2 - Right: Map Card --}}
                <div class="map-card-container">
                    <div class="map-bg-graphic"></div>
                    <svg class="map-svg-roads" viewBox="0 0 200 120" preserveAspectRatio="none">
                        <path d="M-10,30 Q60,50 120,20 T210,70" stroke="#FFFFFF" stroke-width="8" fill="none"/>
                        <path d="M30,130 Q80,60 160,80 T220,10" stroke="#FFFFFF" stroke-width="6" fill="none"/>
                        <path d="M100,-10 L110,130" stroke="#FFFFFF" stroke-width="5" fill="none"/>
                    </svg>
                    <div class="map-pulsing-pin"></div>
                    <div class="map-location-bubble">
                        <div class="map-bubble-title">Bogotá, Colombia 🇨🇴</div>
                        <div class="map-bubble-sub">UTC-5 (Horario EST / CST)</div>
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
                            Proyectos Remotos & Consultoría
                        </p>
                    </div>
                    <div class="availability-tags-row">
                        <span class="avail-tag-pill">🇺🇸 EE. UU. (USD)</span>
                        <span class="avail-tag-pill">🇨🇴 Colombia</span>
                        <span class="avail-tag-pill">🌍 Global</span>
                    </div>
                </div>

            </div>

        </div>

        {{-- 3. THE 4 TECH & STACK BOXES (High Fidelity Official SVGs) --}}
        <div class="tech-boxes-strip">
            {{-- Box 1: WordPress --}}
            <div class="tech-box-item">
                <div class="tech-box-icon">
                    <svg width="34" height="34" viewBox="0 0 122.52 122.523" xmlns="http://www.w3.org/2000/svg">
                        <g fill="#21759B">
                            <path d="m8.708 61.26c0 20.802 12.089 38.779 29.619 47.298l-25.069-68.686c-2.916 6.536-4.55 13.769-4.55 21.388z"/>
                            <path d="m96.74 58.608c0-6.495-2.333-10.993-4.334-14.494-2.664-4.329-5.161-7.995-5.161-12.324 0-4.831 3.664-9.328 8.825-9.328.233 0 .454.029.681.042-9.35-8.566-21.807-13.796-35.489-13.796-18.36 0-34.513 9.42-43.91 23.688 1.233.037 2.395.063 3.382.063 5.497 0 14.006-.667 14.006-.667 2.833-.167 3.167 3.994.337 4.329 0 0-2.847.335-6.015.501l19.138 56.925 11.501-34.493-8.188-22.434c-2.83-.166-5.511-.501-5.511-.501-2.832-.166-2.5-4.496.332-4.329 0 0 8.679.667 13.843.667 5.496 0 14.006-.667 14.006-.667 2.835-.167 3.168 3.994.337 4.329 0 0-2.853.335-6.015.501l18.992 56.494 5.242-17.517c2.272-7.269 4.001-12.49 4.001-16.989z"/>
                            <path d="m62.184 65.857-15.768 45.819c4.708 1.384 9.687 2.141 14.846 2.141 6.12 0 11.989-1.058 17.452-2.979-.141-.225-.269-.464-.374-.724z"/>
                            <path d="m107.376 36.046c.226 1.674.354 3.471.354 5.404 0 5.333-.996 11.328-3.996 18.824l-16.053 46.413c15.624-9.111 26.133-26.038 26.133-45.426.001-9.137-2.333-17.729-6.438-25.215z"/>
                            <path d="m61.262 0c-33.779 0-61.262 27.481-61.262 61.26 0 33.783 27.483 61.263 61.262 61.263 33.778 0 61.265-27.48 61.265-61.263-.001-33.779-27.487-61.26-61.265-61.26zm0 119.715c-32.23 0-58.453-26.223-58.453-58.455 0-32.23 26.222-58.451 58.453-58.451 32.229 0 58.45 26.221 58.45 58.451 0 32.232-26.221 58.455-58.45 58.455z"/>
                        </g>
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
                    <svg width="32" height="32" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17Z"/>
                        <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24Z"/>
                        <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15Z"/>
                        <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98Z"/>
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
                    <svg width="34" height="34" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="metaGrad2" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#0064E0"/>
                                <stop offset="50%" stop-color="#0081FB"/>
                                <stop offset="100%" stop-color="#0081FB"/>
                            </linearGradient>
                        </defs>
                        <path fill="url(#metaGrad2)" d="M18 15.7c-2.3-3.6-5.2-5.7-8.7-5.7-5 0-9.3 4-9.3 9.4 0 5.5 4.3 9.6 9.3 9.6 3.7 0 6.6-2.2 8.7-5.8 2.1 3.6 5 5.8 8.7 5.8 5 0 9.3-4.1 9.3-9.6 0-5.4-4.3-9.4-9.3-9.4-3.5 0-6.4 2.1-8.7 5.7zm-8.7 9.8c-3 0-5.5-2.3-5.5-5.9 0-3.5 2.5-5.7 5.5-5.7 2.4 0 4.6 1.8 6.5 4.8-1.9 4-4.1 6.8-6.5 6.8zm17.4 0c-2.4 0-4.6-2.8-6.5-6.8 1.9-3 4.1-4.8 6.5-4.8 3 0 5.5 2.2 5.5 5.7 0 3.6-2.5 5.9-5.5 5.9z"/>
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
                    <svg width="34" height="34" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="aiSparkGrad2" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#9333EA"/>
                                <stop offset="100%" stop-color="#E11D48"/>
                            </linearGradient>
                        </defs>
                        <rect width="36" height="36" rx="8" fill="#FAF5FF" stroke="#E9D5FF" stroke-width="1"/>
                        <path fill="url(#aiSparkGrad2)" d="M18 7l2.4 6.8L27 16.2l-6.6 2.4L18 25.4l-2.4-6.8L9 16.2l6.6-2.4L18 7zm7.5 13.5l1.2 3.4 3.3 1.2-3.3 1.2-1.2 3.4-1.2-3.4-3.3-1.2 3.3-1.2 1.2-3.4z"/>
                    </svg>
                </div>
                <div class="tech-box-content">
                    <span class="tech-box-title">AI Assisted</span>
                    <span class="tech-box-subtitle">Prompting & Speed</span>
                </div>
            </div>
        </div>

        {{-- Bottom Section: How Can I Help? --}}
        <div>
            <h2 class="section-separator-title">¿Cómo puedo ayudarte? ⚡</h2>
        </div>

        <div class="bottom-cards-grid">
            {{-- Dark Value Proposition Card --}}
            <div class="card-value-dark">
                <p class="card-value-text">
                    Si buscas un <span>WordPress Developer & Especialista Digital</span> que trabaje con la velocidad y precisión de la IA para construir plataformas y maximizar tus conversiones, trabajemos juntos.
                </p>
                <a href="https://wa.me/573113894136" target="_blank" rel="noopener noreferrer" class="btn-work-together">
                    <span>Hablemos por WhatsApp</span>
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2z"/>
                    </svg>
                </a>
            </div>

            {{-- Pastel Email Direct Card --}}
            <button type="button" id="copy-email-button" class="card-email-pastel">
                <div class="email-arrow-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 17L17 7M17 7H7M17 7V17"/>
                    </svg>
                </div>
                <div>
                    <p class="email-big-text">yohanblaro18@gmail.com</p>
                    <p id="copy-hint-text" class="email-copy-hint">Haz clic para copiar email 📋</p>
                </div>
            </button>
        </div>

    </div>

    {{-- Toast Notification --}}
    <div id="toast-copied">
        <svg width="14" height="14" fill="none" stroke="#10B981" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        <span>Email copiado al portapapeles</span>
    </div>

    {{-- Interactive Script --}}
    <script>
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
                        if (hint) hint.textContent = 'Haz clic para copiar email 📋';
                    }, 2500);
                }
            });
        }

        document.getElementById('copy-email-button')?.addEventListener('click', copyEmailToClipboard);
    </script>
</div>
