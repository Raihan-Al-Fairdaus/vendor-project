# -*- coding: utf-8 -*-
"""
HTML & CSS & JS UI Template for DNA Developer Central Portal
Uses PyWebView (native Edge WebView2) to render modern web UI matching DNA Advertising web portal.
"""

from assets_data import LOGO_BASE64, BILLBOARD_BASE64

def get_html_content() -> str:
    return f"""<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DNA Advertising - Developer Central Security Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {{
            --navy-dark: #0f1f38;
            --navy-sidebar: #1b3a60;
            --navy-header: #1b3a60;
            --navy-hover: #234b7c;
            --navy-active: #152d4a;
            --gold-accent: #f59e0b;
            --gold-hover: #d97706;
            --gold-light: #fef3c7;
            --bg-page: #edf2f7;
            --bg-card: #ffffff;
            --border-color: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --success: #10b981;
            --success-light: #dcfce7;
            --success-text: #15803d;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --danger-text: #b91c1c;
            --info: #0284c7;
            --info-light: #e0f2fe;
            --info-text: #0369a1;
        }}

        * {{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            user-select: none;
        }}

        input, textarea {{
            user-select: text;
        }}

        body, html {{
            width: 100%;
            height: 100%;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-main);
            overflow: hidden;
        }}

        /* Scrollbars */
        ::-webkit-scrollbar {{
            width: 7px;
            height: 7px;
        }}
        ::-webkit-scrollbar-track {{
            background: #f1f5f9;
        }}
        ::-webkit-scrollbar-thumb {{
            background: #cbd5e1;
            border-radius: 4px;
        }}
        ::-webkit-scrollbar-thumb:hover {{
            background: #94a3b8;
        }}

        /* ==============================================================
           1. LOGIN VIEW (IDENTIK DENGAN WEB LOGIN SCREEN)
           ============================================================== */
        #login-view {{
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--navy-dark);
            z-index: 1000;
        }}

        .login-bg-pattern {{
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.05) 1.5px, transparent 1.5px);
            background-size: 28px 28px;
            pointer-events: none;
        }}

        .login-bg-circle {{
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.03);
            pointer-events: none;
        }}
        .circle-1 {{ width: 650px; height: 650px; left: -160px; top: -160px; }}
        .circle-2 {{ width: 450px; height: 450px; right: -120px; bottom: -120px; }}

        .login-container {{
            width: min(1080px, 92vw);
            height: min(650px, 90vh);
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.35), 0 2px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            overflow: hidden;
            position: relative;
            z-index: 5;
            animation: fadeInScale 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }}

        @keyframes fadeInScale {{
            from {{ opacity: 0; transform: scale(0.96); }}
            to {{ opacity: 1; transform: scale(1); }}
        }}

        /* Left Panel */
        .login-left {{
            width: 44%;
            background-image:
                linear-gradient(
                    160deg,
                    rgba(22, 48, 86, 0.94) 0%,
                    rgba(30, 58, 104, 0.86) 50%,
                    rgba(15, 31, 56, 0.96) 100%
                ),
                url('{BILLBOARD_BASE64}');
            background-size: cover;
            background-position: center;
            color: #ffffff;
            padding: 44px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }}

        .left-brand {{
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }}

        .left-logo {{
            height: 52px;
            object-fit: contain;
            margin-bottom: 14px;
            filter: drop-shadow(0 4px 10px rgba(0,0,0,0.25));
        }}

        .left-brand h1 {{
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.1;
        }}

        .left-brand h1 span {{
            color: #ef4444;
        }}

        .left-brand p {{
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 5px;
        }}

        .gold-line {{
            width: 46px;
            height: 3px;
            background: var(--gold-accent);
            border-radius: 2px;
            margin-top: 16px;
        }}

        .left-features {{
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin: 24px 0;
        }}

        .feat-item {{
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }}

        .feat-icon {{
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-accent);
            flex-shrink: 0;
        }}

        .feat-text h4 {{
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 2px;
        }}

        .feat-text p {{
            font-size: 11.5px;
            color: rgba(255, 255, 255, 0.65);
            line-height: 1.4;
        }}

        .left-copy {{
            font-size: 11px;
            color: rgba(255, 255, 255, 0.45);
        }}

        /* Right Panel */
        .login-right {{
            width: 56%;
            padding: 44px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }}

        .security-pill {{
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e0f2fe;
            color: #0369a1;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 14px;
            align-self: flex-start;
        }}

        .login-heading h2 {{
            font-size: 26px;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.02em;
        }}

        .login-heading p {{
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
            margin-bottom: 22px;
        }}

        .login-alert {{
            display: none;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 18px;
            align-items: center;
            gap: 10px;
        }}
        .login-alert.show {{
            display: flex;
        }}
        .login-alert.error {{
            background: var(--danger-light);
            color: var(--danger-text);
            border-left: 4px solid var(--danger);
        }}

        .input-group {{
            margin-bottom: 18px;
        }}

        .input-label {{
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 6px;
        }}

        .input-box {{
            position: relative;
            display: flex;
            align-items: center;
        }}

        .input-box svg.field-icon {{
            position: absolute;
            left: 14px;
            width: 17px;
            height: 17px;
            color: #94a3b8;
            pointer-events: none;
        }}

        .input-field {{
            width: 100%;
            height: 44px;
            padding: 0 42px 0 42px;
            font-size: 13.5px;
            font-family: inherit;
            color: var(--text-main);
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            outline: none;
            transition: all 0.2s ease;
        }}

        .input-field:focus {{
            border-color: var(--navy-sidebar);
            box-shadow: 0 0 0 3px rgba(27, 58, 96, 0.12);
        }}

        .eye-btn {{
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
        }}
        .eye-btn:hover {{
            color: var(--navy-sidebar);
        }}

        .btn-submit-login {{
            width: 100%;
            height: 46px;
            background: var(--navy-sidebar);
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 700;
            font-family: inherit;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
            transition: background 0.2s, transform 0.1s;
        }}
        .btn-submit-login:hover {{
            background: var(--navy-hover);
        }}
        .btn-submit-login:active {{
            transform: scale(0.99);
        }}
        .btn-submit-login:disabled {{
            opacity: 0.7;
            cursor: wait;
        }}

        .login-bottom-links {{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 18px;
            font-size: 12px;
        }}

        .server-link {{
            color: var(--text-muted);
            text-decoration: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }}
        .server-link:hover {{
            color: var(--navy-sidebar);
        }}

        /* ==============================================================
           2. MAIN DASHBOARD VIEW (IDENTIK DENGAN WEB ADMIN DASHBOARD)
           ============================================================== */
        #dashboard-view {{
            display: none;
            width: 100vw;
            height: 100vh;
            display: flex;
        }}

        /* Sidebar (Fixed 220px Navy) */
        .sidebar {{
            width: 220px;
            min-width: 220px;
            height: 100vh;
            background: var(--navy-sidebar);
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            z-index: 50;
            flex-shrink: 0;
        }}

        .sidebar-header {{
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }}

        .sidebar-brand {{
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 17px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.01em;
        }}

        .sidebar-brand img {{
            height: 28px;
            object-fit: contain;
        }}

        .sidebar-brand span {{
            color: #ef4444;
        }}

        .sidebar-subtitle {{
            font-size: 9px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.4);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-top: 3px;
        }}

        .nav-section-label {{
            padding: 16px 20px 6px 20px;
            font-size: 9.5px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.35);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }}

        .sidebar-nav {{
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 2px;
            padding: 0 10px;
            overflow-y: auto;
        }}

        .nav-item {{
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease;
            position: relative;
        }}

        .nav-item:hover {{
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }}

        .nav-item.active {{
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border-left: 3px solid var(--gold-accent);
            padding-left: 11px;
        }}

        .nav-item svg {{
            width: 17px;
            height: 17px;
            color: inherit;
            flex-shrink: 0;
        }}

        .sidebar-footer {{
            padding: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.12);
        }}

        .profile-card {{
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }}

        .profile-avatar {{
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #3b82f6;
            color: #ffffff;
            font-size: 14px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }}

        .profile-info {{
            overflow: hidden;
        }}

        .profile-name {{
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }}

        .profile-role {{
            display: inline-block;
            font-size: 9.5px;
            font-weight: 600;
            color: #38bdf8;
            letter-spacing: 0.04em;
        }}

        .logout-btn {{
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-size: 12px;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }}
        .logout-btn:hover {{
            background: var(--danger);
            border-color: var(--danger);
        }}

        /* Main Content Container */
        .main-content {{
            flex: 1;
            height: 100vh;
            overflow-y: auto;
            background: var(--bg-page);
            display: flex;
            flex-direction: column;
        }}

        /* Top Header Bar (Navy) */
        .admin-page-header {{
            background-color: var(--navy-header);
            padding: 16px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            border-bottom: 2px solid var(--gold-accent);
        }}

        .header-title-box h1 {{
            color: #ffffff;
            font-size: 18px;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.01em;
        }}

        .header-title-box p {{
            color: rgba(255, 255, 255, 0.65);
            font-size: 11.5px;
            margin-top: 3px;
        }}

        .header-right-box {{
            display: flex;
            align-items: center;
            gap: 14px;
        }}

        .clock-badge {{
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 6px 14px;
            border-radius: 20px;
            color: var(--gold-accent);
            font-size: 11.5px;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
        }}

        .pulse-dot {{
            width: 7px;
            height: 7px;
            background: #10b981;
            border-radius: 50%;
            box-shadow: 0 0 8px #10b981;
            animation: pulseAnim 1.5s infinite;
        }}

        @keyframes pulseAnim {{
            0% {{ opacity: 0.4; transform: scale(0.9); }}
            50% {{ opacity: 1; transform: scale(1.15); }}
            100% {{ opacity: 0.4; transform: scale(0.9); }}
        }}

        .server-pill {{
            background: var(--success-light);
            color: var(--success-text);
            border: 1px solid #86efac;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 10.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }}

        /* Stats Grid */
        .stats-grid {{
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
            padding: 20px 28px 0 28px;
            flex-shrink: 0;
        }}

        .stat-card {{
            background: #ffffff;
            border-radius: 10px;
            padding: 14px 16px;
            border-top: 3.5px solid var(--navy-sidebar);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
            border-left: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }}

        .stat-card-header {{
            display: flex;
            align-items: center;
            justify-content: space-between;
        }}

        .stat-card-title {{
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }}

        .stat-card-icon {{
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }}

        .stat-card-val {{
            font-size: 24px;
            font-weight: 800;
            color: var(--text-main);
            margin-top: 6px;
            line-height: 1.1;
        }}

        /* Content Body Card */
        .content-body {{
            flex: 1;
            padding: 16px 28px 24px 28px;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }}

        .panel-card {{
            background: #ffffff;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            box-shadow: 0 3px 6px -1px rgba(0, 0, 0, 0.04);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
            overflow: hidden;
        }}

        /* Toolbar */
        .panel-toolbar {{
            padding: 14px 18px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            background: #fafbfc;
            flex-wrap: wrap;
        }}

        .toolbar-left, .toolbar-right {{
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }}

        .search-box {{
            position: relative;
            display: flex;
            align-items: center;
        }}

        .search-box svg {{
            position: absolute;
            left: 10px;
            width: 14px;
            height: 14px;
            color: #94a3b8;
        }}

        .search-input {{
            height: 34px;
            padding: 0 10px 0 32px;
            font-size: 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            outline: none;
            width: 180px;
            background: #ffffff;
            color: var(--text-main);
        }}
        .search-input:focus {{
            border-color: var(--navy-sidebar);
        }}

        .select-filter {{
            height: 34px;
            padding: 0 24px 0 10px;
            font-size: 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background: #ffffff;
            color: var(--text-main);
            outline: none;
            cursor: pointer;
        }}

        /* Buttons */
        .btn {{
            height: 34px;
            padding: 0 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            border: none;
            transition: all 0.15s ease;
            white-space: nowrap;
        }}

        .btn svg {{
            width: 14px;
            height: 14px;
        }}

        .btn-primary {{
            background: var(--navy-sidebar);
            color: #ffffff;
        }}
        .btn-primary:hover {{
            background: var(--navy-hover);
        }}

        .btn-gold {{
            background: var(--gold-accent);
            color: #ffffff;
        }}
        .btn-gold:hover {{
            background: var(--gold-hover);
        }}

        .btn-success {{
            background: var(--success);
            color: #ffffff;
        }}
        .btn-success:hover {{
            background: #059669;
        }}

        .btn-danger {{
            background: var(--danger);
            color: #ffffff;
        }}
        .btn-danger:hover {{
            background: #dc2626;
        }}

        .btn-secondary {{
            background: #ffffff;
            color: var(--text-main);
            border: 1px solid var(--border-color);
        }}
        .btn-secondary:hover {{
            background: #f1f5f9;
        }}

        .btn-danger-outline {{
            background: #ffffff;
            color: var(--danger);
            border: 1px solid #fecaca;
        }}
        .btn-danger-outline:hover {{
            background: var(--danger-light);
        }}

        .auto-chk-label {{
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            color: var(--text-muted);
            cursor: pointer;
        }}

        /* Table Styling */
        .table-wrap {{
            flex: 1;
            overflow: auto;
            position: relative;
        }}

        table.data-table {{
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
            text-align: left;
        }}

        table.data-table th {{
            position: sticky;
            top: 0;
            background: #1b3a60;
            color: #ffffff;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 10px 14px;
            border-bottom: 2px solid var(--gold-accent);
            z-index: 10;
            white-space: nowrap;
        }}

        table.data-table td {{
            padding: 11px 14px;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-main);
            vertical-align: middle;
        }}

        table.data-table tbody tr {{
            transition: background 0.1s ease;
            cursor: pointer;
        }}

        table.data-table tbody tr:hover {{
            background: #f8fafc;
        }}

        table.data-table tbody tr.selected {{
            background: #eff6ff !important;
        }}

        /* Badges */
        .badge {{
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }}
        .badge-success {{
            background: var(--success-light);
            color: var(--success-text);
            border: 1px solid #86efac;
        }}
        .badge-danger {{
            background: var(--danger-light);
            color: var(--danger-text);
            border: 1px solid #fca5a5;
        }}
        .badge-warning {{
            background: var(--gold-light);
            color: #b45309;
            border: 1px solid #fde68a;
        }}

        .pwd-badge {{
            font-family: 'JetBrains Mono', monospace;
            background: #f1f5f9;
            padding: 2px 7px;
            border-radius: 4px;
            color: #0f172a;
            font-weight: 600;
            font-size: 11.5px;
            border: 1px solid #e2e8f0;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }}

        .copy-icon-btn {{
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            display: inline-flex;
            padding: 2px;
        }}
        .copy-icon-btn:hover {{
            color: var(--navy-sidebar);
        }}

        /* ==============================================================
           3. MODAL POPUPS (RESET PWD, ADD ADMIN, LOG DETAIL, SETTINGS)
           ============================================================== */
        .modal-overlay {{
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(4px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }}
        .modal-overlay.show {{
            display: flex;
        }}

        .modal-card {{
            background: #ffffff;
            width: min(500px, 94vw);
            border-radius: 14px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            animation: modalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }}

        @keyframes modalPop {{
            from {{ opacity: 0; transform: scale(0.95); }}
            to {{ opacity: 1; transform: scale(1); }}
        }}

        .modal-header {{
            background: var(--navy-sidebar);
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #ffffff;
            border-bottom: 2px solid var(--gold-accent);
        }}

        .modal-header h3 {{
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }}

        .modal-close-btn {{
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            font-size: 18px;
            cursor: pointer;
            padding: 4px;
        }}
        .modal-close-btn:hover {{
            color: #ffffff;
        }}

        .modal-body {{
            padding: 22px;
        }}

        .modal-footer {{
            padding: 14px 22px;
            background: #f8fafc;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }}

        /* Toast Notifications */
        #toast-container {{
            position: fixed;
            top: 24px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            pointer-events: none;
        }}

        .toast {{
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            pointer-events: auto;
            animation: toastSlideDown 0.3s ease;
        }}
        .toast.success {{
            border-left: 4px solid var(--success);
            color: var(--success-text);
        }}
        .toast.error {{
            border-left: 4px solid var(--danger);
            color: var(--danger-text);
        }}

        @keyframes toastSlideDown {{
            from {{ opacity: 0; transform: translateY(-15px); }}
            to {{ opacity: 1; transform: translateY(0); }}
        }}
    </style>
</head>
<body>

    <!-- TOAST CONTAINER -->
    <div id="toast-container"></div>

    <!-- ==============================================================
         1. LOGIN VIEW
         ============================================================== -->
    <div id="login-view">
        <div class="login-bg-pattern"></div>
        <div class="login-bg-circle circle-1"></div>
        <div class="login-bg-circle circle-2"></div>

        <div class="login-container">
            <!-- LEFT PANEL -->
            <div class="login-left">
                <div class="left-brand">
                    <img src="{LOGO_BASE64}" class="left-logo" alt="DNA Advertising">
                    <h1><span>DNA</span> Advertising</h1>
                    <p>Developer Central Security Portal</p>
                    <div class="gold-line"></div>
                </div>

                <div class="left-features">
                    <div class="feat-item">
                        <div class="feat-icon">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <div class="feat-text">
                            <h4>Super Developer Access</h4>
                            <p>Akses tingkat tertinggi untuk audit keamanan & kredensial.</p>
                        </div>
                    </div>

                    <div class="feat-item">
                        <div class="feat-icon">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div class="feat-text">
                            <h4>Realtime Login Guard</h4>
                            <p>Pantau siapa & kapan admin masuk, deteksi sandi & blokir penyusup.</p>
                        </div>
                    </div>
                </div>

                <div class="left-copy">
                    &copy; 2026 DNA Advertising. All Rights Reserved. Desktop Edition.
                </div>
            </div>

            <!-- RIGHT PANEL -->
            <div class="login-right">
                <div class="security-pill">
                    <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                    DNA DEVELOPER SECURITY ACCESS
                </div>

                <div class="login-heading">
                    <h2>Developer Login</h2>
                    <p>Silakan masuk menggunakan kredensial khusus Developer.</p>
                </div>

                <div id="login-alert" class="login-alert error">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span id="login-alert-msg">Email atau password salah!</span>
                </div>

                <form id="login-form" onsubmit="handleLoginSubmit(event)">
                    <div class="input-group">
                        <label class="input-label">Email Developer</label>
                        <div class="input-box">
                            <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            <input type="email" id="login-email" class="input-field" value="developer@dna-advertising.com" placeholder="Email Developer" required autofocus>
                        </div>
                    </div>

                    <div class="input-group">
                        <label class="input-label">Password Developer</label>
                        <div class="input-box">
                            <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <input type="password" id="login-password" class="input-field" value="DevDNA@2026!" placeholder="Password Developer" required>
                            <button type="button" class="eye-btn" onclick="togglePasswordVisibility('login-password', this)">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="btn-login" class="btn-submit-login">
                        <span>Masuk ke Developer Central</span>
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </button>

                    <div class="login-bottom-links">
                        <span style="color:#94a3b8; font-size:11.5px;">Terkoneksi ke Database DNA Vendor</span>
                        <a class="server-link" onclick="openServerModal()">
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                            Server Config
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==============================================================
         2. DASHBOARD VIEW
         ============================================================== -->
    <div id="dashboard-view">
        <!-- SIDEBAR (NAVY) -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <img src="{LOGO_BASE64}" alt="DNA">
                    <div><span>DNA</span> Portal</div>
                </div>
                <div class="sidebar-subtitle">DEVELOPER CONTROL PANEL</div>
            </div>

            <div class="nav-section-label">MAIN MONITORING</div>
            <nav class="sidebar-nav">
                <a class="nav-item active" id="nav-logs" onclick="switchNavTab('logs')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>Riwayat Login Realtime</span>
                </a>
                <a class="nav-item" id="nav-admins" onclick="switchNavTab('admins')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span>Manajemen Akun Admin</span>
                </a>

                <div class="nav-section-label">SYSTEM & CONFIG</div>
                <a class="nav-item" id="nav-settings" onclick="switchNavTab('settings')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg>
                    <span>Pengaturan Server</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="profile-card">
                    <div class="profile-avatar">D</div>
                    <div class="profile-info">
                        <span class="profile-name" id="sidebar-dev-email">developer@dna...</span>
                        <span class="profile-role">SUPER DEVELOPER</span>
                    </div>
                </div>
                <button class="logout-btn" onclick="handleLogout()">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    <span>Keluar Portal</span>
                </button>
            </div>
        </aside>

        <!-- MAIN AREA -->
        <main class="main-content">
            <!-- TOP HEADER -->
            <header class="admin-page-header">
                <div class="header-title-box">
                    <h1 id="page-title">Riwayat Login Realtime & Deteksi Sandi</h1>
                    <p id="page-subtitle">Pemantauan riwayat login admin, jam realtime, kredensial masuk & audit siber.</p>
                </div>

                <div class="header-right-box">
                    <div class="clock-badge">
                        <span class="pulse-dot"></span>
                        <span id="live-clock">🕒 Memuat waktu...</span>
                    </div>
                    <div class="server-pill">
                        <span>●</span> ONLINE VERCEL
                    </div>
                </div>
            </header>

            <!-- 5 STATS CARDS -->
            <section class="stats-grid">
                <div class="stat-card" style="border-top-color: #1b3a60;">
                    <div class="stat-card-header">
                        <span class="stat-card-title">Total Admin Terdaftar</span>
                        <span class="stat-card-icon" style="background: rgba(27,58,96,0.1); color: #1b3a60;">👥</span>
                    </div>
                    <div class="stat-card-val" id="stat-admins">0</div>
                </div>

                <div class="stat-card" style="border-top-color: #0284c7;">
                    <div class="stat-card-header">
                        <span class="stat-card-title">Total Riwayat Login</span>
                        <span class="stat-card-icon" style="background: rgba(2,132,199,0.1); color: #0284c7;">📋</span>
                    </div>
                    <div class="stat-card-val" id="stat-logs">0</div>
                </div>

                <div class="stat-card" style="border-top-color: #10b981;">
                    <div class="stat-card-header">
                        <span class="stat-card-title">Login Sukses</span>
                        <span class="stat-card-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">🟢</span>
                    </div>
                    <div class="stat-card-val" id="stat-success" style="color: #10b981;">0</div>
                </div>

                <div class="stat-card" style="border-top-color: #ef4444;">
                    <div class="stat-card-header">
                        <span class="stat-card-title">Login Gagal / Ditolak</span>
                        <span class="stat-card-icon" style="background: rgba(239,68,68,0.1); color: #ef4444;">🔴</span>
                    </div>
                    <div class="stat-card-val" id="stat-failed" style="color: #ef4444;">0</div>
                </div>

                <div class="stat-card" style="border-top-color: #f59e0b;">
                    <div class="stat-card-header">
                        <span class="stat-card-title">Aktivitas Hari Ini</span>
                        <span class="stat-card-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">📅</span>
                    </div>
                    <div class="stat-card-val" id="stat-today" style="color: #d97706;">0</div>
                </div>
            </section>

            <!-- MAIN PANEL CONTENT -->
            <div class="content-body">
                <!-- PANEL 1: LOGS -->
                <div class="panel-card" id="panel-logs">
                    <div class="panel-toolbar">
                        <div class="toolbar-left">
                            <div class="search-box">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" id="log-search-input" class="search-input" placeholder="Cari email / nama / IP..." onkeyup="if(event.key==='Enter') fetchLogsData()">
                            </div>

                            <select id="log-status-select" class="select-filter" onchange="fetchLogsData()">
                                <option value="Semua">Semua Status</option>
                                <option value="success">Sukses Saja</option>
                                <option value="failed">Gagal / Ditolak</option>
                            </select>

                            <button class="btn btn-secondary" onclick="fetchLogsData()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <span>Cari</span>
                            </button>

                            <button class="btn btn-primary" onclick="fetchLogsData()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                                <span>Refresh</span>
                            </button>

                            <button class="btn btn-secondary" id="btn-toggle-log-pwd" onclick="toggleLogPasswords()">
                                <span id="log-pwd-eye-icon">👁️</span>
                                <span id="log-pwd-eye-text">Sembunyikan Sandi</span>
                            </button>

                            <label class="auto-chk-label">
                                <input type="checkbox" id="chk-auto-refresh" checked onchange="toggleAutoRefresh(this.checked)">
                                <span>Auto-refresh (10 dtk)</span>
                            </label>
                        </div>

                        <div class="toolbar-right">
                            <button class="btn btn-danger-outline" onclick="openClearLogsModal()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                <span>Bersihkan Riwayat</span>
                            </button>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px; text-align: center;">ID</th>
                                    <th style="width: 170px;">Waktu & Jam Realtime</th>
                                    <th style="width: 210px;">Email Login</th>
                                    <th style="width: 150px;">Nama Admin / HRD</th>
                                    <th style="width: 180px;">Sandi Digunakan</th>
                                    <th style="width: 100px; text-align: center;">Status</th>
                                    <th style="width: 120px;">IP Address</th>
                                    <th>Keterangan / Pesan</th>
                                </tr>
                            </thead>
                            <tbody id="logs-tbody">
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 40px; color: #94a3b8;">
                                        Memuat riwayat login realtime...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PANEL 2: ADMINS -->
                <div class="panel-card" id="panel-admins" style="display: none;">
                    <div class="panel-toolbar">
                        <div class="toolbar-left">
                            <button class="btn btn-primary" onclick="openAddAdminModal()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                <span>Tambah Admin Baru</span>
                            </button>

                            <button class="btn btn-gold" onclick="openResetPasswordModal()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                <span>Ganti / Reset Sandi</span>
                            </button>

                            <button class="btn btn-secondary" onclick="copySelectedAdminCredentials()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                <span>Salin Akun & Sandi</span>
                            </button>

                            <button class="btn btn-secondary" style="color: #b45309;" onclick="toggleBlockSelectedAdmin()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                <span>Blokir / Buka Blokir</span>
                            </button>

                            <button class="btn btn-danger-outline" onclick="deleteSelectedAdmin()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                <span>Tendang / Hapus Akun</span>
                            </button>

                            <button class="btn btn-secondary" id="btn-toggle-adm-pwd" onclick="toggleAdminPasswords()">
                                <span id="adm-pwd-eye-icon">👁️</span>
                                <span id="adm-pwd-eye-text">Sembunyikan Sandi</span>
                            </button>
                        </div>

                        <div class="toolbar-right">
                            <button class="btn btn-secondary" onclick="fetchAdminsData()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                                <span>Refresh</span>
                            </button>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px; text-align: center;">ID</th>
                                    <th style="width: 180px;">Nama Admin / HRD</th>
                                    <th style="width: 220px;">Email Login</th>
                                    <th style="width: 170px;">Sandi Akun (Tersimpan)</th>
                                    <th style="width: 110px; text-align: center;">Status Akun</th>
                                    <th style="width: 150px;">Tanggal Dibuat</th>
                                    <th style="width: 160px;">Terakhir Login</th>
                                    <th>IP Terakhir</th>
                                </tr>
                            </thead>
                            <tbody id="admins-tbody">
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 40px; color: #94a3b8;">
                                        Memuat data admin...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PANEL 3: SETTINGS -->
                <div class="panel-card" id="panel-settings" style="display: none; padding: 26px;">
                    <div style="max-width: 650px;">
                        <h2 style="font-size: 18px; font-weight: 800; color: var(--navy-sidebar); margin-bottom: 6px;">⚙️ Konfigurasi Server DNA Developer</h2>
                        <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 24px;">Atur alamat server backend API DNA Advertising untuk sinkronisasi database online.</p>

                        <div class="input-group">
                            <label class="input-label">URL Server Backend API (Production Vercel / Localhost):</label>
                            <input type="text" id="settings-server-url" class="input-field" style="padding-left: 14px; font-family: monospace;">
                            <span style="font-size: 11px; color: #94a3b8; margin-top: 4px; display: block;">Default: https://dna-vendor.vercel.app</span>
                        </div>

                        <div class="input-group">
                            <label class="input-label">Developer API Secret Key:</label>
                            <input type="text" id="settings-secret-key" class="input-field" style="padding-left: 14px; font-family: monospace;" readonly value="dna_dev_sec_2026_secret_key_dna_portal">
                        </div>

                        <div style="display: flex; gap: 10px; margin-top: 20px;">
                            <button class="btn btn-primary" onclick="saveServerSettings()">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                <span>Simpan Pengaturan</span>
                            </button>
                            <button class="btn btn-secondary" onclick="testServerConnection()">
                                <span>⚡ Uji Koneksi Server</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- ==============================================================
         MODALS
         ============================================================== -->
    <!-- MODAL: DETAIL LOG -->
    <div id="modal-log-detail" class="modal-overlay" onclick="if(event.target===this) closeModal('modal-log-detail')">
        <div class="modal-card">
            <div class="modal-header">
                <h3>🔍 Detail Riwayat Login</h3>
                <button class="modal-close-btn" onclick="closeModal('modal-log-detail')">&times;</button>
            </div>
            <div class="modal-body" id="modal-log-body">
                <!-- Injected via JS -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="copyDetailLogPassword()">📋 Salin Sandi</button>
                <button class="btn btn-primary" onclick="closeModal('modal-log-detail')">Tutup</button>
            </div>
        </div>
    </div>

    <!-- MODAL: RESET PASSWORD -->
    <div id="modal-reset-pwd" class="modal-overlay" onclick="if(event.target===this) closeModal('modal-reset-pwd')">
        <div class="modal-card">
            <div class="modal-header" style="background: #b45309;">
                <h3>🔑 Ganti / Reset Password Admin</h3>
                <button class="modal-close-btn" onclick="closeModal('modal-reset-pwd')">&times;</button>
            </div>
            <div class="modal-body">
                <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 14px;">
                    Target: <strong id="reset-target-name" style="color: var(--text-main);"></strong> (<span id="reset-target-email"></span>)
                </p>

                <div class="input-group">
                    <label class="input-label">Password Baru (Min 6 Karakter):</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="reset-new-pwd-input" class="input-field" style="padding-left: 14px; font-family: monospace;">
                        <button class="btn btn-secondary" onclick="generateRandomPassword('reset-new-pwd-input')">🎲 Acak</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('modal-reset-pwd')">Batal</button>
                <button class="btn btn-gold" onclick="submitResetPassword()">Simpan Password Baru</button>
            </div>
        </div>
    </div>

    <!-- MODAL: ADD ADMIN -->
    <div id="modal-add-admin" class="modal-overlay" onclick="if(event.target===this) closeModal('modal-add-admin')">
        <div class="modal-card">
            <div class="modal-header">
                <h3>➕ Tambah Akun Admin Baru</h3>
                <button class="modal-close-btn" onclick="closeModal('modal-add-admin')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <label class="input-label">Nama Lengkap Admin / HRD:</label>
                    <input type="text" id="add-adm-name" class="input-field" style="padding-left: 14px;" placeholder="Contoh: HRD DNA Advertising">
                </div>
                <div class="input-group">
                    <label class="input-label">Email Login:</label>
                    <input type="email" id="add-adm-email" class="input-field" style="padding-left: 14px;" placeholder="nama@dnajayagroup.co.id">
                </div>
                <div class="input-group">
                    <label class="input-label">Password Awal:</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="add-adm-pwd" class="input-field" style="padding-left: 14px; font-family: monospace;" placeholder="Password awal">
                        <button class="btn btn-secondary" onclick="generateRandomPassword('add-adm-pwd')">🎲 Acak</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('modal-add-admin')">Batal</button>
                <button class="btn btn-primary" onclick="submitAddAdmin()">Simpan Admin Baru</button>
            </div>
        </div>
    </div>

    <!-- MODAL: SERVER CONFIG IN LOGIN -->
    <div id="modal-server-login" class="modal-overlay" onclick="if(event.target===this) closeModal('modal-server-login')">
        <div class="modal-card">
            <div class="modal-header">
                <h3>⚙️ Pengaturan URL Server API</h3>
                <button class="modal-close-btn" onclick="closeModal('modal-server-login')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <label class="input-label">URL Server Backend (Vercel atau Localhost):</label>
                    <input type="text" id="modal-login-server-url" class="input-field" style="padding-left: 14px; font-family: monospace;">
                    <span style="font-size: 11px; color: #94a3b8; margin-top: 4px; display: block;">Default: https://dna-vendor.vercel.app</span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('modal-server-login')">Batal</button>
                <button class="btn btn-primary" onclick="saveLoginServerSettings()">Simpan</button>
            </div>
        </div>
    </div>

    <!-- ==============================================================
         JAVASCRIPT LOGIC
         ============================================================== -->
    <script>
        // State
        let currentAuthToken = null;
        let currentDevEmail = null;
        let serverUrl = "https://dna-vendor.vercel.app";
        let autoRefreshInterval = null;
        let showLogPasswords = false;
        let showAdminPasswords = false;
        let cachedLogsData = [];
        let cachedAdminsData = [];
        let selectedAdminId = null;
        let activeDetailLog = null;

        // Initialize on load
        window.addEventListener('DOMContentLoaded', async () => {{
            startLiveClock();
            try {{
                if (window.pywebview && window.pywebview.api) {{
                    const cfg = await window.pywebview.api.get_config();
                    if (cfg && cfg.server_url) {{
                        serverUrl = cfg.server_url;
                        document.getElementById('settings-server-url').value = serverUrl;
                    }}
                    if (cfg && cfg.remember_email) {{
                        document.getElementById('login-email').value = cfg.remember_email;
                    }}
                }}
            }} catch(e) {{
                console.log("Config load fallback", e);
            }}
        }});

        // Live Clock
        function startLiveClock() {{
            const clockEl = document.getElementById('live-clock');
            const hariNames = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const bulanNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];

            function update() {{
                const now = new Date();
                const hari = hariNames[now.getDay()];
                const tgl = String(now.getDate()).padStart(2, '0');
                const bln = bulanNames[now.getMonth()];
                const thn = now.getFullYear();
                const jam = String(now.getHours()).padStart(2, '0');
                const mnt = String(now.getMinutes()).padStart(2, '0');
                const dtk = String(now.getSeconds()).padStart(2, '0');

                if (clockEl) {{
                    clockEl.innerText = `${{hari}}, ${{tgl}} ${{bln}} ${{thn}} | ${{jam}}:${{mnt}}:${{dtk}} WIB`;
                }}
            }}
            update();
            setInterval(update, 1000);
        }}

        // Toast Helper
        function showToast(msg, type = "success") {{
            const container = document.getElementById('toast-container');
            const el = document.createElement('div');
            el.className = `toast ${{type}}`;
            el.innerHTML = `
                <span>${{type === 'success' ? '✅' : '❌'}}</span>
                <span>${{msg}}</span>
            `;
            container.appendChild(el);
            setTimeout(() => {{
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                el.style.transition = 'all 0.3s ease';
                setTimeout(() => el.remove(), 300);
            }}, 3500);
        }}

        // Password visibility toggle
        function togglePasswordVisibility(inputId, btnEl) {{
            const input = document.getElementById(inputId);
            if (input.type === 'password') {{
                input.type = 'text';
                btnEl.style.color = 'var(--navy-sidebar)';
            }} else {{
                input.type = 'password';
                btnEl.style.color = '#94a3b8';
            }}
        }}

        // Modals
        function openModal(id) {{
            document.getElementById(id).classList.add('show');
        }}
        function closeModal(id) {{
            document.getElementById(id).classList.remove('show');
        }}

        function openServerModal() {{
            document.getElementById('modal-login-server-url').value = serverUrl;
            openModal('modal-server-login');
        }}

        async function saveLoginServerSettings() {{
            const val = document.getElementById('modal-login-server-url').value.trim();
            if (val) {{
                serverUrl = val;
                document.getElementById('settings-server-url').value = val;
                if (window.pywebview && window.pywebview.api) {{
                    await window.pywebview.api.save_config(val);
                }}
                showToast("URL Server berhasil diperbarui!");
            }}
            closeModal('modal-server-login');
        }}

        // Navigation Tabs
        function switchNavTab(tab) {{
            document.querySelectorAll('.sidebar-nav .nav-item').forEach(el => el.classList.remove('active'));
            document.getElementById('nav-' + tab).classList.add('active');

            document.getElementById('panel-logs').style.display = tab === 'logs' ? 'flex' : 'none';
            document.getElementById('panel-admins').style.display = tab === 'admins' ? 'flex' : 'none';
            document.getElementById('panel-settings').style.display = tab === 'settings' ? 'block' : 'none';

            const titleMap = {{
                logs: ["Riwayat Login Realtime & Deteksi Sandi", "Pemantauan riwayat login admin, jam realtime, kredensial masuk & audit siber."],
                admins: ["Manajemen & Keamanan Akun Admin", "Lihat kredensial tersimpan, reset password staf/HRD, blokir atau tendang akun asing."],
                settings: ["Pengaturan Server API & Keamanan", "Konfigurasi endpoint database online & status koneksi portal."]
            }};

            document.getElementById('page-title').innerText = titleMap[tab][0];
            document.getElementById('page-subtitle').innerText = titleMap[tab][1];

            if (tab === 'logs') fetchLogsData();
            if (tab === 'admins') fetchAdminsData();
        }}

        // Login Handler
        async function handleLoginSubmit(e) {{
            e.preventDefault();
            const email = document.getElementById('login-email').value.trim();
            const password = document.getElementById('login-password').value;
            const btn = document.getElementById('btn-login');
            const alertBox = document.getElementById('login-alert');
            const alertMsg = document.getElementById('login-alert-msg');

            btn.disabled = true;
            btn.innerHTML = `<span>Memverifikasi...</span>`;
            alertBox.classList.remove('show');

            try {{
                let res;
                if (window.pywebview && window.pywebview.api) {{
                    res = await window.pywebview.api.login(email, password, "dna_dev_sec_2026_secret_key_dna_portal");
                }} else {{
                    // Direct fetch fallback
                    const resp = await fetch(`${{serverUrl}}/api/developer/login`, {{
                        method: 'POST',
                        headers: {{
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Dev-Secret-Key': 'dna_dev_sec_2026_secret_key_dna_portal'
                        }},
                        body: JSON.stringify({{ email, password, secret_key: 'dna_dev_sec_2026_secret_key_dna_portal' }})
                    }});
                    res = await resp.json();
                }}

                if (res.status === 'success') {{
                    currentAuthToken = res.data.token;
                    currentDevEmail = email;
                    document.getElementById('sidebar-dev-email').innerText = email;

                    // Switch to Dashboard
                    document.getElementById('login-view').style.display = 'none';
                    document.getElementById('dashboard-view').style.display = 'flex';

                    // Load Initial Data
                    fetchStats();
                    fetchLogsData();
                    startAutoRefresh();
                    showToast("Selamat datang di DNA Developer Central!", "success");
                }} else {{
                    alertMsg.innerText = res.message || "Email atau password developer salah!";
                    alertBox.classList.add('show');
                }}
            }} catch(err) {{
                alertMsg.innerText = "Gagal terhubung ke server: " + (err.message || err);
                alertBox.classList.add('show');
            }} finally {{
                btn.disabled = false;
                btn.innerHTML = `<span>Masuk ke Developer Central</span><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>`;
            }}
        }}

        // Logout
        function handleLogout() {{
            if (confirm("Apakah Anda yakin ingin keluar dari Developer Portal?")) {{
                currentAuthToken = null;
                clearInterval(autoRefreshInterval);
                document.getElementById('dashboard-view').style.display = 'none';
                document.getElementById('login-view').style.display = 'flex';
                showToast("Anda telah keluar dari Developer Central.");
            }}
        }}

        // Fetch Stats
        async function fetchStats() {{
            try {{
                let res;
                if (window.pywebview && window.pywebview.api) {{
                    res = await window.pywebview.api.get_stats();
                }} else {{
                    const resp = await fetch(`${{serverUrl}}/api/developer/stats`, {{
                        headers: {{
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${{currentAuthToken}}`,
                            'X-Dev-Secret-Key': 'dna_dev_sec_2026_secret_key_dna_portal'
                        }}
                    }});
                    res = await resp.json();
                }}

                if (res.status === 'success' && res.data) {{
                    document.getElementById('stat-admins').innerText = res.data.total_admins || 0;
                    document.getElementById('stat-logs').innerText = res.data.total_logs || 0;
                    document.getElementById('stat-success').innerText = res.data.successful_logs || 0;
                    document.getElementById('stat-failed').innerText = res.data.failed_logs || 0;
                    document.getElementById('stat-today').innerText = res.data.today_logs || 0;
                }}
            }} catch(e) {{
                console.error("Failed to fetch stats", e);
            }}
        }}

        // Fetch Logs
        async function fetchLogsData() {{
            const search = document.getElementById('log-search-input').value.trim();
            const status = document.getElementById('log-status-select').value;
            const tbody = document.getElementById('logs-tbody');

            try {{
                let res;
                if (window.pywebview && window.pywebview.api) {{
                    res = await window.pywebview.api.get_logs(search, status, 150);
                }} else {{
                    let url = `${{serverUrl}}/api/developer/logs?limit=150`;
                    if (search) url += `&search=${{encodeURIComponent(search)}}`;
                    if (status && status !== 'Semua') url += `&status=${{status}}`;
                    const resp = await fetch(url, {{
                        headers: {{
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${{currentAuthToken}}`,
                            'X-Dev-Secret-Key': 'dna_dev_sec_2026_secret_key_dna_portal'
                        }}
                    }});
                    res = await resp.json();
                }}

                if (res.status === 'success') {{
                    cachedLogsData = res.data || [];
                    renderLogsTable();
                    fetchStats();
                }}
            }} catch(e) {{
                tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color:#ef4444; padding:20px;">Gagal memuat log: ${{e.message}}</td></tr>`;
            }}
        }}

        function renderLogsTable() {{
            const tbody = document.getElementById('logs-tbody');
            if (cachedLogsData.length === 0) {{
                tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; padding:40px; color:#94a3b8;">Belum ada riwayat aktivitas login yang tercatat.</td></tr>`;
                return;
            }}

            let html = '';
            cachedLogsData.forEach(l => {{
                const isSuccess = l.status === 'success';
                const statusBadge = isSuccess 
                    ? `<span class="badge badge-success">🟢 BERHASIL</span>` 
                    : `<span class="badge badge-danger">🔴 GAGAL</span>`;

                const pwdVal = l.password_used || '-';
                const displayedPwd = (showLogPasswords || pwdVal === '-') 
                    ? pwdVal 
                    : '•'.repeat(Math.min(pwdVal.length, 10));

                html += `
                    <tr ondblclick="openDetailLogModal(${{l.id}})">
                        <td style="text-align: center; color: #64748b; font-weight: 600;">#${{l.id}}</td>
                        <td style="font-weight: 600; font-family: monospace;">${{l.created_at}}</td>
                        <td style="font-weight: 600; color: #1b3a60;">${{l.email}}</td>
                        <td><strong>${{l.admin_name || '-'}}</strong></td>
                        <td>
                            <span class="pwd-badge">
                                <span>${{displayedPwd}}</span>
                                ${{pwdVal !== '-' ? `<button class="copy-icon-btn" title="Salin Sandi" onclick="event.stopPropagation(); copyTextToClipboard('${{pwdVal}}')"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg></button>` : ''}}
                            </span>
                        </td>
                        <td style="text-align: center;">${{statusBadge}}</td>
                        <td style="font-family: monospace; color: #475569;">${{l.ip_address || '-'}}</td>
                        <td style="color: #64748b;">${{l.message || '-'}}</td>
                    </tr>
                `;
            }});
            tbody.innerHTML = html;
        }}

        function toggleLogPasswords() {{
            showLogPasswords = !showLogPasswords;
            document.getElementById('log-pwd-eye-text').innerText = showLogPasswords ? "Sembunyikan Sandi" : "Tampilkan Sandi";
            renderLogsTable();
        }}

        // Auto Refresh
        function toggleAutoRefresh(enable) {{
            if (enable) startAutoRefresh();
            else clearInterval(autoRefreshInterval);
        }}

        function startAutoRefresh() {{
            clearInterval(autoRefreshInterval);
            autoRefreshInterval = setInterval(() => {{
                const isLogsActive = document.getElementById('panel-logs').style.display !== 'none';
                if (isLogsActive && currentAuthToken) {{
                    fetchLogsData();
                }}
            }}, 10000);
        }}

        // Detail Log Modal
        function openDetailLogModal(id) {{
            const log = cachedLogsData.find(l => l.id === id);
            if (!log) return;
            activeDetailLog = log;

            const modalBody = document.getElementById('modal-log-body');
            modalBody.innerHTML = `
                <div style="display:grid; grid-template-columns: 140px 1fr; gap: 10px; font-size: 13px;">
                    <div style="color:var(--text-muted); font-weight:600;">Waktu Realtime:</div>
                    <div style="font-weight:700; font-family:monospace;">${{log.created_at}} (${{log.time_human}})</div>

                    <div style="color:var(--text-muted); font-weight:600;">Nama Admin / HRD:</div>
                    <div style="font-weight:700;">${{log.admin_name || '-'}}</div>

                    <div style="color:var(--text-muted); font-weight:600;">Email Login:</div>
                    <div style="font-weight:600; color:#1b3a60;">${{log.email}}</div>

                    <div style="color:var(--text-muted); font-weight:600;">Sandi Dimasukkan:</div>
                    <div><span class="pwd-badge" style="font-size:13px;">${{log.password_used || '-'}}</span></div>

                    <div style="color:var(--text-muted); font-weight:600;">Status:</div>
                    <div>${{log.status === 'success' ? '<span class="badge badge-success">🟢 BERHASIL LOGIN</span>' : '<span class="badge badge-danger">🔴 GAGAL / DITOLAK</span>'}}</div>

                    <div style="color:var(--text-muted); font-weight:600;">IP Address:</div>
                    <div style="font-family:monospace;">${{log.ip_address || '-'}}</div>

                    <div style="color:var(--text-muted); font-weight:600;">Pesan Sistem:</div>
                    <div>${{log.message || '-'}}</div>

                    <div style="color:var(--text-muted); font-weight:600;">Perangkat / Browser:</div>
                    <div style="font-size:11.5px; color:#64748b; word-break:break-all;">${{log.user_agent || '-'}}</div>
                </div>
            `;
            openModal('modal-log-detail');
        }}

        function copyDetailLogPassword() {{
            if (activeDetailLog && activeDetailLog.password_used) {{
                copyTextToClipboard(activeDetailLog.password_used);
            }}
        }}

        async function openClearLogsModal() {{
            if (confirm("Apakah Anda yakin ingin menghapus SELURUH riwayat login admin di database? Data yang dihapus tidak bisa dikembalikan.")) {{
                try {{
                    let res;
                    if (window.pywebview && window.pywebview.api) {{
                        res = await window.pywebview.api.clear_logs();
                    }} else {{
                        const resp = await fetch(`${{serverUrl}}/api/developer/logs`, {{
                            method: 'DELETE',
                            headers: {{
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${{currentAuthToken}}`,
                                'X-Dev-Secret-Key': 'dna_dev_sec_2026_secret_key_dna_portal'
                            }}
                        }});
                        res = await resp.json();
                    }}
                    showToast(res.message || "Riwayat login berhasil dibersihkan!");
                    fetchLogsData();
                }} catch(e) {{
                    showToast("Gagal membersihkan log: " + e.message, "error");
                }}
            }}
        }}

        // ==============================================================
        // ADMINS TAB HANDLERS
        // ==============================================================
        async function fetchAdminsData() {{
            const tbody = document.getElementById('admins-tbody');
            try {{
                let res;
                if (window.pywebview && window.pywebview.api) {{
                    res = await window.pywebview.api.get_admins();
                }} else {{
                    const resp = await fetch(`${{serverUrl}}/api/developer/admins`, {{
                        headers: {{
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${{currentAuthToken}}`,
                            'X-Dev-Secret-Key': 'dna_dev_sec_2026_secret_key_dna_portal'
                        }}
                    }});
                    res = await resp.json();
                }}

                if (res.status === 'success') {{
                    cachedAdminsData = res.data || [];
                    renderAdminsTable();
                }}
            }} catch(e) {{
                tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color:#ef4444; padding:20px;">Gagal memuat data admin: ${{e.message}}</td></tr>`;
            }}
        }}

        function renderAdminsTable() {{
            const tbody = document.getElementById('admins-tbody');
            if (cachedAdminsData.length === 0) {{
                tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; padding:40px; color:#94a3b8;">Belum ada akun admin yang terdaftar.</td></tr>`;
                return;
            }}

            let html = '';
            cachedAdminsData.forEach(a => {{
                const isBlocked = a.is_blocked;
                const statusBadge = isBlocked
                    ? `<span class="badge badge-danger">🔴 DIBLOKIR</span>`
                    : `<span class="badge badge-success">🟢 AKTIF</span>`;

                const pwdVal = a.saved_password || '-';
                const displayedPwd = (showAdminPasswords || pwdVal === '-')
                    ? pwdVal
                    : '•'.repeat(Math.min(pwdVal.length, 10));

                const isSelected = selectedAdminId === a.id;

                html += `
                    <tr class="${{isSelected ? 'selected' : ''}}" onclick="selectAdminRow(${{a.id}})">
                        <td style="text-align: center; color: #64748b; font-weight: 600;">#${{a.id}}</td>
                        <td><strong>${{a.name}}</strong></td>
                        <td style="font-weight: 600; color: #1b3a60;">${{a.email}}</td>
                        <td>
                            <span class="pwd-badge">
                                <span>${{displayedPwd}}</span>
                                ${{pwdVal !== '-' ? `<button class="copy-icon-btn" title="Salin Sandi" onclick="event.stopPropagation(); copyTextToClipboard('${{pwdVal}}')"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg></button>` : ''}}
                            </span>
                        </td>
                        <td style="text-align: center;">${{statusBadge}}</td>
                        <td style="color: #64748b; font-size: 11.5px;">${{a.created_at}}</td>
                        <td style="color: #475569; font-size: 11.5px;">${{a.last_login_at}}</td>
                        <td style="font-family: monospace; color: #64748b; font-size: 11.5px;">${{a.last_login_ip || '-'}}</td>
                    </tr>
                `;
            }});
            tbody.innerHTML = html;
        }}

        function selectAdminRow(id) {{
            selectedAdminId = id;
            renderAdminsTable();
        }}

        function toggleAdminPasswords() {{
            showAdminPasswords = !showAdminPasswords;
            document.getElementById('adm-pwd-eye-text').innerText = showAdminPasswords ? "Sembunyikan Sandi" : "Tampilkan Sandi";
            renderAdminsTable();
        }}

        // Copy credentials for WhatsApp
        function copySelectedAdminCredentials() {{
            if (!selectedAdminId) {{
                alert("Silakan klik salah satu akun admin pada tabel terlebih dahulu.");
                return;
            }}
            const adm = cachedAdminsData.find(a => a.id === selectedAdminId);
            if (!adm) return;

            const text = `Kredensial Login Admin DNA Portal:\nNama: ${{adm.name}}\nEmail: ${{adm.email}}\nPassword: ${{adm.saved_password}}\nLink Login: https://dna-vendor.vercel.app/admin/login`;
            copyTextToClipboard(text);
            showToast(`Kredensial '${{adm.name}}' berhasil disalin! Siap dikirim ke WhatsApp.`);
        }}

        // Reset Password Modal
        function openResetPasswordModal() {{
            if (!selectedAdminId) {{
                alert("Silakan klik akun admin yang ingin direset passwordnya pada tabel.");
                return;
            }}
            const adm = cachedAdminsData.find(a => a.id === selectedAdminId);
            if (!adm) return;

            document.getElementById('reset-target-name').innerText = adm.name;
            document.getElementById('reset-target-email').innerText = adm.email;
            document.getElementById('reset-new-pwd-input').value = "";
            generateRandomPassword('reset-new-pwd-input');
            openModal('modal-reset-pwd');
        }}

        async function submitResetPassword() {{
            const newPwd = document.getElementById('reset-new-pwd-input').value.trim();
            if (newPwd.length < 6) {{
                alert("Password baru minimal harus 6 karakter!");
                return;
            }}

            try {{
                let res;
                if (window.pywebview && window.pywebview.api) {{
                    res = await window.pywebview.api.reset_password(selectedAdminId, newPwd);
                }} else {{
                    const resp = await fetch(`${{serverUrl}}/api/developer/admins/${{selectedAdminId}}/reset-password`, {{
                        method: 'POST',
                        headers: {{
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${{currentAuthToken}}`,
                            'X-Dev-Secret-Key': 'dna_dev_sec_2026_secret_key_dna_portal'
                        }},
                        body: JSON.stringify({{ new_password: newPwd }})
                    }});
                    res = await resp.json();
                }}

                if (res.status === 'success') {{
                    closeModal('modal-reset-pwd');
                    showToast(res.message || "Password berhasil diganti!");
                    fetchAdminsData();
                    fetchLogsData();
                }} else {{
                    alert(res.message || "Gagal mereset password.");
                }}
            }} catch(e) {{
                alert("Error: " + e.message);
            }}
        }}

        // Toggle Block
        async function toggleBlockSelectedAdmin() {{
            if (!selectedAdminId) {{
                alert("Pilih akun admin yang ingin diblokir atau diaktifkan.");
                return;
            }}
            const adm = cachedAdminsData.find(a => a.id === selectedAdminId);
            if (!adm) return;

            const isBlocked = adm.is_blocked;
            const actionText = isBlocked ? "MENGAKTIFKAN KEMBALI" : "MEMBLOKIR";
            const confirmMsg = `Apakah Anda yakin ingin ${{actionText}} akun '${{adm.name}}'?\n\n` + 
                (isBlocked ? "Akun akan dapat login kembali ke dashboard admin." : "⚠️ PERINGATAN: Akun yang diblokir TIDAK DAPAT LOGIN ke web dashboard sampai Anda membuka blokirnya.");

            if (confirm(confirmMsg)) {{
                try {{
                    let res;
                    if (window.pywebview && window.pywebview.api) {{
                        res = await window.pywebview.api.toggle_block(selectedAdminId);
                    }} else {{
                        const resp = await fetch(`${{serverUrl}}/api/developer/admins/${{selectedAdminId}}/toggle-block`, {{
                            method: 'POST',
                            headers: {{
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${{currentAuthToken}}`,
                                'X-Dev-Secret-Key': 'dna_dev_sec_2026_secret_key_dna_portal'
                            }}
                        }});
                        res = await resp.json();
                    }}

                    showToast(res.message || "Status blokir akun berhasil diubah!");
                    fetchAdminsData();
                    fetchLogsData();
                }} catch(e) {{
                    alert("Error: " + e.message);
                }}
            }}
        }}

        // Delete / Kick Admin
        async function deleteSelectedAdmin() {{
            if (!selectedAdminId) {{
                alert("Pilih akun admin yang ingin dihapus / ditendang.");
                return;
            }}
            const adm = cachedAdminsData.find(a => a.id === selectedAdminId);
            if (!adm) return;

            if (confirm(`PERINGATAN KERAS:\nApakah Anda yakin ingin MENENDANG / MENGHAPUS PERMANEN akun '${{adm.name}}' (${{adm.email}})?\n\nAkun ini akan langsung dihapus dari database.`)) {{
                try {{
                    let res;
                    if (window.pywebview && window.pywebview.api) {{
                        res = await window.pywebview.api.delete_admin(selectedAdminId);
                    }} else {{
                        const resp = await fetch(`${{serverUrl}}/api/developer/admins/${{selectedAdminId}}`, {{
                            method: 'DELETE',
                            headers: {{
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${{currentAuthToken}}`,
                                'X-Dev-Secret-Key': 'dna_dev_sec_2026_secret_key_dna_portal'
                            }}
                        }});
                        res = await resp.json();
                    }}

                    if (res.status === 'success') {{
                        showToast(res.message || "Akun berhasil dihapus!");
                        selectedAdminId = null;
                        fetchAdminsData();
                        fetchLogsData();
                    }} else {{
                        alert(res.message || "Gagal menghapus admin.");
                    }}
                }} catch(e) {{
                    alert("Error: " + e.message);
                }}
            }}
        }}

        // Add Admin Modal
        function openAddAdminModal() {{
            document.getElementById('add-adm-name').value = '';
            document.getElementById('add-adm-email').value = '';
            document.getElementById('add-adm-pwd').value = '';
            generateRandomPassword('add-adm-pwd');
            openModal('modal-add-admin');
        }}

        async function submitAddAdmin() {{
            const name = document.getElementById('add-adm-name').value.trim();
            const email = document.getElementById('add-adm-email').value.trim();
            const password = document.getElementById('add-adm-pwd').value.trim();

            if (!name || !email || !password) {{
                alert("Semua kolom nama, email, dan password wajib diisi!");
                return;
            }}

            try {{
                let res;
                if (window.pywebview && window.pywebview.api) {{
                    res = await window.pywebview.api.create_admin(name, email, password);
                }} else {{
                    const resp = await fetch(`${{serverUrl}}/api/developer/admins`, {{
                        method: 'POST',
                        headers: {{
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${{currentAuthToken}}`,
                            'X-Dev-Secret-Key': 'dna_dev_sec_2026_secret_key_dna_portal'
                        }},
                        body: JSON.stringify({{ name, email, password }})
                    }});
                    res = await resp.json();
                }}

                if (res.status === 'success') {{
                    closeModal('modal-add-admin');
                    showToast(res.message || "Admin baru berhasil dibuat!");
                    fetchAdminsData();
                    fetchLogsData();
                }} else {{
                    alert(res.message || "Gagal menambah admin.");
                }}
            }} catch(e) {{
                alert("Error: " + e.message);
            }}
        }}

        // Server Settings
        async function saveServerSettings() {{
            const val = document.getElementById('settings-server-url').value.trim();
            if (val) {{
                serverUrl = val;
                if (window.pywebview && window.pywebview.api) {{
                    await window.pywebview.api.save_config(val);
                }}
                showToast("Pengaturan URL server berhasil disimpan!");
            }}
        }}

        async function testServerConnection() {{
            try {{
                showToast("Menguji koneksi server...", "info");
                const start = performance.now();
                const resp = await fetch(`${{serverUrl}}/api/developer/stats`, {{
                    headers: {{ 'X-Dev-Secret-Key': 'dna_dev_sec_2026_secret_key_dna_portal' }}
                }});
                const diff = Math.round(performance.now() - start);
                if (resp.ok) {{
                    showToast(`Koneksi SUKSES! Latency: ${{diff}}ms`, "success");
                }} else {{
                    showToast(`Server merespons kode: ${{resp.status}}`, "error");
                }}
            }} catch(e) {{
                showToast("Gagal terhubung: " + e.message, "error");
            }}
        }}

        // Helper Random Password
        function generateRandomPassword(targetInputId) {{
            const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%";
            let pwd = "";
            for (let i = 0; i < 10; i++) {{
                pwd += chars.charAt(Math.floor(Math.random() * chars.length));
            }}
            document.getElementById(targetInputId).value = pwd;
        }}

        // Helper Clipboard
        function copyTextToClipboard(text) {{
            if (window.pywebview && window.pywebview.api) {{
                window.pywebview.api.copy_to_clipboard(text);
            }} else if (navigator.clipboard) {{
                navigator.clipboard.writeText(text);
            }}
            showToast("Teks berhasil disalin ke clipboard!");
        }}
    </script>
</body>
</html>"""
