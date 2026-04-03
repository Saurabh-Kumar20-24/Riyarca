@include('layouts.header')

{{-- ──────────────────────────────────────────────────────────────
     DASHBOARD PAGE — resources/views/dashboard.blade.php
     Uses: layouts/header.blade.php → layouts/sidebar.blade.php
           layouts/footer.blade.php
────────────────────────────────────────────────────────────────── --}}

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

<style>
    /* ─── Flash Alert ─────────────────────────────────────── */
    .flash-alert {
        display: flex; align-items: center; gap: 10px;
        background: #ecfdf5;
        border: 1px solid #6ee7b7;
        color: #065f46;
        padding: 10px 16px;
        border-radius: var(--radius);
        margin-bottom: 1.25rem;
        font-size: 13px;
        animation: slideDown .3s ease;
    }
    @keyframes slideDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }

    /* ─── Welcome Banner ──────────────────────────────────── */
    .welcome-banner {
        background: linear-gradient(135deg, var(--primary) 0%, #1a4a8a 100%);
        border-radius: var(--radius);
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .welcome-banner h2 { font-size: 18px; color: #fff; font-weight: 700; }
    .welcome-banner p  { font-size: 13px; color: rgba(255,255,255,.65); margin-top: 3px; }
    .role-badge {
        background: rgba(255,255,255,.15);
        color: #fff;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 1px solid rgba(255,255,255,.2);
        white-space: nowrap;
    }

    /* ─── Stat Cards ──────────────────────────────────────── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.25rem;
    }
    .stat-card {
        background: var(--surface);
        border-radius: var(--radius);
        padding: 1.25rem;
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1px solid var(--border);
        transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 14px rgba(0,0,0,.09); }
    .stat-icon {
        width: 46px; height: 46px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }
    .si-blue   { background: #eef2f9; color: #0f3460; }
    .si-green  { background: #ecfdf5; color: #16a34a; }
    .si-red    { background: #fef2f2; color: #dc2626; }
    .si-purple { background: #f5f3ff; color: #7c3aed; }
    .stat-value { font-size: 24px; font-weight: 700; color: var(--text); line-height: 1; }
    .stat-label { font-size: 12px; color: var(--muted); margin-top: 3px; }

    /* ─── Content Grid ────────────────────────────────────── */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }
    @media (max-width: 900px) { .dashboard-grid { grid-template-columns: 1fr; } }

    /* ─── Card ────────────────────────────────────────────── */
    .dash-card {
        background: var(--surface);
        border-radius: var(--radius);
        padding: 1.25rem;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
    }
    .dash-card h3 {
        font-size: 14px; font-weight: 700; color: var(--text);
        margin-bottom: 1rem; padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
    }
    .dash-card.full-width { grid-column: 1 / -1; }

    /* ─── Table ───────────────────────────────────────────── */
    .dash-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .dash-table th {
        text-align: left; padding: 8px 10px;
        font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .5px;
        color: var(--muted); border-bottom: 1px solid var(--border);
    }
    .dash-table td { padding: 9px 10px; border-bottom: 1px solid var(--border); color: var(--text); }
    .dash-table tr:last-child td { border-bottom: none; }
    .dash-table tr:hover td { background: var(--bg); }
    .badge-paid   { background: #ecfdf5; color: #16a34a; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; }
    .badge-unpaid { background: #fef2f2; color: #dc2626; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; }

    /* ─── Activity Timeline ───────────────────────────────── */
    .timeline { list-style: none; }
    .timeline li {
        display: flex; gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
        font-size: 13px;
    }
    .timeline li:last-child { border-bottom: none; }
    .tl-dot {
        width: 8px; height: 8px;
        border-radius: 50%; background: var(--accent);
        flex-shrink: 0; margin-top: 5px;
    }
    .tl-time { font-size: 11px; color: var(--muted); margin-top: 2px; }

    /* ─── Performers List ─────────────────────────────────── */
    .performer {
        display: flex; align-items: center; gap: 10px;
        padding: 9px 0; border-bottom: 1px solid var(--border);
    }
    .performer:last-child { border-bottom: none; }
    .performer img, .performer .avatar-fallback {
        width: 36px; height: 36px;
        border-radius: 50%; object-fit: cover; flex-shrink: 0;
    }
    .performer .avatar-fallback {
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700; color: #fff;
    }
    .performer .p-meta { flex: 1; }
    .performer .p-meta strong { font-size: 13px; display: block; }
    .performer .p-meta small { color: var(--muted); }
    .performer .p-time { font-size: 11px; color: var(--muted); }

    /* ─── Progress Bars ───────────────────────────────────── */
    .progress-row { margin-bottom: 10px; }
    .progress-label { display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 13px; }
    .progress-bar-track {
        height: 8px; background: var(--bg);
        border-radius: 10px; overflow: hidden;
    }
    .progress-bar-fill { height: 100%; border-radius: 10px; transition: width .6s ease; }

    @media (max-width: 576px) {
        .welcome-banner { flex-direction: column; align-items: flex-start; }
        .stat-grid { grid-template-columns: 1fr 1fr; }
    }
</style>

{{-- Flash message --}}
@if(session('success'))
    <div class="flash-alert">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
@endif

{{-- Welcome banner --}}
<div class="welcome-banner">
    <div>
        <h2>Welcome back, {{ Auth::user()->name ?? 'User' }}!</h2>
        <p>Here's what's happening in your workspace today.</p>
    </div>
    <span class="role-badge">{{ Auth::user()->role->role_name ?? 'Admin' }}</span>
</div>

{{-- Stat cards --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon si-blue"><i class="bi bi-people-fill"></i></div>
        <div>
            <div class="stat-value">{{ $totalUsers ?? '—' }}</div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green"><i class="bi bi-calendar-check"></i></div>
        <div>
            <div class="stat-value">12</div>
            <div class="stat-label">Leave Requests</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-red"><i class="bi bi-diagram-3"></i></div>
        <div>
            <div class="stat-value">38</div>
            <div class="stat-label">Total Leads</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-purple"><i class="bi bi-journal-text"></i></div>
        <div>
            <div class="stat-value">54</div>
            <div class="stat-label">EODs Today</div>
        </div>
    </div>
</div>



@include('layouts.footer')