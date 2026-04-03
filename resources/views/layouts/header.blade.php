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
            <span class="topbar-title">@yield('page-title', 'Dashboard')</span>

            <div class="topbar-search">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Search…">
            </div>

            <div class="topbar-actions">
                <div class="icon-btn">
                    <i class="bi bi-bell"></i>
                    <span class="badge"></span>
                </div>
                <div class="icon-btn">
                    <i class="bi bi-envelope"></i>
                </div>
                <div class="avatar-btn">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=0f3460&color=fff" alt="avatar">
                </div>
            </div>
        </header>

        {{-- ── Main content area ── --}}
        <main class="main-content">