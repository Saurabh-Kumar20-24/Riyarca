@extends('layouts.header')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@section('content')

<div class="page-header">
    <h2>Attendance — {{ $employees->name }}</h2>
    <div class="header-actions">

        <a href="{{ route('attendence.index') }}"
           class="btn-add" style="background:#6b7280;">← Back</a>
    </div>
</div>


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
                  @php $status = strtolower($log->late_status ?? 'on time'); @endphp
                    <span style="padding:3px 12px; border-radius:99px;
                        font-size:12px; font-weight:600;
                        background:{{ $status === 'late' ? '#bc1717' : '#129a76' }};
                        color:{{ $status === 'late' ? '#f9f1f1' : '#e2eeea' }};">
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





