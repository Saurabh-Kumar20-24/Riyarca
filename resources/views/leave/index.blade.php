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
                <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="btn-search">Filter</button>
            <a href="{{ route('index') }}" class="btn-search">Reset</a>
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
@endif


<div class="leave-section-title">All Leave Requests</div>

<div class="table-card">
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

                <!-- <td>
                    @if($leave->status === 'pending')

                        <form method="POST" action="{{ route('updateStatus', $leave->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="leave-action-approve">Approve</button>
                        </form>

                        <form method="POST" action="{{ route('updateStatus', $leave->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="leave-action-reject">Reject</button>
                        </form>

                    @else
                        <span class="leave-action-done">—</span>
                    @endif
                </td> -->
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $allLeaves->links() }}
</div>

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