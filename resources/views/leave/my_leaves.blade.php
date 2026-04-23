@extends('layouts.header')

@section('title', 'My Leaves')
@section('page-title', 'My Leaves')

@section('content')

<div class="page-header">
    <h2>My Leave Requests</h2>
    <div class="header-actions">
        <button class="btn-search" onclick="openLeaveModal()">Apply Leave</button>
    </div>
</div>

@if(session('success'))
<div class="alert-success-custom">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert-danger-custom">{{ session('error') }}</div>
@endif


<div id="leaveModal" class="modal">
    <div class="modal-content">

        <form id="leaveForm" enctype="multipart/form-data">
            @csrf

            <h3>Apply Leave</h3>

            <div class="form-row">
                <div>
                    <label>Leave Type</label>
                    <select name="leave_type" id="leave_type" required>
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


                <div id="halfDayBox" style="display:none;">
                    <div>
                        <label>Day Type</label>
                        <select name="half_day_type">
                            <option value="">Select</option>
                            <option value="first_half">First Half</option>
                            <option value="second_half">Second Half</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="form-row">
                <div>
                    <label>From Date</label>
                    <input type="date" name="from_date" min="{{ date('Y-m-d') }}" required>
                </div>

                <div>
                    <label>To Date</label>
                    <input type="date" name="to_date" min="{{ date('Y-m-d') }}" required>
                </div>
            </div>



            <div class="form-row" id="documentBox" style="display:none;">
                <div>
                    <label>Document (if more than 5 days)</label>
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
                <button type="submit" id="submitBtn" class="btn-search ">Submit</button>
                <button type="button" onclick="closeLeaveModal()" class="btn-search">Cancel</button>
            </div>

        </form>
    </div>
</div>


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


<div class="leave-section-title">Leave History</div>

<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>S.No.</th>
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
</div>

<div class="pagination-wrap">
    {{ $myLeaves->links() }}
</div>

@endsection


@push('scripts')
<script>
    document.querySelector('input[name="from_date"]').addEventListener('change', function() {
        document.querySelector('input[name="to_date"]').min = this.value;
    });
    const leaveType = document.getElementById('leave_type');
    const halfDayBox = document.getElementById('halfDayBox');

    leaveType.addEventListener('change', function() {
        halfDayBox.style.display = this.value === 'half_day' ? 'flex' : 'none';
    });

    function openLeaveModal() {
        document.getElementById('leaveModal').style.display = 'flex';
    }

    function closeLeaveModal() {
        document.getElementById('leaveModal').style.display = 'none';
    }

    document.getElementById('leaveModal').addEventListener('click', function(e) {
        if (e.target === this) closeLeaveModal();
    });

    document.getElementById('leaveForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let form = this;
        let formData = new FormData(form);
        let submitBtn = document.getElementById('submitBtn');

        // Loading state
        submitBtn.disabled = true;
        submitBtn.innerText = 'Submitting...';

        fetch("{{ route('store') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(async res => {
                if (!res.ok) {
                    let text = await res.text();
                    console.error('Server Response:', text);
                    throw new Error('Server error');
                }
                return res.json();
            })
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Submit';

                if (data.success) {
                    alert('Leave applied successfully');
                    closeLeaveModal();
                    location.reload(); // or update UI dynamically
                } else {
                    alert(data.message || 'Something went wrong');
                }
            })
            .catch(async (err) => {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Submit';
                console.log('Error:', err);
                // 👇 Try to read Laravel response
                const res = err?.response;
                if (res) {
                    console.log(await res.text());
                }
                alert('Check console (F12) for real error');
            });
    });


    const fromDateInput = document.querySelector('input[name="from_date"]');
    const toDateInput = document.querySelector('input[name="to_date"]');
    const documentBox = document.getElementById('documentBox');

    function calculateDays(from, to) {
        if (!from || !to) return 0;

        let start = new Date(from);
        let end = new Date(to);

        let diffTime = end - start;
        let days = (diffTime / (1000 * 60 * 60 * 24)) + 1; // inclusive

        return days;
    }

    function toggleDocumentField() {
        let from = fromDateInput.value;
        let to = toDateInput.value;

        let days = calculateDays(from, to);

        if (days > 5) {
            documentBox.style.display = 'block';
        } else {
            documentBox.style.display = 'none';
        }
    }
    fromDateInput.addEventListener('change', toggleDocumentField);
    toDateInput.addEventListener('change', toggleDocumentField);
</script>
@endpush