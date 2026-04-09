
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
                           class="nav-link {{ request()->routeIs('employee.index') ? 'active' : '' }}">
                            All Employees
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('employee.create') }}"
                           class="nav-link {{ request()->routeIs('employee.create') ? 'active' : '' }}">
                            Add Employee
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('role.create') }}" class="nav-link {{ request()->routeIs('role.create') ? 'active' : '' }}">Add Role</a>
                    </li>
                </ul>
            </div> 
        @endif

        @if( Auth::user()->role_id === 9)
            <!-- <div class="nav-label">HR</div> -->
                <div class="nav-item">
                    <a href="#hiringMenu"
                    class="nav-link {{ request()->routeIs('hiring.*') ? 'active' : '' }}"
                    data-bs-toggle="collapse" aria-expanded="false">
                        <i class="bi bi-people"></i> Hiring
                        <i class="bi bi-chevron-right arrow"></i>
                    </a>
                    <ul class="sub-menu collapse {{ request()->routeIs('hiring.*') ? 'show' : '' }}"
                        id="hiringMenu">
                        <li class="nav-item">
                            <a href="{{ route('hiring.accepted') }}"
                            class="nav-link {{ request()->routeIs('hiring.accepted') ? 'active' : '' }}">
                            <i class="bi bi-check-circle"></i>Accepted
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hiring.rejected') }}"
                            class="nav-link {{ request()->routeIs('hiring.rejected') ? 'active' : '' }}">
                            <i class="bi bi-x-circle"></i>Rejected
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hiring.newApplication') }}"
                            class="nav-link {{ request()->routeIs('hiring.newApplication') ? 'active' : '' }}">
                            <i class="bi bi-x-circle"></i>New Application
                            </a>
                        </li>
                    </ul>
                </div>
        

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
         @endif

        {{-- ── Leave Requests ── --}}
        

        {{-- ── Leave Requests ── --}}
        <div class="nav-item">
            <a href="#leaveMenu"
               class="nav-link {{ request()->routeIs('leave.*') ? 'active' : '' }}"
               data-bs-toggle="collapse"
               aria-expanded="false">
                <i class="bi bi-diagram-3"></i> Leaves
                <i class="bi bi-chevron-right arrow"></i>
            </a>
            <ul class="sub-menu collapse" id="leaveMenu">
                <li class="nav-item">
                    <a href="{{route('index')}}" class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}"><i class="bi bi-journal-text"></i>Leave Requests</a>
                </li>
               
                <li class="nav-item">
                    <a href="{{route('my')}}" class="nav-link {{ request()->routeIs('my') ? 'active' : '' }}"><i class="bi bi-journal-text"></i>My leaves</a>
                </li>
            
            </ul>
        </div>

        {{-- ── EOD Reports ── --}}
        <div class="nav-item">
            <a href="#eodMenu"
               class="nav-link {{ request()->routeIs('eod.*') ? 'active' : '' }}"
               data-bs-toggle="collapse"
               aria-expanded="false">
                <i class="bi bi-diagram-3"></i> EOD
                <i class="bi bi-chevron-right arrow"></i>
            </a>
            <ul class="sub-menu collapse" id="eodMenu">
                <li class="nav-item">
                    <a href="{{route('eod.index')}}" class="nav-link {{ request()->routeIs('eod.index') ? 'active' : '' }}"><i class="bi bi-journal-text"></i>EOD reports</a>
                </li>
                @if(Auth::user()->role_id !== 1)
                <li class="nav-item">
                    <a href="{{route('eod.create')}}" class="nav-link {{ request()->routeIs('eod.create') ? 'active' : '' }}"><i class="bi bi-journal-text"></i>Add EOD</a>
                </li>
                @endif
            </ul>
        </div>

        <!-- <div class="nav-item">
            <a href="{{route('eod.index')}}" class="nav-link {{ request()->routeIs('eod.*') || request()->routeIs('nfc.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> EOD Reports
            </a>
        </div> -->
      
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

        <!-- @if(Auth::user()->role_id === 1)
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
        @endif -->

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