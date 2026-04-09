@extends('layouts.header')

@section('title', 'My Leaves')
@section('page-title', 'My Leaves')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <h2>My Leave Requests</h2>
    <div class="header-actions">
        <button class="btn-search" onclick="openLeaveModal()">Apply Leave</button>
    </div>
</div>

{{-- Success / Error --}}
@if(session('success'))
    <div class="alert-success-custom">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert-danger-custom">{{ session('error') }}</div>
@endif


{{-- APPLY LEAVE MODAL --}}
<div id="leaveModal" class="modal">
    <div class="modal-content">

        <form method="POST" action="{{ route('store') }}" enctype="multipart/form-data">
            @csrf

            <h3>Apply Leave</h3>

            <div class="form-row">
                <div>
                    <label>Leave Type</label>
                    <select name="leave_type" required>
                        <option value="">Select</option>
                        <option value="casual">Casual</option>
                        <option value="sick">Sick</option>
                        <option value="earned">Earned</option>
                        <option value="optional">Optional</option>
                        <option value="emergency">Emergency</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="half_day">Half Day</option>
                    </select>
                </div>

                <div>
                    <label>Duration</label>
                    <select name="duration">
                        <option value="full">Full Day</option>
                        <option value="half">Half Day</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label>From Date</label>
                    <input type="date" name="from_date" required>
                </div>

                <div>
                    <label>To Date</label>
                    <input type="date" name="to_date" required>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label>Half Day Type</label>
                    <select name="half_day_type">
                        <option value="">Select</option>
                        <option value="first_half">First Half</option>
                        <option value="second_half">Second Half</option>
                    </select>
                </div>

                <div>
                    <label>Contact Email</label>
                    <input type="email" name="contact_email">
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label>Contact Phone</label>
                    <input type="text" name="contact_phone">
                </div>

                <div>
                    <label>Document</label>
                    <input type="file" name="document">
                </div>
            </div>

            <div>
                <label>Work Handover</label>
                <textarea name="handover"></textarea>
            </div>

            <div>
                <label>Reason</label>
                <textarea name="reason" required></textarea>
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn-search">Submit</button>
                <button type="button" onclick="closeLeaveModal()" class="btn-search">Cancel</button>
            </div>

        </form>
    </div>
</div>


{{-- Leave Balance Cards --}}
<div class="leave-section-title">My Leave Balance — {{ now()->year }}</div>

<div class="leave-summary" style="flex-wrap: wrap; gap: 12px; margin-bottom: 28px;">
    @foreach($balances as $type => $balance)
    <div class="leave-stat-card" style="min-width: 180px; flex: 1;">
        <div>
            <div class="leave-stat-label">{{ ucfirst($type) }}</div>

            <div style="display: flex; align-items: baseline; gap: 6px; margin-top: 6px;">
                <span class="leave-stat-value">{{ $balance->remaining }}</span>
                <span style="font-size: 12px; color: var(--muted);">/ {{ $balance->allocated }}</span>
            </div>

            @php
                $pct = $balance->allocated > 0
                    ? round(($balance->used / $balance->allocated) * 100)
                    : 0;
            @endphp

            <div style="margin-top: 8px; background: var(--border); border-radius: 4px; height: 4px;">
                <div style="width: {{ $pct }}%; background: var(--brand-gradient); height: 4px; border-radius: 4px;"></div>
            </div>

            <div style="display: flex; justify-content: space-between; margin-top: 5px; font-size: 11px; color: var(--muted);">
                <span>Used: {{ $balance->used }}</span>
                @if($balance->pending > 0)
                    <span style="color: #d97706;">Pending: {{ $balance->pending }}</span>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>


{{-- My Leave Requests Table --}}
<div class="leave-section-title">Leave History</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Type</th>
                <th>From</th>
                <th>To</th>
                <th>Days</th>
                <th>Status</th>
                <th>Reviewed By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($myLeaves as $i => $leave)
            <tr>
                <td>{{ $myLeaves->firstItem() + $i }}</td>
                <td>{{ ucfirst($leave->leave_type) }}</td>
                <td>{{ $leave->from_date->format('d M Y') }}</td>
                <td>{{ $leave->to_date->format('d M Y') }}</td>
                <td>{{ $leave->total_days }}</td>

                <td>
                    <span class="leave-status-{{ $leave->status }}">
                        {{ ucfirst($leave->status) }}
                    </span>
                </td>

                <td>{{ $leave->reviewer->name ?? '-' }}</td>

                <td>
                    @if($leave->status === 'pending')
                        <form method="POST" action="{{ route('cancel', $leave->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="leave-action-reject"
                                onclick="return confirm('Cancel this leave request?')">
                                Cancel
                            </button>
                        </form>
                    @else
                        <span class="leave-action-done">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="table-empty">No leave requests found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $myLeaves->links() }}
</div>

@endsection


@push('scripts')
<script>
    function openLeaveModal() {
        document.getElementById('leaveModal').style.display = 'flex';
    }

    function closeLeaveModal() {
        document.getElementById('leaveModal').style.display = 'none';
    }

    document.getElementById('leaveModal').addEventListener('click', function(e) {
        if (e.target === this) closeLeaveModal();
    });
</script>
@endpush