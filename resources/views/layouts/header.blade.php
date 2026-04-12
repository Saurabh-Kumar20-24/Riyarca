<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Riyarca') }} – @yield('title', 'Dashboard')</title>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

   
    <link rel="stylesheet" href="{{ asset('assets/css/headerfooter.css') }}">

    @stack('styles')
</head>
<body>

<div class="app-shell">

   
    @include('layouts.sidebar')

    
    <div class="page-wrapper" id="pageWrapper">

        <header class="topbar">

            <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
                <i class="bi bi-list"></i>
            </button>

            <span class="topbar-title">@yield('page-title', 'Dashboard')</span>

            {{-- Search Box --}}
            <!-- <div class="topbar-search">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Search…" aria-label="Search">
            </div> -->

            <div class="topbar-actions">

                <div class="icon-btn" title="Notifications">
                    <i class="bi bi-bell"></i>
                    <span class="badge"></span>
                </div>

                {{-- Messages --}}
                <!-- <div class="icon-btn" title="Messages">
                    <i class="bi bi-envelope"></i>
                </div> -->

                <div class="avatar-btn" title="{{ Auth::user()->name ?? 'User' }}">
                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=6A2FE0&color=fff"
                        alt="User Avatar"
                    >
                </div>

            </div>
        </header>

        <main class="main-content">
            @yield('content')
        </main>


        @include('layouts.footer')

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('assets/js/misc.js') }}"></script>

<script>
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');

    function openSidebar()  { sidebar.classList.add('open');    overlay.classList.add('open'); }
    function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('open'); }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () =>
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar()
        );
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    document.querySelectorAll('.sub-menu .nav-link.active').forEach(link => {
        const collapseEl = link.closest('.collapse');
        if (collapseEl) {
            collapseEl.classList.add('show');
            const trigger = document.querySelector(`[href="#${collapseEl.id}"]`);
            if (trigger) trigger.setAttribute('aria-expanded', 'true');
        }
    });
</script>

@stack('scripts')

</body>
</html>