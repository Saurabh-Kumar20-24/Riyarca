
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">

    
    <div class="sidebar-brand">
       
        <span class="brand-name">Riy<span class="brand-accent">arca</span></span>
    </div>

   
    <div class="sidebar-profile">
        <img
            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=6A2FE0&color=fff"
            alt="avatar"
        >
        <div class="profile-info">
            <div class="p-name">{{ Auth::user()->name ?? 'User' }}</div>
            <div class="p-role">{{ Auth::user()->role->role_name ?? 'Admin' }}</div>
        </div>
        <div class="online-dot"></div>
    </div>

    
    <nav class="sidebar-nav">

        
        <div class="nav-label">Main</div>

        <div class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
        </div>
        <!-- @if(Auth::user()->role_id === 1 || Auth::user()->role_id === 2 || Auth::user()->role_id === 9)
          <div class="nav-label">Management</div> -->

       
        @if(Auth::user()->role_id === 1 || Auth::user()->role_id === 2 || Auth::user()->role_id === 9)
            <div class="nav-label">Management</div>

            <div class="nav-item">
                <a href="#userMenu"
                   class="nav-link"
                   data-bs-toggle="collapse"
                   aria-expanded="{{ request()->routeIs('employee.*') ? 'true' : 'false' }}">
                    <i class="bi bi-people"></i> Employees
                    <i class="bi bi-chevron-right arrow"></i>
                </a>
                <ul class="sub-menu collapse {{ request()->routeIs('employee.*') ? 'show' : '' }}" id="userMenu">
                    <li class="nav-item">
                        <a href="{{ route('employee.index') }}"
                           class="nav-link {{ request()->routeIs('employee.*') ? 'active' : '' }}">
                            All Employees
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('employee.create') }}"
                           class="nav-link {{ request()->routeIs('employee.create') ? 'active' : '' }}">
                            Add Employee
                        </a>
                    </li>
                </ul>
            </div>

            
        @endif

        {{-- ── Leads ── --}}
        <div class="nav-item">
            <a href="#leadsMenu"
               class="nav-link"
               data-bs-toggle="collapse"
               aria-expanded="false">
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
         @endif

        {{-- ── Leave Requests ── --}}
        <div class="nav-item">
            <a href="{{route('leave.index')}}" class="nav-link {{ request()->routeIs('leave.*') || request()->routeIs('nfc.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Leave Requests
                <!-- <span class="nav-badge">12</span> -->
            </a>
        </div>

        {{-- ── EOD Reports ── --}}
        <div class="nav-item">
            <a href="{{route('eod.index')}}" class="nav-link {{ request()->routeIs('eod.*') || request()->routeIs('nfc.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> EOD Reports
                <!-- <span class="nav-badge">54</span> -->
            </a>
        </div>
        {{-- ✅ Replace with this --}}
<div class="nav-item">
    <a href="#attendenceMenu" class="nav-link {{ request()->routeIs('attendence.*') || request()->routeIs('nfc.*') ? 'active' : '' }}"
        data-bs-toggle="collapse" aria-expanded="false">
        <i class="bi bi-calendar2-check"></i> Attendance
        <i class="bi bi-chevron-right arrow"></i>
    </a>
    <ul class="sub-menu collapse {{ request()->routeIs('attendence.*') || request()->routeIs('nfc.*') ? 'show' : '' }}"
        id="attendenceMenu">

        <li class="nav-item">
            <a href="{{ route('attendence.index') }}"
               class="nav-link {{ request()->routeIs('attendence.index') ? 'active' : '' }}">
               All Employees
            </a>
        </li>

        @if(Auth::user()->role_id === 1)
        <li class="nav-item">
            <a href="{{ route('nfc.scanner') }}"
               class="nav-link {{ request()->routeIs('nfc.scanner') ? 'active' : '' }}">
               NFC Scanner
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('nfc.write') }}"
               class="nav-link {{ request()->routeIs('nfc.write') ? 'active' : '' }}">
               Write NFC Card
            </a>
        </li>
        @endif

    </ul>
</div>

        {{-- ── Settings ── --}}
        <div class="nav-label">Settings</div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-gear"></i> Settings
            </a>
        </div>

        {{-- ── Profile ── --}}
        <div class="nav-item">
            <a href="{{ route('auth.profile') }}"
               class="nav-link {{ request()->routeIs('auth.profile') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Profile
            </a>
        </div>

    </nav>
    {{-- ── End Navigation ── --}}

    {{-- ══════════════════════════════════════
         Sign Out
    ══════════════════════════════════════ --}}
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-100"
                    style="border:none; background:none; text-align:left;">
                <i class="bi bi-box-arrow-left"></i> Sign Out
            </button>
        </form>
    </div>

</aside>