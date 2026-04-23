@extends('layouts.header')

@section('title', 'Leave')
@section('page-title', 'Leave')

@section('content')

<div class="page-header">
    <h2>Leave Requests</h2>

    <div class="header-actions">
        <form method="GET" action="{{ route('index') }}" class="d-flex gap-2">
            <select name="status" class="search-input">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending'  ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="btn-search">Filter</button>
            <a href="{{ route('index') }}" class="btn-search ">Reset</a>
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert-success-custom">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert-danger-custom">{{ session('error') }}</div>
@endif


<div class="leave-summary">
    <div class="leave-stat-card leave-stat-green">
        <div class="leave-stat-label">Currently On Leave</div>
        <div class="leave-stat-value">{{ $onLeave->count() }}</div>
    </div>

    <div class="leave-stat-card leave-stat-yellow">
        <div class="leave-stat-label">Pending</div>
        <div class="leave-stat-value">{{ $pending->count() }}</div>
    </div>

    <div class="leave-stat-card leave-stat-blue">
        <div class="leave-stat-label">Upcoming</div>
        <div class="leave-stat-value">{{ $upcoming->count() }}</div>
    </div>
</div>


@if($onLeave->count())
<div class="leave-section-title">Currently On Leave</div>

<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Days</th>
                    <th>Contact</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @foreach($onLeave as $leave)
                <tr>
                    <td>{{ $leave->user->name }}</td>
                    <td>{{ ucfirst($leave->leave_type) }}</td>
                    <td>{{ $leave->from_date->format('d M Y') }}</td>
                    <td>{{ $leave->to_date->format('d M Y') }}</td>
                    <td>{{ $leave->total_days }}</td>
                    <td>{{$leave->user->phone}}</td>
                    <td>{{ $leave->reason }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif



<div class="leave-section-header">
    <div class="leave-section-title" style="margin-bottom:0;">All Leave Requests</div>


    <div class="export-actions">
        <a href="{{ route('leave.exportCsv', request()->only('status')) }}"
            class="btn-export btn-export-csv"
            title="Download table as CSV">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="7 10 12 15 17 10" />
                <line x1="12" y1="15" x2="12" y2="3" />
            </svg>
            Download CSV
        </a>

        <a href="{{ route('leave.exportPdf', request()->only('status')) }}"
            class="btn-export btn-export-pdf"
            title="Download table as PDF">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <line x1="16" y1="13" x2="8" y2="13" />
                <line x1="16" y1="17" x2="8" y2="17" />
                <polyline points="10 9 9 9 8 9" />
            </svg>
            Download PDF
        </a>
    </div>
</div>

<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Type</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Days</th>
                    <th>Status</th>
                    <th>Contact</th>
                    <th>Reviewed By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allLeaves as $i => $leave)
                <tr>
                    <td>{{ $allLeaves->firstItem() + $i }}</td>
                    <td>{{ $leave->user->name }}</td>
                    <td>{{ ucfirst($leave->leave_type) }}</td>
                    <td>{{ $leave->from_date->format('d M Y') }}</td>
                    <td>{{ $leave->to_date->format('d M Y') }}</td>
                    <td>{{ $leave->total_days }}</td>

                    <td>
                        <span class="leave-status-{{ $leave->status }}">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </td>

                    <td>{{$leave->user->phone}}</td>

                    <td>{{ $leave->reviewer->name ?? '-' }}</td>

                    <td>
                        @if($leave->status === 'pending')
                        <div class="action-menu">
                            <button class="action-toggle" onclick="toggleMenu(this)">
                                &#8942;
                            </button>
                            <div class="action-dropdown">
                                <form method="POST" action="{{ route('updateStatus', $leave->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="dropdown-item approve-btn">
                                        Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('updateStatus', $leave->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="dropdown-item reject-btn">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <span class="leave-action-done">—</span>
                        @endif
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="pagination-wrap">
    {{ $allLeaves->links() }}
</div>

<style>
    .leave-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px;
        margin-top: 24px;
    }

    .export-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: opacity 0.15s ease, transform 0.1s ease;
        white-space: nowrap;
        border: none;
    }

    .btn-export:hover {
        opacity: 0.85;
        transform: translateY(-1px);
    }

    .btn-export:active {
        transform: translateY(0);
    }

    .btn-export-csv {
        background: #3be278;
        color: #fff;
    }

    .btn-export-pdf {
        background: #d64949;
        color: #fff;
    }
</style>

<script>
    function toggleMenu(button) {
        const menu = button.nextElementSibling;
        document.querySelectorAll('.action-dropdown').forEach(el => {
            if (el !== menu) el.style.display = 'none';
        });
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.action-menu')) {
            document.querySelectorAll('.action-dropdown').forEach(el => {
                el.style.display = 'none';
            });
        }
    });
</script>

@endsection