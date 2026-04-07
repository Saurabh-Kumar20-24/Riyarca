@extends('layouts.header')

@section('title', 'EodPrevious')
@section('page-title', 'EodPrevious')
<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@section('content')
{{-- Page Header --}}
<div class="page-header">
    <h2>EOD Reports — {{ $employee->name }}</h2>
    <div class="header-actions">

        {{-- Date Filter --}}
        <form method="GET"
            action="{{ route('eod.previousEods') }}"
            class="d-flex gap-2">

            <input type="hidden" name="emp_id" value="{{ $employee->id }}">

            <input type="date" name="from" class="search-input"
                value="{{ request('from') }}">
            <input type="date" name="to" class="search-input"
                value="{{ request('to') }}">
            <button type="submit" class="btn-search">Filter</button>
            <a href="{{ route('eod.previousEods', ['emp_id' => $employee->id]) }}"
                class="btn-search" style="background:#475569;">
                Reset
            </a>
        </form>

        <a href="{{ route('eod.index') }}" class="btn-add" style="background:#475569;">
            ← Back
        </a>
    </div>
</div>

{{-- Employee Info Card --}}
<div class="emp-info-card">
    <div class="emp-info-item">
        <div class="emp-info-label">Employee</div>
        <div class="emp-info-value" style="color:#fff;">{{ $employee->name }}</div>
    </div>
    <div class="emp-info-item">
        <div class="emp-info-label">Email</div>
        <div class="emp-info-value">{{ $employee->email }}</div>
    </div>
    <div class="emp-info-item">
        <div class="emp-info-label">Role</div>
        <div class="emp-info-value">{{ $employee->role->role_name ?? '-' }}</div>
    </div>
    <div class="emp-info-item">
        <div class="emp-info-label">Employee ID</div>
        <div class="emp-info-value" style="color:var(--accent);">
            {{ $employee->employee_id ?? '-' }}
        </div>
    </div>
    <div class="emp-info-item">
        <div class="emp-info-label">Total EODs</div>
        <div class="emp-info-value" style="color:var(--success);">
            {{ $eods->total() }}
        </div>
    </div>
</div>

{{-- EOD Cards --}}
@forelse($eods as $eod)
<div class="eod-report-card">

    {{-- Card Header --}}
    <div class="eod-report-header">
        <div class="eod-report-left">

            {{-- Date Box --}}
            <div class="eod-date-box">
                <div class="eod-date-day">{{ $eod->report_date->format('d') }}</div>
                <div class="eod-date-month">{{ $eod->report_date->format('M') }}</div>
            </div>

            <div>
                <div class="eod-report-title">
                    {{ $eod->report_date->format('d M Y') }}
                </div>
                <div class="eod-report-sub">
                    {{ $eod->report_date->format('l') }}
                    &bull;
                    Submitted at {{ $eod->created_at->format('h:i A') }}
                </div>
            </div>
        </div>

        {{-- Task Count Badge --}}
        <div class="eod-task-count">
            {{ count($eod->tasks_completed) }} task(s)
        </div>
    </div>

    {{-- Tasks Table --}}
    <table class="eod-task-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Task</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eod->tasks_completed as $index => $task)
            <tr>
                <td class="eod-task-index">{{ $index + 1 }}</td>
                <td class="eod-task-text">{{ $task }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@empty
<div class="eod-empty">
    <div class="eod-empty-icon">📋</div>
    <div class="eod-empty-title">No EOD Reports Found</div>
    <div class="eod-empty-sub">
        This employee has not submitted any EOD reports yet.
    </div>
</div>
@endforelse

{{-- Pagination --}}
<div style="margin-top:20px;">
    {{ $eods->links() }}
</div>

<style>
/* ── Employee Info Card ───────────────────────────── */
.emp-info-card {
    background: var(--primary);
    border-radius: 12px;
    padding: 16px 24px;
    margin-bottom: 24px;
    display: flex;
    flex-wrap: wrap;
    gap: 28px;
    border: 1px solid rgba(255,255,255,.08);
}
.emp-info-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: rgba(255,255,255,.35);
    margin-bottom: 4px;
}
.emp-info-value {
    font-size: 14px;
    font-weight: 600;
    color: rgba(255,255,255,.65);
}

/* ── EOD Report Card ──────────────────────────────── */
.eod-report-card {
    background: var(--primary);
    border-radius: 12px;
    padding: 20px 24px;
    margin-bottom: 16px;
    border: 1px solid rgba(255,255,255,.08);
    transition: border-color .18s, background .18s;
}


/* Card Header */
.eod-report-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    padding-bottom: 14px;
    border-bottom: 1px solid rgba(255,255,255,.08);
}
.eod-report-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Date Box */
.eod-date-box {
    background: rgba(59,130,246,.15);
    border: 1px solid rgba(59,130,246,.3);
    border-radius: 10px;
    padding: 8px 14px;
    text-align: center;
    min-width: 56px;
}
.eod-date-day {
    font-size: 18px;
    font-weight: 800;
    color: var(--accent);
    line-height: 1;
}
.eod-date-month {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: rgba(255,255,255,.35);
    margin-top: 2px;
}

/* Title & subtitle */
.eod-report-title {
    font-size: 15px;
    font-weight: 700;
    color: #fff;
}
.eod-report-sub {
    font-size: 11px;
    color: rgba(255,255,255,.35);
    margin-top: 3px;
    letter-spacing: .3px;
}

/* Task count badge */
.eod-task-count {
    background: rgba(59,130,246,.25);
    color: var(--accent);
    border: 1px solid rgba(59,130,246,.3);
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
}

/* ── Tasks Table ──────────────────────────────────── */
.eod-task-table {
    width: 100%;
    border-collapse: collapse;
}
.eod-task-table thead th {
    text-align: left;
    padding: 8px 12px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: rgba(255,255,255,.35);
    border-bottom: 1px solid rgba(255,255,255,.08);
}
.eod-task-table tbody tr {
    border-bottom: 1px solid rgba(255,255,255,.05);
    transition: background .15s;
}
.eod-task-table tbody tr:last-child { border-bottom: none; }
.eod-task-table tbody tr:hover { background: rgba(255,255,255,.04); }
.eod-task-index {
    padding: 10px 12px;
    color: rgba(255,255,255,.35);
    font-size: 12px;
    width: 32px;
}
.eod-task-text {
    padding: 10px 12px;
    color: rgba(255,255,255,.65);
    font-size: 13.5px;
    font-weight: 500;
}

/* ── Empty State ──────────────────────────────────── */
.eod-empty {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--primary);
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,.08);
}
.eod-empty-icon { font-size: 40px; margin-bottom: 12px; }
.eod-empty-title {
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 6px;
}
.eod-empty-sub {
    font-size: 13px;
    color: rgba(255,255,255,.35);
}
</style>

@endsection