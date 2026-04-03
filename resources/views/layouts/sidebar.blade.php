<style>
    /* ─── Sidebar ────────────────────────────────────────────── */
    .sidebar {
        width: var(--sidebar-w);
        height: 100vh;
        background: var(--primary);
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        overflow: hidden;
        transition: transform .25s ease, width .25s ease;
        position: fixed;
        top: 0; left: 0;
        z-index: 200;
    }

    /* Brand */
    .sidebar-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0 1.25rem;
        height: var(--navbar-h);
        border-bottom: 1px solid rgba(255,255,255,.08);
        flex-shrink: 0;
    }
    .brand-icon {
        width: 32px; height: 32px;
        background: var(--accent);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 16px; flex-shrink: 0;
    }
    .brand-name {
        font-weight: 700; font-size: 16px;
        color: #fff; letter-spacing: -.3px;
    }
    .brand-name span { color: var(--accent); }

    /* User profile strip */
    .sidebar-profile {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 1.25rem;
        border-bottom: 1px solid rgba(255,255,255,.08);
        flex-shrink: 0;
    }
    .sidebar-profile img {
        width: 36px; height: 36px;
        border-radius: 50%; object-fit: cover;
        border: 2px solid rgba(255,255,255,.2);
    }
    .profile-info .p-name {
        font-size: 13px; font-weight: 600; color: #fff;
        line-height: 1.2;
    }
    .profile-info .p-role {
        font-size: 11px; color: rgba(255,255,255,.5);
        text-transform: uppercase; letter-spacing: .5px;
    }
    .online-dot {
        width: 8px; height: 8px;
        border-radius: 50%; background: var(--success);
        margin-left: auto; flex-shrink: 0;
    }

    /* Nav scroll area */
    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
        padding: 10px 0;
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,.1) transparent;
    }
    .sidebar-nav::-webkit-scrollbar { width: 4px; }
    .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }

    /* Section label */
    .nav-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: rgba(255,255,255,.35);
        padding: 12px 1.25rem 6px;
    }

    /* Nav item */
    .nav-item { position: relative; }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 1.25rem;
        color: rgba(255,255,255,.65);
        font-size: 13.5px;
        font-weight: 500;
        border-radius: 0;
        transition: background .18s, color .18s;
        cursor: pointer;
        user-select: none;
    }
    .nav-link i { font-size: 16px; flex-shrink: 0; }
    .nav-link:hover { background: rgba(255,255,255,.07); color: #fff; }
    .nav-link.active {
        background: rgba(59,130,246,.25);
        color: #fff;
        border-right: 3px solid var(--accent);
    }
    .nav-link .arrow {
        margin-left: auto;
        font-size: 11px;
        transition: transform .2s;
    }
    .nav-link[aria-expanded="true"] .arrow { transform: rotate(90deg); }

    /* Badge */
    .nav-badge {
        margin-left: auto;
        background: var(--accent);
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 20px;
        line-height: 1.6;
    }

    /* Sub-menu */
    .sub-menu {
        list-style: none;
        background: rgba(0,0,0,.15);
        overflow: hidden;
    }
    .sub-menu .nav-link {
        padding: 7px 1.25rem 7px 3rem;
        font-size: 13px;
        color: rgba(255,255,255,.5);
    }
    .sub-menu .nav-link:hover { color: #fff; }
    .sub-menu .nav-link.active { color: #fff; background: rgba(59,130,246,.18); border-right: 3px solid var(--accent); }

    /* Bottom sign-out */
    .sidebar-footer {
        border-top: 1px solid rgba(255,255,255,.08);
        padding: 10px 0;
        flex-shrink: 0;
    }

    /* Overlay for mobile */
    .sidebar-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 199;
    }

    @media (max-width: 768px) {
        .sidebar { transform: translateX(-100%); }
        .sidebar.open { transform: translateX(0); }
        .sidebar-overlay.open { display: block; }
    }

    /* Push page content right of sidebar on desktop */
    .page-wrapper {
        flex: 1;
        margin-left: var(--sidebar-w);
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        overflow: hidden;
        transition: margin-left .25s ease;
    }
    @media (max-width: 768px) {
        .page-wrapper { margin-left: 0; }
    }
</style>

{{-- Mobile overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">
    {{-- Brand --}}
    <!-- <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-lightning-charge-fill"></i></div>
        <span class="brand-name">Seo<span>Magics</span></span>
    </div> -->

    {{-- Profile strip --}}
    <div class="sidebar-profile">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=3b82f6&color=fff" alt="avatar">
        <div class="profile-info">
            <div class="p-name">{{ Auth::user()->name ?? 'User' }}</div>
            <div class="p-role">{{ Auth::user()->role->role_name ?? 'Admin' }}</div>
        </div>
        <div class="online-dot"></div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        <div class="nav-label">Main</div>

        <div class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
        </div>
        @if(Auth::user()->role_id === 1 || Auth::user()->role_id === 2)
          <div class="nav-label">Management</div>
            <div class="nav-item">
                <a href="#userMenu" class="nav-link" data-bs-toggle="collapse" aria-expanded="false">
                    <i class="bi bi-people"></i>Employees
                    <i class="bi bi-chevron-right arrow"></i>
                </a>
                <ul class="sub-menu collapse" id="userMenu">
                    <li class="nav-item">
                        <a href="{{ route('employee.index') }}" class="nav-link {{ request()->routeIs('employee.*') ? 'active' : '' }}">All Employees</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('employee.create') }}" class="nav-link {{ request()->routeIs('employee.create') ? 'active' : '' }}">Add Employee</a>
                    </li>
                </ul>
            </div>
        @endif
        <div class="nav-item">
            <a href="#leadsMenu" class="nav-link" data-bs-toggle="collapse" aria-expanded="false">
                <i class="bi bi-diagram-3"></i> Leads
                <i class="bi bi-chevron-right arrow"></i>
            </a>
            <ul class="sub-menu collapse" id="leadsMenu">
                <li class="nav-item">
                    <a href="#" class="nav-link">All Leads</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Add Lead</a>
                </li>
            </ul>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-calendar-check"></i> Leave Requests
                <span class="nav-badge">12</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-journal-text"></i> EOD Reports
                <span class="nav-badge">54</span>
            </a>
        </div>

        <div class="nav-label">Settings</div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-gear"></i> Settings
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('auth.profile') }}" 
                class="nav-link {{ request()->routeIs('auth.profile') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Profile
            </a>
        </div>
    </nav>

    {{-- Sign out --}}
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-100" style="border:none;background:none;text-align:left;">
                <i class="bi bi-box-arrow-left"></i> Sign Out
            </button>
        </form>
    </div>
</aside>