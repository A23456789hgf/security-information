{{--
╔══════════════════════════════════════════════════════════════════════╗
║                                                                      ║
║    ✦  Enterprise Dashboard — Collapsible Sidebar Layout            ✦ ║
║                                                                      ║
║    • القائمة مفتوحة افتراضياً على الشاشات الكبيرة                   ║
║    • تكرار الضغط يُغلق/يفتح القائمة (Toggle)                        ║
║    • دعم كامل RTL + Light Mode                                      ║
║                                                                      ║
╚══════════════════════════════════════════════════════════════════════╝
--}}

<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') — {{ config('app.name', 'نظام الإدارة') }}</title>

    {{-- Local Fonts & Zero-Delay Fallbacks --}}
    <style>
        @font-face { font-family: 'Cairo'; font-display: swap; src: local('Cairo'), local('Segoe UI'), local('Arial'); }
        @font-face { font-family: 'Tajawal'; font-display: swap; src: local('Tajawal'), local('Segoe UI'), local('Arial'); }
    </style>

    {{-- Bootstrap RTL (Local) --}}
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.rtl.min.css') }}">

    {{-- Font Awesome (Local) --}}
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/all.min.css') }}">

    {{-- Vite Assets (Includes compiled Tailwind & Custom Styles) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ═══════════════════════════════════════════════════
           §1. DESIGN TOKENS
        ═══════════════════════════════════════════════════ */
        :root {
            --brand: #4f46e5;
            --brand-dark: #4338ca;
            --brand-50: #eef2ff;
            --brand-100: #e0e7ff;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --bg: #f8fafc;
            --surface: #ffffff;
            --surface-subtle: #f1f5f9;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --text-subtle: #94a3b8;
            --sidebar-width: 280px;
            --header-height: 68px;
            --ease: cubic-bezier(0.16, 1, 0.3, 1);
            --duration: 0.3s;
        }

        /* ═══════════════════════════════════════════════════
           §2. BASE
        ═══════════════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; }
        html { -webkit-font-smoothing: antialiased; }

        body {
            font-family: 'Tajawal', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            line-height: 1.6;
            font-size: 14.5px;
            overflow-x: hidden;
        }

        .font-display { font-family: 'Cairo', sans-serif; letter-spacing: -0.02em; }
        ::selection { background: var(--brand-100); color: var(--brand-dark); }

        :focus-visible {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
            border-radius: 8px;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }

        /* ═══════════════════════════════════════════════════
           §3. APP SHELL
        ═══════════════════════════════════════════════════ */
        .app-shell {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* ═══════════════════════════════════════════════════
           §4. SIDEBAR
        ═══════════════════════════════════════════════════ */
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--surface);
            border-left: 1px solid var(--border);
            box-shadow: -4px 0 24px rgba(0,0,0,0.06);
            z-index: 60;
            display: flex;
            flex-direction: column;
            transform: translateX(100%); /* مغلق افتراضياً على الشاشات الصغيرة */
            transition: transform var(--duration) var(--ease);
            overflow: hidden;
        }

        /* حالة الفتح للشاشات الصغيرة */
        .sidebar.is-open {
            transform: translateX(0);
        }

        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0); /* مفتوح افتراضياً على الشاشات الكبيرة */
            }
            .sidebar.is-closed {
                transform: translateX(100%); /* حالة الإغلاق */
            }
        }

        .sidebar-header {
            height: var(--header-height);
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
            flex-shrink: 0;
        }

        .brand-text {
            font-family: 'Cairo', sans-serif;
            font-weight: 800;
            font-size: 15px;
            color: var(--text);
        }

        .sidebar-close-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            background: transparent;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .sidebar-close-btn:hover {
            background: var(--surface-subtle);
            color: var(--danger);
            border-color: var(--border);
        }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 16px 12px;
        }

        .nav-section-title {
            font-size: 10.5px;
            font-weight: 800;
            color: var(--text-subtle);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            padding: 20px 12px 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            margin-bottom: 2px;
            position: relative;
            transition: all 0.15s ease;
        }

        .nav-item:hover {
            background: var(--surface-subtle);
            color: var(--text);
        }

        .nav-item.active {
            background: linear-gradient(135deg, var(--brand-50) 0%, rgba(238, 242, 255, 0.6) 100%);
            color: var(--brand);
            box-shadow: inset 0 0 0 1px rgba(99, 102, 241, 0.1);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background: var(--brand);
            border-radius: 0 3px 3px 0;
        }

        .nav-icon {
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--text-muted);
            transition: color 0.15s ease;
        }

        .nav-item.active .nav-icon,
        .nav-item:hover .nav-icon {
            color: var(--brand);
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid var(--border);
            flex-shrink: 0;
        }

        .sidebar-footer-card {
            padding: 14px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .sidebar-footer-card::before {
            content: '';
            position: absolute;
            top: -50%; right: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 50%);
        }

        /* ═══════════════════════════════════════════════════
           §5. BACKDROP (Overlay)
        ═══════════════════════════════════════════════════ */
        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            z-index: 55;
            opacity: 0;
            visibility: hidden;
            transition: opacity var(--duration) var(--ease), visibility var(--duration);
        }

        .sidebar-backdrop.is-visible {
            opacity: 1;
            visibility: visible;
        }

        /* ═══════════════════════════════════════════════════
           §6. MAIN CONTENT — Takes full width
        ═══════════════════════════════════════════════════ */
        .main-area {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            width: 100%;
            transition: padding-right var(--duration) var(--ease);
        }

        @media (min-width: 992px) {
            .main-area {
                padding-right: var(--sidebar-width); /* إزاحة المحتوى بمقدار القائمة */
            }
            .app-shell.sidebar-closed .main-area {
                padding-right: 0; /* إلغاء الإزاحة عند الإغلاق */
            }
        }

        /* Header */
        .app-header {
            height: var(--header-height);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: saturate(180%) blur(16px);
            -webkit-backdrop-filter: saturate(180%) blur(16px);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .header-start {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-end {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ✅ زر فتح القائمة — ظاهر دائماً */
        .sidebar-toggle-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--surface);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .sidebar-toggle-btn:hover {
            background: var(--brand-50);
            color: var(--brand);
            border-color: var(--brand-100);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.12);
        }

        .page-title {
            font-family: 'Cairo', sans-serif;
            font-size: 17px;
            font-weight: 800;
            color: var(--text);
            margin: 0;
        }

        /* Icon Button */
        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: transparent;
            border: 1px solid transparent;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            transition: all 0.15s ease;
        }

        .icon-btn:hover {
            background: var(--surface-subtle);
            color: var(--text);
            border-color: var(--border);
        }

        .badge-dot {
            position: absolute;
            top: 9px;
            right: 9px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--danger);
            border: 2px solid var(--surface);
        }

        /* User Card */
        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 12px 4px 4px;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: var(--surface);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .user-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
        }

        .user-info {
            display: none;
            text-align: right;
            line-height: 1.2;
        }

        @media (min-width: 640px) {
            .user-info { display: block; }
        }

        .user-name { font-size: 12.5px; font-weight: 700; color: var(--text); }
        .user-role { font-size: 10.5px; color: var(--text-muted); }

        /* ═══════════════════════════════════════════════════
           §7. CONTENT AREA
        ═══════════════════════════════════════════════════ */
        .content-area {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
        }

        @media (min-width: 768px) {
            .content-area { padding: 28px; }
        }

        /* Breadcrumbs */
        .breadcrumbs {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 20px;
            font-size: 12.5px;
            color: var(--text-muted);
            flex-wrap: wrap;
        }

        .breadcrumbs a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .breadcrumbs a:hover { color: var(--brand); }
        .breadcrumbs .separator { color: var(--text-subtle); }
        .breadcrumbs .current { color: var(--text); font-weight: 600; }

        /* ═══════════════════════════════════════════════════
           §8. DROPDOWN
        ═══════════════════════════════════════════════════ */
        .dropdown-menu-custom {
            min-width: 240px;
            padding: 6px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 16px 40px -8px rgba(15, 23, 42, 0.12), 0 4px 12px -2px rgba(15, 23, 42, 0.06);
            margin-top: 8px !important;
        }

        .dropdown-header {
            padding: 12px;
            border-bottom: 1px solid var(--surface-subtle);
            margin-bottom: 4px;
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 13px;
            color: var(--text);
            text-decoration: none;
            background: transparent;
            border: none;
            width: 100%;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.15s ease;
        }

        .dropdown-item-custom:hover { background: var(--surface-subtle); }
        .dropdown-item-custom.danger:hover { background: rgba(239, 68, 68, 0.08); }

        .dropdown-item-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--surface-subtle);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            flex-shrink: 0;
        }

        /* ═══════════════════════════════════════════════════
           §9. MODAL
        ═══════════════════════════════════════════════════ */
        .modal-content-custom {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 24px 48px -12px rgba(15, 23, 42, 0.25);
            overflow: hidden;
        }

        .modal-icon {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(239, 68, 68, 0.05) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: var(--danger);
            font-size: 24px;
        }

        .btn-secondary {
            padding: 10px 20px;
            background: var(--surface-subtle);
            color: var(--text);
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            border-color: #94a3b8;
        }

        .btn-danger {
            padding: 10px 20px;
            background: var(--danger);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.35);
        }

        /* ═══════════════════════════════════════════════════
           §10. TOAST
        ═══════════════════════════════════════════════════ */
        .toast-container {
            position: fixed;
            bottom: 24px;
            left: 24px;
            z-index: 100;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }

        .toast {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 18px;
            box-shadow: 0 16px 40px -8px rgba(15, 23, 42, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 280px;
            pointer-events: auto;
            animation: toastIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes toastIn {
            from { opacity: 0; transform: translateY(12px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ═══════════════════════════════════════════════════
           §11. UTILITIES
        ═══════════════════════════════════════════════════ */
        .bg-soft-primary { background: rgba(99, 102, 241, 0.1) !important; color: var(--brand) !important; }
        .bg-soft-success { background: rgba(16, 185, 129, 0.1) !important; color: var(--success) !important; }
        .bg-soft-warning { background: rgba(245, 158, 11, 0.1) !important; color: var(--warning) !important; }
        .bg-soft-danger  { background: rgba(239, 68, 68, 0.1) !important; color: var(--danger) !important; }
        .bg-soft-info    { background: rgba(6, 182, 212, 0.1) !important; color: var(--info) !important; }
        .soft-shadow { box-shadow: 0 4px 12px -2px rgba(15, 23, 42, 0.06); }

        /* Print */
        @media print {
            .sidebar, .app-header, .sidebar-backdrop, .toast-container, .sidebar-toggle-btn { display: none !important; }
            .content-area { padding: 0; }
        }
    </style>
</head>

<body class="antialiased">

    {{-- ═══════════════════════════════════════════════════════════
         🎯 SIDEBAR (Closed by Default)
    ═══════════════════════════════════════════════════════════ --}}
    <aside class="sidebar" id="sidebar" aria-label="القائمة الجانبية" aria-hidden="true">

        {{-- Header --}}
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <div class="brand-logo">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <span class="brand-text">نظام الإدارة</span>
            </div>
            <button class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="إغلاق القائمة" title="إغلاق">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav" aria-label="القائمة الرئيسية">

            <div class="nav-section-title">الرئيسية</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large nav-icon"></i>
                <span>لوحة التحكم</span>
            </a>

            <div class="nav-section-title">إدارة الأفراد</div>
            <a href="{{ route('system.personal-data.index') }}" class="nav-item {{ request()->is('system/personal-data*') ? 'active' : '' }}">
                <i class="fas fa-user-circle nav-icon"></i>
                <span>البيانات الشخصية</span>
            </a>
            <a href="{{ route('system.secondary-data.index') }}" class="nav-item {{ request()->is('system/secondary-data*') ? 'active' : '' }}">
                <i class="fas fa-id-card nav-icon"></i>
                <span>البيانات الثانوية</span>
            </a>
            <a href="{{ route('system.wives.index') }}" class="nav-item {{ request()->is('system/wives*') ? 'active' : '' }}">
                <i class="fas fa-heart nav-icon"></i>
                <span>بيانات الزوجات</span>
            </a>
            <a href="{{ route('system.relatives.index') }}" class="nav-item {{ request()->is('system/relatives*') ? 'active' : '' }}">
                <i class="fas fa-users nav-icon"></i>
                <span>الأهل والأقارب</span>
            </a>
            <a href="{{ route('system.courses.index') }}" class="nav-item {{ request()->is('system/courses*') ? 'active' : '' }}">
                <i class="fas fa-graduation-cap nav-icon"></i>
                <span>الدورات التدريبية</span>
            </a>

            <div class="nav-section-title">السجلات الأمنية</div>
            <a href="{{ route('system.arrests.index') }}" class="nav-item {{ request()->is('system/arrests*') ? 'active' : '' }}">
                <i class="fas fa-user-lock nav-icon"></i>
                <span>سجلات الضبط</span>
            </a>
            <a href="{{ route('system.suspicious.index') }}" class="nav-item {{ request()->is('system/suspicious*') ? 'active' : '' }}">
                <i class="fas fa-user-secret nav-icon"></i>
                <span>المشتبه بهم</span>
            </a>
            <a href="{{ route('system.security-reports.index') }}" class="nav-item {{ request()->is('system/security-reports*') ? 'active' : '' }}">
                <i class="fas fa-file-shield nav-icon"></i>
                <span>البلاغات الأمنية</span>
            </a>

            <div class="nav-section-title">أدوات البيانات</div>
            <a href="{{ route('system.pages.index') }}" class="nav-item {{ request()->is('system/pages*') ? 'active' : '' }}">
                <i class="fas fa-layer-group nav-icon"></i>
                <span>الصفحات الديناميكية</span>
            </a>
            <a href="{{ route('data.import.index') }}" class="nav-item {{ request()->is('data/import*') ? 'active' : '' }}">
                <i class="fas fa-file-import nav-icon"></i>
                <span>استيراد البيانات</span>
            </a>
            <a href="{{ route('data.differentiation.index') }}" class="nav-item {{ request()->is('data/differentiation*') ? 'active' : '' }}">
                <i class="fas fa-code-compare nav-icon"></i>
                <span>مقارنة وتمايز البيانات</span>
            </a>

            <div class="nav-section-title">الترميزات الجغرافية</div>
            <a href="{{ route('configuration.countries.index') }}" class="nav-item {{ request()->is('configuration/countries*') ? 'active' : '' }}">
                <i class="fas fa-globe nav-icon"></i>
                <span>الدول</span>
            </a>
            <a href="{{ route('configuration.governorates.index') }}" class="nav-item {{ request()->is('configuration/governorates*') ? 'active' : '' }}">
                <i class="fas fa-map nav-icon"></i>
                <span>المحافظات</span>
            </a>
            <a href="{{ route('configuration.directorates.index') }}" class="nav-item {{ request()->is('configuration/directorates*') ? 'active' : '' }}">
                <i class="fas fa-map-location nav-icon"></i>
                <span>المديريات</span>
            </a>
            <a href="{{ route('configuration.sub-areas.index') }}" class="nav-item {{ request()->is('configuration/sub-areas*') ? 'active' : '' }}">
                <i class="fas fa-map-pin nav-icon"></i>
                <span>العزل والناطق</span>
            </a>
            <a href="{{ route('configuration.villages.index') }}" class="nav-item {{ request()->is('configuration/villages*') ? 'active' : '' }}">
                <i class="fas fa-home nav-icon"></i>
                <span>القرى والمحلات</span>
            </a>

            <div class="nav-section-title">ترميزات الحالة</div>
            <a href="{{ route('configuration.blood-types.index') }}" class="nav-item {{ request()->is('configuration/blood-types*') ? 'active' : '' }}">
                <i class="fas fa-tint nav-icon"></i>
                <span>فصائل الدم</span>
            </a>
            <a href="{{ route('configuration.health-statuses.index') }}" class="nav-item {{ request()->is('configuration/health-statuses*') ? 'active' : '' }}">
                <i class="fas fa-heartbeat nav-icon"></i>
                <span>الحالات الصحية</span>
            </a>
            <a href="{{ route('configuration.marital-statuses.index') }}" class="nav-item {{ request()->is('configuration/marital-statuses*') ? 'active' : '' }}">
                <i class="fas fa-ring nav-icon"></i>
                <span>الحالات الاجتماعية</span>
            </a>
            <a href="{{ route('configuration.qualifications.index') }}" class="nav-item {{ request()->is('configuration/qualifications*') ? 'active' : '' }}">
                <i class="fas fa-award nav-icon"></i>
                <span>المؤهلات الدراسية</span>
            </a>
            <a href="{{ route('configuration.kinships.index') }}" class="nav-item {{ request()->is('configuration/kinships*') ? 'active' : '' }}">
                <i class="fas fa-users nav-icon"></i>
                <span>صلات القرابة</span>
            </a>
            <a href="{{ route('configuration.ideological-affiliations.index') }}" class="nav-item {{ request()->is('configuration/ideological-affiliations*') ? 'active' : '' }}">
                <i class="fas fa-praying-hands nav-icon"></i>
                <span>الانتماءات العقائدية</span>
            </a>
            <a href="{{ route('configuration.political-affiliations.index') }}" class="nav-item {{ request()->is('configuration/political-affiliations*') ? 'active' : '' }}">
                <i class="fas fa-flag nav-icon"></i>
                <span>الانتماءات السياسية</span>
            </a>

            <div class="nav-section-title">ترميزات العمل</div>
            <a href="{{ route('configuration.ranks.index') }}" class="nav-item {{ request()->is('configuration/ranks*') ? 'active' : '' }}">
                <i class="fas fa-medal nav-icon"></i>
                <span>الرتب العسكرية</span>
            </a>
            <a href="{{ route('configuration.job-statuses.index') }}" class="nav-item {{ request()->is('configuration/job-statuses*') ? 'active' : '' }}">
                <i class="fas fa-briefcase nav-icon"></i>
                <span>الحالات الوظيفية</span>
            </a>
            <a href="{{ route('configuration.financial-statuses.index') }}" class="nav-item {{ request()->is('configuration/financial-statuses*') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave nav-icon"></i>
                <span>الحالات المالية</span>
            </a>
            <a href="{{ route('configuration.number-classifications.index') }}" class="nav-item {{ request()->is('configuration/number-classifications*') ? 'active' : '' }}">
                <i class="fas fa-list-ol nav-icon"></i>
                <span>تصنيفات الأرقام</span>
            </a>

            <div class="nav-section-title">ترميزات عسكرية</div>
            <a href="{{ route('configuration.military-districts.index') }}" class="nav-item {{ request()->is('configuration/military-districts*') ? 'active' : '' }}">
                <i class="fas fa-shield-alt nav-icon"></i>
                <span>المناطق العسكرية</span>
            </a>
            <a href="{{ route('configuration.divisions.index') }}" class="nav-item {{ request()->is('configuration/divisions*') ? 'active' : '' }}">
                <i class="fas fa-users-cog nav-icon"></i>
                <span>الشعب والوحدات</span>
            </a>
            <a href="{{ route('configuration.axes.index') }}" class="nav-item {{ request()->is('configuration/axes*') ? 'active' : '' }}">
                <i class="fas fa-compass nav-icon"></i>
                <span>المحاور الجبهوية</span>
            </a>

            <div class="nav-section-title">ترميزات أخرى</div>
            <a href="{{ route('configuration.course-types.index') }}" class="nav-item {{ request()->is('configuration/course-types*') ? 'active' : '' }}">
                <i class="fas fa-book nav-icon"></i>
                <span>أنواع الدورات</span>
            </a>
            <a href="{{ route('configuration.filling-agencies.index') }}" class="nav-item {{ request()->is('configuration/filling-agencies*') ? 'active' : '' }}">
                <i class="fas fa-building nav-icon"></i>
                <span>جهات التعبئة</span>
            </a>
            <a href="{{ route('configuration.electronic-inputs.index') }}" class="nav-item {{ request()->is('configuration/electronic-inputs*') ? 'active' : '' }}">
                <i class="fas fa-laptop nav-icon"></i>
                <span>المدخلات الإلكترونية</span>
            </a>
            <a href="{{ route('configuration.arresting-authorities.index') }}" class="nav-item {{ request()->is('configuration/arresting-authorities*') ? 'active' : '' }}">
                <i class="fas fa-landmark nav-icon"></i>
                <span>جهات الضبط</span>
            </a>
            <a href="{{ route('configuration.case-types.index') }}" class="nav-item {{ request()->is('configuration/case-types*') ? 'active' : '' }}">
                <i class="fas fa-gavel nav-icon"></i>
                <span>أنواع القضايا</span>
            </a>

            <div class="nav-section-title">الإدارة والصلاحيات</div>
            <a href="{{ route('system.users.index') }}" class="nav-item {{ request()->is('system/users*') ? 'active' : '' }}">
                <i class="fas fa-user-shield nav-icon"></i>
                <span>إدارة المستخدمين</span>
            </a>
            <a href="{{ route('system.roles.index') }}" class="nav-item {{ request()->is('system/roles*') ? 'active' : '' }}">
                <i class="fas fa-key nav-icon"></i>
                <span>الأدوار والصلاحيات</span>
            </a>
            <a href="{{ route('system.entities.index') }}" class="nav-item {{ request()->is('system/entities*') ? 'active' : '' }}">
                <i class="fas fa-building-shield nav-icon"></i>
                <span>إدارة الجهات</span>
            </a>
            <a href="{{ route('system.sections.index') }}" class="nav-item {{ request()->is('system/sections*') ? 'active' : '' }}">
                <i class="fas fa-sitemap nav-icon"></i>
                <span>إدارة الأقسام</span>
            </a>

            <div style="height: 24px;"></div>
        </nav>

        {{-- Footer --}}
        <div class="sidebar-footer">
            <div class="sidebar-footer-card">
                <div class="relative z-10">
                    <div class="text-xs font-bold mb-1">نسخة احترافية</div>
                    <div class="text-[10px] opacity-80">v2.0.1 • جميع الحقوق محفوظة</div>
                </div>
            </div>
        </div>
    </aside>

    {{-- ═══ Backdrop Overlay ═══ --}}
    <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

    {{-- ═══════════════════════════════════════════════════════════
         📄 MAIN AREA
    ═══════════════════════════════════════════════════════════ --}}
    <div class="main-area">

        {{-- Header --}}
        <header class="app-header">
            <div class="header-start">
                {{-- ✅ زر فتح/إغلاق القائمة --}}
                <button class="sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="فتح القائمة" aria-expanded="false" aria-controls="sidebar">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
                <h1 class="page-title">@yield('page_title', 'نظرة عامة')</h1>
            </div>

            <div class="header-end">
                {{-- Notifications --}}
                <button class="icon-btn" aria-label="الإشعارات">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    <span class="badge-dot"></span>
                </button>

                {{-- User Menu --}}
                @php
                    $user = auth()->user();
                    $userName = $user->name ?? 'مستخدم';
                    $userRole = $user->roles->first()->name ?? 'مستخدم النظام';
                    $userEmail = $user->email ?? 'guest@system.app';
                    $userInitial = mb_substr($userName, 0, 1, 'UTF-8');
                @endphp

                <div class="dropdown">
                    <button class="user-card" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">{{ $userInitial }}</div>
                        <div class="user-info">
                            <div class="user-name">{{ $userName }}</div>
                            <div class="user-role">{{ $userRole }}</div>
                        </div>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-subtle);">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-custom dropdown-menu-end">
                        <li class="dropdown-header">
                            <div class="d-flex align-items-center gap-3">
                                <div class="user-avatar" style="width:40px;height:40px;font-size:14px;">{{ $userInitial }}</div>
                                <div>
                                    <div class="user-name" style="font-size:13.5px;">{{ $userName }}</div>
                                    <div class="user-role" style="font-size:11px;">{{ $userEmail }}</div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="{{ route('system.profile.index') }}" class="dropdown-item-custom">
                                <div class="dropdown-item-icon"><i class="fas fa-user"></i></div>
                                <div>
                                    <div style="font-weight:600;">الملف الشخصي</div>
                                    <div style="font-size:11px;color:var(--text-muted);">إدارة بياناتك</div>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1" style="border-color: var(--surface-subtle);"></li>
                        <li>
                            <button type="button" class="dropdown-item-custom danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                <div class="dropdown-item-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                <div style="color: var(--danger); font-weight:600;">تسجيل الخروج</div>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="content-area">
            @hasSection('breadcrumbs')
                <nav class="breadcrumbs" aria-label="التنقل الفرعي">
                    @yield('breadcrumbs')
                </nav>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         🚪 LOGOUT MODAL
    ═══════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content modal-content-custom">
                <div class="modal-body p-4 text-center">
                    <div class="modal-icon">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <h5 id="logoutModalLabel" class="font-display" style="font-size:18px;font-weight:800;margin-bottom:8px;">
                        تسجيل الخروج
                    </h5>
                    <p style="color: var(--text-muted); font-size: 13.5px; margin: 0 0 24px;">
                        هل أنت متأكد من رغبتك في تسجيل الخروج؟
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn-danger">
                                <i class="fas fa-sign-out-alt"></i>
                                تأكيد الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast Container --}}
    <div class="toast-container" id="toastContainer" aria-live="polite"></div>

    {{-- ═══════════════════════════════════════════════════════════
         ⚙️ SCRIPTS
    ═══════════════════════════════════════════════════════════ --}}
    <!-- jQuery (Required for Select2 and custom scripts) -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <script>
    (() => {
        'use strict';

        // ─── Elements ───
        const sidebar    = document.getElementById('sidebar');
        const backdrop   = document.getElementById('sidebarBackdrop');
        const toggleBtn  = document.getElementById('sidebarToggleBtn');
        const closeBtn   = document.getElementById('sidebarCloseBtn');

        // ─── Toggle Sidebar Function ───
        function toggleSidebar() {
            const isDesktop = window.innerWidth >= 992;
            
            if (isDesktop) {
                // Desktop toggle logic
                const isClosed = sidebar.classList.contains('is-closed');
                if (isClosed) {
                    sidebar.classList.remove('is-closed');
                    document.querySelector('.app-shell').classList.remove('sidebar-closed');
                    toggleBtn.setAttribute('aria-expanded', 'true');
                } else {
                    sidebar.classList.add('is-closed');
                    document.querySelector('.app-shell').classList.add('sidebar-closed');
                    toggleBtn.setAttribute('aria-expanded', 'false');
                }
            } else {
                // Mobile toggle logic
                const isOpen = sidebar.classList.contains('is-open');
                if (isOpen) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            }
        }

        function openSidebar() {
            sidebar.classList.add('is-open');
            backdrop.classList.add('is-visible');
            sidebar.setAttribute('aria-hidden', 'false');
            toggleBtn.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('is-open');
            backdrop.classList.remove('is-visible');
            sidebar.setAttribute('aria-hidden', 'true');
            toggleBtn.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }

        // ─── Event Listeners ───
        toggleBtn?.addEventListener('click', toggleSidebar);
        closeBtn?.addEventListener('click', closeSidebar);
        backdrop?.addEventListener('click', closeSidebar);

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar.classList.contains('is-open')) {
                closeSidebar();
            }
        });

        // ─── Toast System ───
        window.showToast = function(message, type = 'info', duration = 3500) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'toast';

            const icons = {
                success: 'fa-check-circle',
                error: 'fa-times-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle',
            };
            const colors = {
                success: 'var(--success)',
                error: 'var(--danger)',
                warning: 'var(--warning)',
                info: 'var(--brand)',
            };

            toast.innerHTML = `
                <div style="width:32px;height:32px;border-radius:8px;background:${colors[type]}1a;color:${colors[type]};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas ${icons[type]}"></i>
                </div>
                <div style="flex:1;font-size:13px;font-weight:500;">${message}</div>
            `;
            container.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(12px)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        };

        // Show flash messages
        @if(session('success'))  showToast(@json(session('success')), 'success'); @endif
        @if(session('error'))    showToast(@json(session('error')), 'error'); @endif
        @if(session('warning'))  showToast(@json(session('warning')), 'warning'); @endif
        @if(session('info'))     showToast(@json(session('info')), 'info'); @endif

        // ─── Scroll active nav item into view ───
        const activeLink = document.querySelector('.nav-item.active');
        if (activeLink) {
            requestAnimationFrame(() => {
                activeLink.scrollIntoView({ block: 'nearest', behavior: 'instant' });
            });
        }

        // ─── Bootstrap Dropdowns ───
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
            new bootstrap.Dropdown(el);
        });

    })();
    </script>

    @stack('scripts')
</body>

</html>
