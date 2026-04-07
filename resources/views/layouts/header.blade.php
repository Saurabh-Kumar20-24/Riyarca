<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SeoMagics') }} – @yield('title', 'Dashboard')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Your existing CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    {{-- Page-specific styles --}}
    @stack('styles')

    <style>
        /* ─── Reset & Base ─────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 240px;
            --navbar-h: 60px;
            --bg:        #f4f6fb;
            --surface:   #ffffff;
            --border:    #e8ecf4;
            --primary:   #0f3460;
            --primary-lt:#eef2f9;
            --accent:    #3b82f6;
            --text:      #1a1a2e;
            --muted:     #8890a4;
            --success:   #22c55e;
            --danger:    #ef4444;
            --warning:   #f59e0b;
            --purple:    #8b5cf6;
            --radius:    10px;
            --shadow:    0 1px 4px rgba(0,0,0,.07);
        }

        html, body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
        }

        a { text-decoration: none; color: inherit; }

        /* ─── App Shell ─────────────────────────────────────────── */
        .app-shell {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* ─── Topbar ────────────────────────────────────────────── */
        .topbar {
            position: fixed;
            top: 0; left: var(--sidebar-w); right: 0;
            height: var(--navbar-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
            z-index: 100;
            transition: left .25s ease;
        }

        .topbar-title {
            font-weight: 700;
            font-size: 15px;
            color: var(--text);
            flex: 1;
        }

        .topbar-search {
            display: flex;
            align-items: center;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 6px 12px;
            gap: 8px;
            width: 220px;
        }
        .topbar-search i { color: var(--muted); font-size: 13px; }
        .topbar-search input {
            border: none; background: transparent;
            font-family: inherit; font-size: 13px;
            color: var(--text); outline: none; width: 100%;
        }
        .topbar-search input::placeholder { color: var(--muted); }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .icon-btn {
            width: 36px; height: 36px;
            border-radius: 8px;
            background: var(--bg);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; position: relative;
            color: var(--muted);
            transition: background .2s, color .2s;
        }
        .icon-btn:hover { background: var(--primary-lt); color: var(--primary); }
        .icon-btn .badge {
            position: absolute; top: 4px; right: 4px;
            width: 8px; height: 8px;
            border-radius: 50%; background: var(--danger);
            border: 2px solid var(--surface);
        }

        .avatar-btn {
            width: 36px; height: 36px;
            border-radius: 50%; overflow: hidden;
            border: 2px solid var(--border);
            cursor: pointer; flex-shrink: 0;
        }
        .avatar-btn img { width: 100%; height: 100%; object-fit: cover; }

        /* Sidebar toggle for mobile */
        .sidebar-toggle {
            display: none;
            width: 36px; height: 36px;
            border-radius: 8px;
            background: var(--bg);
            border: 1px solid var(--border);
            align-items: center; justify-content: center;
            cursor: pointer; color: var(--text);
        }

        @media (max-width: 768px) {
            .sidebar-toggle { display: flex; }
            .topbar { left: 0; }
            .topbar-search { display: none; }
        }


        /* this is for profile style */
                :root {
            --brand:       #5b21b6;
            --brand-light: #ede9fe;
            --brand-mid:   #7c3aed;
            --surface:     #ffffff;
            --border:      #e8e8ed;
            --muted:       #9ca3af;
            --text:        #111827;
            --text-2:      #6b7280;
            --green-bg:    #d1fae5;
            --green-fg:    #065f46;
            --red-bg:      #fee2e2;
            --red-fg:      #991b1b;
            --radius:      14px;
            --shadow:      0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: #f5f4f8;
            color: var(--text);
        }

        /* ── Page ── */
        .page-wrap {
            max-width: 660px;
            margin: 2.5rem auto;
            padding: 0 1.25rem 3rem;
        }
        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.4rem;
            display: flex;
            align-items: center;
            gap: .45rem;
        }
        .page-title i { color: var(--brand); }

        /* ── Avatar hero ── */
        .avatar-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 1.6rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.3rem;
            margin-bottom: 1rem;
        }
        .avatar-wrap {
            position: relative;
            flex-shrink: 0;
        }
        /* camera icon overlay — only clickable in edit mode */
        .avatar-wrap .cam-btn {
            position: absolute;
            bottom: 0; right: 0;
            width: 26px; height: 26px;
            border-radius: 50%;
            background: var(--brand);
            color: #fff;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: .7rem;
            cursor: pointer;
            border: 2px solid #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,.18);
        }
        body.edit-mode .avatar-wrap .cam-btn { display: flex; }
        body.edit-mode .avatar-wrap { cursor: pointer; }

        .avatar {
            width: 76px; height: 76px;
            border-radius: 50%;
            background: var(--brand);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.85rem; font-weight: 700; color: #fff;
            overflow: hidden;
            border: 3px solid var(--brand-light);
        }
        .avatar img { width: 100%; height: 100%; object-fit: cover; }
        #avatarInput { display: none; }

        .hero-info h4 { font-size: 1.05rem; font-weight: 700; margin-bottom: .12rem; }
        .hero-info p  { font-size: .8rem; color: var(--text-2); margin-bottom: .45rem; }
        .badge-pill {
            font-size: .67rem; font-weight: 600;
            padding: .16rem .6rem; border-radius: 20px; display: inline-block;
        }
        .pill-role     { background: var(--brand-light); color: var(--brand); }
        .pill-active   { background: var(--green-bg); color: var(--green-fg); }
        .pill-inactive { background: var(--red-bg);   color: var(--red-fg); }

        /* ── Stats ── */
        .stats-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .8rem;
            margin-bottom: 1rem;
        }
        .stat-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: .8rem 1rem;
            display: flex; align-items: center; gap: .75rem;
        }
        .stat-icon {
            width: 34px; height: 34px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem; flex-shrink: 0;
        }
        .ic-green { background: var(--green-bg); color: #059669; }
        .ic-blue  { background: #dbeafe;         color: #1d4ed8; }
        .stat-label { font-size: .62rem; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
        .stat-val   { font-size: .86rem; font-weight: 700; color: var(--text); font-family: 'DM Mono', monospace; }

        /* ── Main card ── */
        .main-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .card-head {
            padding: .95rem 1.4rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-head-title {
            font-size: .66rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .08em;
            color: var(--muted);
            display: flex; align-items: center; gap: .35rem;
        }
        .card-head-title i { color: var(--brand); font-size: .8rem; }

        /* Edit / Cancel toggle button */
        .btn-edit-toggle {
            font-size: .73rem; font-weight: 600;
            padding: .28rem .8rem;
            border-radius: 7px;
            border: 1.5px solid var(--brand);
            background: transparent;
            color: var(--brand);
            cursor: pointer;
            display: flex; align-items: center; gap: .3rem;
            transition: background .15s, color .15s;
        }
        .btn-edit-toggle:hover { background: var(--brand); color: #fff; }

        /* ── Field row ── */
        .field-row {
            display: flex; align-items: center;
            padding: .78rem 1.4rem;
            border-bottom: 1px solid #f3f4f6;
            gap: .85rem;
        }
        .field-row:last-of-type { border-bottom: none; }
        .field-icon {
            width: 28px; height: 28px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 7px; background: #f5f4f8;
            font-size: .76rem; color: var(--brand); flex-shrink: 0;
        }
        .field-body { flex: 1; }
        .field-label {
            font-size: .6rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .06em;
            color: var(--muted); margin-bottom: .2rem;
        }

        /* ── Input styling ── */
        .field-input {
            width: 100%;
            border: 1px solid transparent;
            border-radius: 7px;
            padding: .38rem .6rem;
            font-size: .85rem;
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            color: var(--text);
            background: transparent;
            outline: none;
            transition: border-color .2s, background .2s, box-shadow .2s;
            /* disabled by default */
            pointer-events: none;
        }

        /* When edit mode is ON and input has data-editable */
        body.edit-mode .field-input[data-editable] {
            pointer-events: auto;
            border-color: var(--border);
            background: #fafafa;
            cursor: text;
        }
        body.edit-mode .field-input[data-editable]:focus {
            border-color: var(--brand-mid);
            background: var(--surface);
            box-shadow: 0 0 0 3px rgba(124,58,237,.1);
        }

        .field-val { font-size: .85rem; font-weight: 600; color: var(--text); }

        /* ── Save button — hidden until edit mode ── */
        .save-bar {
            display: none;
            padding: .85rem 1.4rem 1rem;
            gap: .55rem;
        }
        body.edit-mode .save-bar { display: flex; }

        .btn-save {
            flex: 1;
            padding: .55rem 1rem;
            background: var(--brand);
            color: #fff; border: none;
            border-radius: 8px;
            font-size: .83rem; font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: .4rem;
            transition: background .15s, box-shadow .15s;
        }
        .btn-save:hover { background: var(--brand-mid); box-shadow: 0 4px 12px rgba(91,33,182,.25); }

        .btn-cancel {
            padding: .55rem 1rem;
            background: transparent;
            color: var(--text-2);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: .83rem; font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: border-color .15s, color .15s;
        }
        .btn-cancel:hover { border-color: #bbb; color: var(--text); }

        /* ── Toast ── */
        .toast-wrap { position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999; }
        .toast-msg {
            background: #111; color: #fff;
            padding: .55rem .95rem; border-radius: 9px;
            font-size: .78rem; font-weight: 500;
            display: flex; align-items: center; gap: .4rem;
            opacity: 0; transform: translateY(10px);
            transition: all .25s ease; pointer-events: none;
        }
        .toast-msg.show { opacity: 1; transform: translateY(0); }
        .toast-msg i { color: #4ade80; }

        @media (max-width: 520px) {
            .stats-row { grid-template-columns: 1fr; }
            .avatar-card { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>
<div class="app-shell">

    {{-- ── Sidebar (injected from sidebar.blade.php) ── --}}
    @include('layouts.sidebar')

    {{-- ── Right Side: topbar + page content ── --}}
    <div class="page-wrapper" id="pageWrapper">

        {{-- Topbar --}}
        <header class="topbar">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <span class="topbar-title"></span>

            <!-- <div class="topbar-search">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Search…">
            </div> -->

            <div class="topbar-actions">
                <div class="icon-btn">
                    <i class="bi bi-bell"></i>
                    <span class="badge"></span>
                </div>
                <!-- <div class="icon-btn">
                    <i class="bi bi-envelope"></i>
                </div> -->
                <div class="avatar-btn">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=0f3460&color=fff" alt="avatar">
                </div>
            </div>
        </header>

        {{-- ── Main content area ── --}}
        <main class="main-content">