@extends('layouts.header')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@section('content')

<div class="page-header">
    <h2>Attendance — {{ $employees->name }}</h2>
    <div class="header-actions">

        {{-- Date Filter --}}
        <!-- <form method="GET"
              action="{{ route('attendence.ShowAttendence') }}"
              class="d-flex gap-2">
            <input type="hidden" name="emp_id" value="{{ $employees->id }}">
            <input type="date" name="from" class="search-input"
                   value="{{ request('from') }}">
            <input type="date" name="to" class="search-input"
                   value="{{ request('to') }}">
            <button type="submit" class="btn-search">Filter</button>
            <a href="{{ route('attendence.ShowAttendence') }}?emp_id={{ $employees->id }}"
               class="btn-search" style="background:#6b7280;">Reset</a>
        </form> -->

        <a href="{{ route('attendence.index') }}"
           class="btn-add" style="background:#6b7280;">← Back</a>
    </div>
</div>

{{-- Employee Info --}}
<!-- <div style="background:#1e293b; border-radius:12px; padding:16px 24px;
    margin-bottom:20px; display:flex; flex-wrap:wrap;
    gap:24px; color:#fff; font-size:14px;">
    <div><span style="color:#94a3b8">Name:</span>
        <strong>{{ $employees->name }}</strong></div>
    <div><span style="color:#94a3b8">Email:</span>
        {{ $employees->email }}</div>
    <div><span style="color:#94a3b8">Employee ID:</span>
        <strong>{{ $employees->employee_id ?? '-' }}</strong></div>
    <div><span style="color:#94a3b8">Role:</span>
        {{ $employees->role->role_name ?? '-' }}</div>
    <div><span style="color:#94a3b8">Total Records:</span>
        <strong>{{ $attendances->total() }}</strong></div>
</div> -->

{{-- Table --}}
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Date</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Total Hours</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $i => $log)
            <tr>
                <td>{{ $attendances->firstItem() + $i }}</td>

                <!-- Carbon to convert string into date object -->

                <td>{{ \Carbon\Carbon::parse($log->attendance_date)->format('d M Y') }}</td>
                <td>{{ $log->check_in
                        ? \Carbon\Carbon::parse($log->check_in)->format('h:i A')
                        : '-' }}</td>
                <td>{{ $log->check_out
                        ? \Carbon\Carbon::parse($log->check_out)->format('h:i A')
                        : '-' }}</td>
                <td>{{ $log->total_hours ? $log->total_hours . ' hrs' : '-' }}</td>
                <td>
                    @php $status = strtolower($log->status ?? 'present'); @endphp
                    <span style="padding:3px 12px; border-radius:99px;
                        font-size:12px; font-weight:600;
                        background:{{ $status === 'present' ? '#064e3b' : '#450a0a' }};
                        color:{{ $status === 'present' ? '#10b981' : '#ef4444' }};">
                        {{ ucfirst($status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;
                    color:#aaa; padding:2rem;">
                    No attendance records found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div style="margin-top:16px;">
    {{ $attendances->appends(['emp_id' => $employees->id])->links() }}
</div>
@endsection





