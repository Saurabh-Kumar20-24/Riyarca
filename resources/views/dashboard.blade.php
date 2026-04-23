@extends('layouts.header')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

@if(session('success'))
<div class="flash-alert">
    <i class="bi bi-check-circle-fill"></i>
    {{ session('success') }}
</div>
@endif

<div class="welcome-banner">
    <div>
        <h2>Welcome back, {{ Auth::user()->name ?? 'User' }}! </h2>
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