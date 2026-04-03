<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riyarca</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f2f5; min-height: 100vh; }

        /* Navbar */
        .navbar {
            background: #0f3460;
            padding: 0 2rem;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 200;
        }
        .navbar .brand {
            color: #fff; font-size: 16px;
            font-weight: 600; letter-spacing: 1px;
            text-transform: uppercase;
        }
        .navbar-right { display: flex; align-items: center; gap: 14px; }
        .logout-btn {
            background: rgba(255,255,255,0.12);
            color: #fff; border: none;
            padding: 7px 20px; border-radius: 6px;
            font-size: 13px; cursor: pointer;
            text-decoration: none;
        }
        .logout-btn:hover { background: rgba(255,255,255,0.22); }

        /* Hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            background: none;
            border: none;
            padding: 4px;
        }
        .hamburger span {
            display: block; width: 22px; height: 2px;
            background: #fff; border-radius: 2px;
            transition: all .3s;
        }
        .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity: 0; }
        .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        /* Layout */
        .layout { display: flex; margin-top: 55px; min-height: calc(100vh - 55px); }
        .main-content { margin-left: 230px; padding: 2rem; flex: 1; transition: margin-left .3s; }

        /* Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 150;
        }
        .sidebar-overlay.active { display: block; }

        /* Responsive */
        @media (max-width: 768px) {
            .hamburger { display: flex; }
            .main-content { margin-left: 0; padding: 1.2rem; }
        }
    </style>
</head>
<body>

<div class="navbar">
    <div style="display:flex;align-items:center;gap:14px;">
        <button class="hamburger" id="hamburgerBtn" onclick="toggleSidebar()">
            <span></span><span></span><span></span>
        </button>
        <span class="brand">Riyarca</span>
    </div>
    <div class="navbar-right">
        <!-- <span style="color:rgba(255,255,255,0.7);font-size:13px;">{{ Auth::user()->name }}</span> -->
        <a class="logout-btn" href="#"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
           Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<div class="layout">
    @include('layouts.sidebar')
    <div class="main-content">