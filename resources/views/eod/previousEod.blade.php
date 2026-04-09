@extends('layouts.header')

@section('title', 'EOD Reports')
@section('page-title', 'EOD Reports')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <h2>EOD Reports — {{ $employee->name }}</h2>
    <div class="header-actions">
        <form method="GET" action="{{ route('eod.previousEods') }}" class="d-flex gap-2">
            <input type="hidden" name="emp_id" value="{{ $employee->id }}">
            <input type="date" name="from" class="search-input" value="{{ request('from') }}">
            <input type="date" name="to"   class="search-input" value="{{ request('to') }}">
            <button type="submit" class="btn-search">Filter</button>
            <a href="{{ route('eod.previousEods', ['emp_id' => $employee->id]) }}" class="btn-search">Reset</a>
        </form>
        <a href="{{ route('eod.index') }}" class="btn-back">← Back</a>
    </div>
</div>

{{-- Employee Info Card --}}
<div class="emp-info-card">
    <div class="emp-info-item">
        <div class="emp-info-label">Employee</div>
        <div class="emp-info-value">{{ $employee->name }}</div>
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
        <div class="emp-info-value" style="color:var(--deep-blue);">
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
            <div class="eod-date-box">
                <div class="eod-date-day">{{ $eod->report_date->format('d') }}</div>
                <div class="eod-date-month">{{ $eod->report_date->format('M') }}</div>
            </div>
            <div>
                <div class="eod-report-title">{{ $eod->report_date->format('d M Y') }}</div>
                <div class="eod-report-sub">
                    {{ $eod->report_date->format('l') }}
                    &bull;
                    Submitted at {{ $eod->created_at->format('h:i A') }}
                </div>
            </div>
        </div>
        <div class="eod-task-count">{{ count($eod->tasks_completed) }} task(s)</div>
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
    <div class="eod-empty-sub">This employee has not submitted any EOD reports yet.</div>
</div>
@endforelse

{{-- Pagination --}}
<div style="margin-top:20px;">
    {{ $eods->links() }}
</div>

@endsection