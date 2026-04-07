@include('layouts.header')
<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

{{-- Page Header --}}
<div class="page-header">
    <h2>Leave Requests</h2>
    <div class="header-actions">
        <form method="GET" action="{{ route('leave.index') }}" class="d-flex gap-2">
            <select name="status" class="search-input">
                <option value="">All Status</option>
                <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <input type="date" name="from" class="search-input" value="{{ request('from') }}">
            <input type="date" name="to"   class="search-input" value="{{ request('to') }}">
            <button type="submit" class="btn-search">Filter</button>
            <a href="{{ route('leave.index') }}" class="btn-search" style="background:#475569;">Reset</a>
        </form>
    </div>
</div>

{{-- Summary Cards --}}
<div class="leave-summary">
    <div class="leave-stat-card" style="border-color:rgba(16,185,129,.3);">
        <div class="leave-stat-icon" style="background:rgba(16,185,129,.15); color:#10b981;">
            <i class="bi bi-person-check"></i>
        </div>
        <div>
            <div class="leave-stat-label">Currently On Leave</div>
            <div class="leave-stat-value" style="color:#10b981;">{{ $onLeave->count() }}</div>
        </div>
    </div>
    <div class="leave-stat-card" style="border-color:rgba(234,179,8,.3);">
        <div class="leave-stat-icon" style="background:rgba(234,179,8,.15); color:#eab308;">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div>
            <div class="leave-stat-label">Pending Requests</div>
            <div class="leave-stat-value" style="color:#eab308;">{{ $pending->count() }}</div>
        </div>
    </div>
    <div class="leave-stat-card" style="border-color:rgba(59,130,246,.3);">
        <div class="leave-stat-icon" style="background:rgba(59,130,246,.15); color:#60a5fa;">
            <i class="bi bi-calendar-event"></i>
        </div>
        <div>
            <div class="leave-stat-label">Upcoming Leaves</div>
            <div class="leave-stat-value" style="color:#60a5fa;">{{ $upcoming->count() }}</div>
        </div>
    </div>
</div>

{{-- Currently On Leave --}}
@if($onLeave->count())
<div class="leave-section-title">
    <i class="bi bi-person-check" style="color:#10b981;"></i> Currently On Leave
</div>
<div class="table-card" style="margin-bottom:24px;">
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Role</th>
                <th>Leave Type</th>
                <th>From</th>
                <th>To</th>
                <th>Days</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach($onLeave as $leave)
            <tr>
                <td>{{ $leave->user->name }}</td>
                <td>{{ $leave->user->role->role_name ?? '-' }}</td>
                <td><span class="leave-type-badge">{{ ucfirst($leave->leave_type) }}</span></td>
                <td>{{ $leave->from_date->format('d M Y') }}</td>
                <td>{{ $leave->to_date->format('d M Y') }}</td>
                <td>{{ $leave->total_days }} day(s)</td>
                <td>{{ $leave->reason ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- All Leave Requests Table --}}
<div class="leave-section-title">
    <i class="bi bi-journal-text" style="color:#60a5fa;"></i> All Leave Requests
</div>
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Employee</th>
                <th>Role</th>
                <th>Leave Type</th>
                <th>From</th>
                <th>To</th>
                <th>Days</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Reviewed By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allLeaves as $i => $leave)
            <tr>
                <td>{{ $allLeaves->firstItem() + $i }}</td>
                <td>{{ $leave->user->name }}</td>
                <td>{{ $leave->user->role->role_name ?? '-' }}</td>
                <td><span class="leave-type-badge">{{ ucfirst($leave->leave_type) }}</span></td>
                <td>{{ $leave->from_date->format('d M Y') }}</td>
                <td>{{ $leave->to_date->format('d M Y') }}</td>
                <td>{{ $leave->total_days }} day(s)</td>
                <td>{{ $leave->reason ?? '-' }}</td>
                <td>
                    <span class="leave-status-badge leave-status-{{ $leave->status }}">
                        {{ ucfirst($leave->status) }}
                    </span>
                </td>
                <td>{{ $leave->reviewer->name ?? '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align:center; color:#94a3b8; padding:2rem;">
                    No leave requests found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div style="margin-top:20px;">
    {{ $allLeaves->links() }}
</div>

<style>
/* ── Summary Cards ───────────────────────────────── */
.leave-summary {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 24px;
}
.leave-stat-card {
    background: var(--primary);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    flex: 1;
    min-width: 200px;
}
.leave-stat-icon {
    width: 44px; height: 44px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.leave-stat-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: rgba(255,255,255,.35);
    margin-bottom: 4px;
}
.leave-stat-value {
    font-size: 24px;
    font-weight: 800;
    line-height: 1;
}

/* ── Section Title ───────────────────────────────── */
.leave-section-title {
    font-size: 13.5px;
    font-weight: 700;
    color: rgba(23, 20, 20, 0.81);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ── Leave Type Badge ────────────────────────────── */
.leave-type-badge {
    background: rgba(59,130,246,.15);
    color: #60a5fa;
    border: 1px solid rgba(59,130,246,.3);
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}

/* ── Status Badge ────────────────────────────────── */
.leave-status-badge {
    padding: 3px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}
.leave-status-pending  { background:rgba(234,179,8,.15);  color:#eab308; border:1px solid rgba(234,179,8,.3); }
.leave-status-approved { background:rgba(16,185,129,.15); color:#10b981; border:1px solid rgba(16,185,129,.3); }
.leave-status-rejected { background:rgba(239,68,68,.15);  color:#ef4444; border:1px solid rgba(239,68,68,.3); }
</style>

@include('layouts.footer')