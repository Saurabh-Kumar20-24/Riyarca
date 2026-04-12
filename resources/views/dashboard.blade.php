@extends('layouts.header')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .flash-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #ecfdf5;
        border: 1px solid #6ee7b7;
        color: #065f46;
        padding: 10px 16px;
        border-radius: var(--radius);
        margin-bottom: 1.25rem;
        font-size: 13px;
        animation: slideDown .3s ease;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .welcome-banner {
        background: var(--brand-gradient);
        border-radius: var(--radius);
        padding: 1.35rem 1.5rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        box-shadow: 0 4px 20px rgba(106,47,224,.25);
        position: relative;
        overflow: hidden;
    }
    /* Decorative circle */
    .welcome-banner::after {
        content: '';
        position: absolute;
        right: -40px; top: -40px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
    }
    .welcome-banner h2 {
        font-size: 18px;
        color: #fff;
        font-weight: 700;
    }
    .welcome-banner p {
        font-size: 13px;
        color: rgba(255,255,255,.70);
        margin-top: 3px;
    }
    .role-badge {
        background: rgba(255,255,255,.15);
        color: #fff;
        padding: 5px 16px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 1px solid rgba(255,255,255,.25);
        white-space: nowrap;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }

   
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
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1px solid var(--border);
        transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }

   
    .si-blue   { background: rgba(30,94,217,.10);  color: var(--deep-blue); }
    .si-green  { background: #ecfdf5;               color: #16a34a; }
    .si-pink   { background: rgba(217,43,191,.10); color: var(--magenta-pink); }
    .si-purple { background: rgba(106,47,224,.10); color: var(--royal-purple); }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark-text);
        line-height: 1;
    }
    .stat-label {
        font-size: 12px;
        color: var(--muted);
        margin-top: 3px;
    }

    
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }
    @media (max-width: 900px) {
        .dashboard-grid { grid-template-columns: 1fr; }
    }

    
    .dash-card {
        background: var(--surface);
        border-radius: var(--radius);
        padding: 1.25rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
    }
    .dash-card h3 {
        font-size: 14px;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 1rem;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .dash-card h3 i {
        background: var(--brand-gradient-h);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .dash-card.full-width { grid-column: 1 / -1; }

   
    .dash-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .dash-table th {
        text-align: left;
        padding: 8px 10px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: var(--muted);
        border-bottom: 1px solid var(--border);
    }
    .dash-table td {
        padding: 9px 10px;
        border-bottom: 1px solid var(--border);
        color: var(--dark-text);
    }
    .dash-table tr:last-child td { border-bottom: none; }
    .dash-table tr:hover td      { background: var(--light-bg); }

    .badge-paid {
        background: #ecfdf5; color: #16a34a;
        padding: 2px 9px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
    }
    .badge-unpaid {
        background: rgba(217,43,191,.10); color: var(--magenta-pink);
        padding: 2px 9px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
    }

   
    .timeline { list-style: none; }
    .timeline li {
        display: flex;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
        font-size: 13px;
    }
    .timeline li:last-child { border-bottom: none; }
    .tl-dot {
        width: 9px; height: 9px;
        border-radius: 50%;
        background: var(--brand-gradient);
        flex-shrink: 0;
        margin-top: 5px;
        box-shadow: 0 0 6px rgba(106,47,224,.4);
    }
    .tl-time { font-size: 11px; color: var(--muted); margin-top: 2px; }

   
    .performer {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 0;
        border-bottom: 1px solid var(--border);
    }
    .performer:last-child { border-bottom: none; }
    .performer img,
    .performer .avatar-fallback {
        width: 36px; height: 36px;
        border-radius: 50%; object-fit: cover; flex-shrink: 0;
    }
    .performer .avatar-fallback {
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700; color: #fff;
        background: var(--brand-gradient);
    }
    .performer .p-meta        { flex: 1; }
    .performer .p-meta strong { font-size: 13px; display: block; color: var(--dark-text); }
    .performer .p-meta small  { color: var(--muted); }
    .performer .p-time        { font-size: 11px; color: var(--muted); }

    
    .progress-row      { margin-bottom: 12px; }
    .progress-label    { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 13px; color: var(--dark-text); }
    .progress-bar-track {
        height: 8px;
        background: var(--light-bg);
        border-radius: 10px;
        overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 10px;
        background: var(--brand-gradient-h);
        transition: width .6s ease;
    }

    
    @media (max-width: 576px) {
        .welcome-banner { flex-direction: column; align-items: flex-start; }
        .stat-grid      { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('content')

    @if(session('success'))
        <div class="flash-alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="welcome-banner">
        <div>
            <h2>Welcome back, {{ Auth::user()->name ?? 'User' }}! 👋</h2>
            <p>Here's what's happening in your workspace today.</p>
        </div>
        <span class="role-badge">{{ Auth::user()->role->role_name ?? 'Admin' }}</span>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon si-blue">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="stat-value">{{ $totalUsers ?? '—' }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon si-green">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div>
                <div class="stat-value">12</div>
                <div class="stat-label">Leave Requests</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon si-pink">
                <i class="bi bi-diagram-3"></i>
            </div>
            <div>
                <div class="stat-value">38</div>
                <div class="stat-label">Total Leads</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon si-purple">
                <i class="bi bi-journal-text"></i>
            </div>
            <div>
                <div class="stat-value">54</div>
                <div class="stat-label">EODs Today</div>
            </div>
        </div>
    </div>
@endsection