<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            z-index: 100;
        }
        .navbar .brand { color: #fff; font-size: 16px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; }
        .logout-btn {
            background: rgba(255,255,255,0.12);
            color: #fff;
            border: none;
            padding: 7px 20px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-btn:hover { background: rgba(255,255,255,0.22); }

        /* Layout */
        .layout { display: flex; margin-top: 55px; min-height: calc(100vh - 55px); }

        /* Sidebar */
        .sidebar {
            width: 230px;
            background: #fff;
            border-right: 1px solid #e0e0e0;
            padding: 1.2rem 0;
            position: fixed;
            top: 55px; left: 0; bottom: 0;
            overflow-y: auto;
        }

        .menu-group { margin-bottom: 6px; }
        .menu-group-title {
            font-size: 10px;
            font-weight: 700;
            color: #bbb;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 10px 1.5rem 4px;
        }

        .sidebar-menu { list-style: none; }
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 1.5rem;
            font-size: 13.5px;
            color: #444;
            text-decoration: none;
            transition: background .15s, color .15s;
            border-left: 3px solid transparent;
        }
        .sidebar-menu li a:hover {
            background: #f0f2f5;
            color: #0f3460;
            border-left-color: #0f3460;
        }
        .sidebar-menu li a.active {
            background: #eef2f9;
            color: #0f3460;
            font-weight: 600;
            border-left-color: #0f3460;
        }
        .sidebar-menu li a .icon { font-size: 15px; width: 20px; text-align: center; }

        /* Main */
        .main { margin-left: 230px; padding: 2rem; flex: 1; }

        /* Welcome */
        .welcome {
            background: #fff;
            border-radius: 10px;
            padding: 1.2rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .welcome h2 { font-size: 18px; color: #1a1a1a; }
        .welcome p  { font-size: 13px; color: #888; margin-top: 3px; }
        .role-badge {
            background: #eef2f9;
            color: #0f3460;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .card {
            background: #fff;
            border-radius: 10px;
            padding: 1.2rem 1.5rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .card .icon-box {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; flex-shrink: 0;
        }
        .icon-box.blue   { background: #eef2f9; }
        .icon-box.green  { background: #eafaf1; }
        .icon-box.orange { background: #fff4e5; }
        .icon-box.red    { background: #fdecea; }
        .icon-box.purple { background: #f3eeff; }
        .card .number { font-size: 22px; font-weight: 700; color: #1a1a1a; }
        .card .label  { font-size: 12px; color: #888; margin-top: 2px; }

        /* Content box */
        .content-box {
            background: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            min-height: 300px;
        }
        .content-box h3 {
            font-size: 16px; color: #1a1a1a;
            margin-bottom: 1rem; padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .content-box p { font-size: 14px; color: #aaa; text-align: center; margin-top: 80px; }

        /* Section pages hidden by default */
        .section { display: none; }
        .section.active { display: block; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <span class="brand">Riyarca</span>
        <div style="display:flex;align-items:center;gap:14px;">
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

    <div class="layout">

        <!-- Sidebar -->
        <div class="sidebar">

            <div class="menu-group">
                <div class="menu-group-title">Main</div>
                <ul class="sidebar-menu">
                    <li><a href="#" class="active" onclick="showSection('dashboard', this)"><span class="icon">🏠</span> Dashboard</a></li>
                </ul>
            </div>

            <div class="menu-group">
                <div class="menu-group-title">User Management</div>
                <ul class="sidebar-menu">
                    <li><a href="#" onclick="showSection('allusers', this)"><span class="icon">👥</span> All Users</a></li>
                    <li><a href="#" onclick="showSection('adduser', this)"><span class="icon">➕</span> Add User</a></li>
                    <li><a href="#" onclick="showSection('roles', this)"><span class="icon">🎭</span> Assign Roles</a></li>
                </ul>
            </div>

            <div class="menu-group">
                <div class="menu-group-title">Hierarchy</div>
                <ul class="sidebar-menu">
                    <li><a href="#" onclick="showSection('managerlist', this)"><span class="icon">👔</span> Manager List</a></li>
                    <li><a href="#" onclick="showSection('employeelist', this)"><span class="icon">👤</span> Employee List</a></li>
                </ul>
            </div>

            <div class="menu-group">
                <div class="menu-group-title">Operations</div>
                <ul class="sidebar-menu">
                    <li><a href="#" onclick="showSection('eod', this)"><span class="icon">📝</span> View All EODs</a></li>
                    <li><a href="#" onclick="showSection('leavelist', this)"><span class="icon">📋</span> Leave List</a></li>
                    <li><a href="#" onclick="showSection('leads', this)"><span class="icon">📊</span> Leads</a></li>
                </ul>
            </div>

            <div class="menu-group">
                <div class="menu-group-title">Settings</div>
                <ul class="sidebar-menu">
                    <li><a href="#" onclick="showSection('profile', this)"><span class="icon">👤</span> Profile</a></li>
                    <li><a href="#" onclick="showSection('systemsettings', this)"><span class="icon">⚙️</span> System Settings</a></li>
                    <li><a href="#" onclick="showSection('resetpassword', this)"><span class="icon">🔒</span> Reset Password</a></li>
                </ul>
            </div>

        </div>

        <!-- Main Content -->
        <div class="main">

            <!-- Welcome -->
            <div class="welcome">
                <div>
                    <h2>Welcome back, {{ Auth::user()->name }}!</h2>
                    <p>Here's what's happening today.</p>
                </div>
                <span class="role-badge">{{ Auth::user()->role->role_name }}</span>
            </div>

            <!-- ── DASHBOARD ── -->
            <div id="dashboard" class="section active">
                <div class="cards">
                    <div class="card">
                        <div class="icon-box blue">👥</div>
                        <div><div class="number">24</div><div class="label">Total Users</div></div>
                    </div>
                    <div class="card">
                        <div class="icon-box green">👔</div>
                        <div><div class="number">6</div><div class="label">Managers</div></div>
                    </div>
                    <div class="card">
                        <div class="icon-box orange">📋</div>
                        <div><div class="number">12</div><div class="label">Leave Requests</div></div>
                    </div>
                    <div class="card">
                        <div class="icon-box red">📊</div>
                        <div><div class="number">38</div><div class="label">Total Leads</div></div>
                    </div>
                    <div class="card">
                        <div class="icon-box purple">📝</div>
                        <div><div class="number">54</div><div class="label">EODs Today</div></div>
                    </div>
                </div>
                <div class="content-box">
                    <h3>Dashboard Overview</h3>
                    <p>Summary statistics shown above. Select a menu item to manage.</p>
                </div>
            </div>

            <!-- ── ALL USERS ── -->
            <div id="allusers" class="section">
                <div class="content-box">
                    <h3>All Users</h3>
                    <p>User list will appear here. Connect to your UsersController.</p>
                </div>
            </div>

            <!-- ── ADD USER ── -->
            <div id="adduser" class="section">
                <div class="content-box">
                    <h3>Add User</h3>
                    <p>Add user form will appear here.</p>
                </div>
            </div>

            <!-- ── ASSIGN ROLES ── -->
            <div id="roles" class="section">
                <div class="content-box">
                    <h3>Assign Roles</h3>
                    <p>Role assignment panel will appear here.</p>
                </div>
            </div>

            <!-- ── MANAGER LIST ── -->
            <div id="managerlist" class="section">
                <div class="content-box">
                    <h3>Manager List</h3>
                    <p>Manager list will appear here.</p>
                </div>
            </div>

            <!-- ── EMPLOYEE LIST ── -->
            <div id="employeelist" class="section">
                <div class="content-box">
                    <h3>Employee List</h3>
                    <p>Employee list will appear here.</p>
                </div>
            </div>

            <!-- ── EOD ── -->
            <div id="eod" class="section">
                <div class="content-box">
                    <h3>View All EODs</h3>
                    <p>All EOD reports will appear here.</p>
                </div>
            </div>

            <!-- ── LEAVE LIST ── -->
            <div id="leavelist" class="section">
                <div class="content-box">
                    <h3>Leave List</h3>
                    <p>Leave requests will appear here.</p>
                </div>
            </div>

            <!-- ── LEADS ── -->
            <div id="leads" class="section">
                <div class="content-box">
                    <h3>Leads</h3>
                    <p>Leads data will appear here.</p>
                </div>
            </div>

            <!-- ── PROFILE ── -->
            <div id="profile" class="section">
                <div class="content-box">
                    <h3>Profile</h3>
                    <p>Profile details will appear here.</p>
                </div>
            </div>

            <!-- ── SYSTEM SETTINGS ── -->
            <div id="systemsettings" class="section">
                <div class="content-box">
                    <h3>System Settings</h3>
                    <p>System configuration options will appear here.</p>
                </div>
            </div>

            <!-- ── RESET PASSWORD ── -->
            <div id="resetpassword" class="section">
                <div class="content-box">
                    <h3>Reset Password</h3>
                    <p>Password reset form will appear here.</p>
                </div>
            </div>

        </div>
    </div>

    <script>
        function showSection(section, el) {
            document.querySelectorAll('.sidebar-menu li a').forEach(a => a.classList.remove('active'));
            el.classList.add('active');
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            document.getElementById(section).classList.add('active');
        }
    </script>

</body>
</html>