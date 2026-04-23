@extends('layouts.header')

@section('title', 'Attendence')
@section('page-title', 'Attendence')

<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@section('content')

@if(session('success'))
<div id="successAlert" class="alert-success-custom">{{ session('success') }}</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    {{ $errors->first() }}
</div>
@endif

<div class="page-header">
    <h2>Employee List</h2>

    <div class="header-actions">

        <form method="GET" action="{{ route('attendence.index') }}" class="d-flex gap-2">
            <input type="text"
                name="search"
                class="search-input"
                placeholder="Search employee..."
                value="{{ request('search') }}">
            <button type="submit" class="btn-search">Search</button>
        </form>
        @if(Auth::user()->role_id == 7)
        <a href="javascript:void(0)" class="btn-add" onclick="openAttendanceModal()">+ Add Attendance</a>
        @endif
    </div>
</div>

<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Manager</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $i => $emp)
                <tr>
                    <td>{{ $employees->firstItem() + $i }}</td>
                    <td>{{ $emp->name }}</td>
                    <td>{{ $emp->email }}</td>
                    <td>{{ $emp->role->role_name ?? '-' }}</td>
                    <td>{{ $emp->attendence ? \Carbon\Carbon::parse($emp->attendence->attendance_date)->format('d M Y') : '-' }}</td>
                 
                    <td>
                        @if($emp->attendence)
                        @if($emp->attendence->status === 'present')
                        <span class="badge-active">Present</span>
                        @elseif($emp->attendence->status === 'half_day')
                        <span class="badge-inactive" style="background-color:#fd7e14;">Half Day</span>
                        @elseif($emp->attendence->status === 'absent')
                        <span class="badge-inactive" style="background-color:#dc3545;">Absent</span>
                        @elseif($emp->attendence->status === 'pending')
                        <span class="badge-inactive" style="background-color:#ffc107; color:#000;">Pending</span>
                        @else
                        <span style="color:#aaa;">No Attendance</span>
                        @endif
                        @else
                        <span style="color:#aaa;">No Attendance</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-menu">
                            <button class="action-toggle" onclick="toggleMenu(this)" title="Actions">
                                &#8942;
                            </button>
                            <div class="action-dropdown">
                                <a href="{{ route('attendence.ShowAttendence', ['emp_id' => $emp->id]) }}">
                                    <i class="bi bi-clock-history"></i> Previous
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center; color:#aaa; padding: 2rem;">
                        No employees found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $employees->links() }}


<div class="modal-overlay" id="attendanceModal">
    <div class="modal-box">
        <h5>Add Attendance</h5>

        <div id="modalMessage" style="display:none; padding: 10px 14px; border-radius: 6px; margin-bottom: 14px; font-size: 14px;"></div>

        <form id="attendanceForm">
            @csrf

            <div class="form-group">
                <label>Employee ID</label>
                <input type="text" name="employee_id" id="employee_id" class="form-control" placeholder="e.g. EMP001" required>
            </div>

            <div class="form-group">
                <label>Date</label>
                <input type="date" name="attendance_date" id="attendance_date" required>
            </div>

            <div class="form-group">
                <label>Check In</label>
                <input type="time" name="check_in" id="check_in" required>
            </div>

            <div class="form-group">
                <label>Check Out</label>
                <input type="time" name="check_out" id="check_out">
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel-modal" onclick="closeAttendanceModal()">Cancel</button>
                <button type="submit" class="btn-confirm-delete" id="saveAttendanceBtn">Save</button>
            </div>
        </form>
    </div>
</div>
<script>
    setTimeout(function() {
        let alert = document.getElementById('successAlert');
        if (alert) alert.style.display = 'none';
    }, 3000);

    function toggleMenu(btn) {
        document.querySelectorAll('.action-dropdown.show').forEach(function(d) {
            if (d !== btn.nextElementSibling) d.classList.remove('show');
        });
        btn.nextElementSibling.classList.toggle('show');
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.action-menu')) {
            document.querySelectorAll('.action-dropdown.show').forEach(function(d) {
                d.classList.remove('show');
            });
        }
    });


    function openAttendanceModal() {
        document.getElementById('attendanceModal').classList.add('show');
        document.getElementById('attendance_date').valueAsDate = new Date();
        document.getElementById('modalMessage').style.display = 'none';
        document.getElementById('attendanceForm').reset();
        document.getElementById('attendance_date').valueAsDate = new Date();
    }

    function closeAttendanceModal() {
        document.getElementById('attendanceModal').classList.remove('show');
    }

    document.getElementById('attendanceModal').addEventListener('click', function(e) {
        if (e.target === this) closeAttendanceModal();
    });

    document.getElementById('attendanceForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const btn = document.getElementById('saveAttendanceBtn');
        btn.disabled = true;
        btn.textContent = 'Saving...';

        const formData = new FormData(this);
        const msgBox = document.getElementById('modalMessage');

        fetch("{{ route('attendence.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(res => res.json().then(data => ({
                status: res.status,
                body: data
            })))
            .then(({
                status,
                body
            }) => {
                msgBox.style.display = 'block';

                if (status === 201) {
                    msgBox.style.backgroundColor = '#d4edda';
                    msgBox.style.color = '#155724';
                    msgBox.style.border = '1px solid #c3e6cb';
                    msgBox.textContent = body.message;

                    document.getElementById('attendanceForm').reset();

                    setTimeout(() => {
                        closeAttendanceModal();
                        window.location.reload();
                    }, 1500);

                } else {
                    msgBox.style.backgroundColor = '#f8d7da';
                    msgBox.style.color = '#721c24';
                    msgBox.style.border = '1px solid #f5c6cb';
                    msgBox.textContent = body.message;
                }
            })
            .catch(() => {
                msgBox.style.display = 'block';
                msgBox.style.backgroundColor = '#f8d7da';
                msgBox.style.color = '#721c24';
                msgBox.textContent = 'Something went wrong. Please try again.';
            })
            .finally(() => {
                btn.disabled = false;
                btn.textContent = 'Save';
            });
    });
</script>

@endsection