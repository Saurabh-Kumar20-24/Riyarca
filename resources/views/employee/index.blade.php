@extends('layouts.header')

@section('title', 'Employee')
@section('page-title', 'Employee')

<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@section('content')

@if(session('success'))
<div id="successAlert" class="alert-success-custom">{{ session('success') }}</div>
@endif

<div class="page-header">
    <h2>Employee List</h2>

    <div class="header-actions">

        <form method="GET" action="{{ route('employee.index') }}" class="d-flex gap-2">
            <input type="text"
                name="search"
                class="search-input"
                placeholder="Search employee..."
                value="{{ request('search') }}">
            <button type="submit" class="btn-search">Search</button>
        </form>

        <form method="GET" action="{{ route('employee.index') }}">
            <select name="role" class="filter-select" onchange="this.form.submit()">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                @if($role->id != 1)
                <option value="{{ $role->id }}"
                    {{ request('role') == $role->id ? 'selected' : '' }}>
                    {{ $role->role_name }}
                </option>
                @endif
                @endforeach
            </select>
        </form>
        @if(auth()->user()->role_id == 2)
        <button onclick="openOtpModal()" class="btn-confirm-delete">Download Data</button>
        @endif

        @if(Auth::user()->role_id === 1 || Auth::user()->role_id === 9)
        <a href="{{ route('employee.create') }}" class="btn-add">+ Add Employee</a>
        @endif

    </div>
</div>

<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>EmployeeId</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>DOB</th>
                    <th>Joining Date</th>
                    <th>Role</th>
                    <th>Manager</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $i => $emp)
                <tr>
                    <td>{{ $employees->firstItem() + $i }}</td>
                    <td>{{ $emp->employee_id }}</td>
                    <td>{{ $emp->name }}</td>
                    <td>{{ $emp->email }}</td>
                    <td>{{ $emp->phone ?? '-' }}</td>
                    <td>{{ $emp->dob ?? '-' }}</td>
                    <td>{{ $emp->joining_date ?? '-' }}</td>
                    <td>{{ $emp->role->role_name ?? '-' }}</td>
                    <td>{{ $emp->manager->name ?? '-' }}</td>
                    <td>
                        <span class="{{ $emp->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $emp->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-menu">
                            <button class="action-toggle" onclick="toggleMenu(this)" title="Actions">
                                &#8942;
                            </button>
                            <div class="action-dropdown">
                                <a href="{{ route('employee.edit', $emp->id) }}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <button type="button" class="btn-delete-action"
                                    onclick="openDeleteModal({{ $emp->id }}, '{{ addslashes($emp->name) }}')">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
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

<div class="modal-overlay" id="otpModal">
    <div class="modal-box">
        <div class="modal-icon">🔐</div>
        <h5 id="otpModalTitle">Verify Your Identity</h5>
        <p id="otpModalMessage">Click "Send OTP" to receive a verification code on your registered email.</p>

        <div id="otpInputSection" style="display:none; margin: 16px 0;">
            <input type="text"
                id="otpInput"
                maxlength="6"
                placeholder="Enter 6-digit OTP"
                style="width:100%; padding:10px; font-size:18px; letter-spacing:6px;
                          text-align:center; border:2px solid #d1d5db; border-radius:6px;">
            <p id="otpError" style="color:red; font-size:13px; margin-top:6px; display:none;"></p>
        </div>

        <div class="modal-actions" id="otpActions">
            <button class="btn-cancel-modal" onclick="closeOtpModal()">Cancel</button>
            <button class="btn-add" id="otpActionBtn" onclick="sendOtp()">Send OTP</button>
        </div>

        <div id="otpLoadingSpinner" style="display:none; text-align:center; padding:10px;">
            <span>⏳ Please wait...</span>
        </div>
    </div>
</div>

<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon">🗑️</div>
        <h5>Delete Employee?</h5>
        <p id="deleteModalText">Are you sure you want to delete this employee? This action cannot be undone.</p>
        <div class="modal-actions">
            <button class="btn-cancel-modal" onclick="closeDeleteModal()">Cancel</button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-confirm-delete">Yes, Delete</button>
            </form>
        </div>
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

    function openDeleteModal(id, name) {
        document.querySelectorAll('.action-dropdown.show').forEach(function(d) {
            d.classList.remove('show');
        });

        document.getElementById('deleteModalText').textContent =
            'Are you sure you want to delete "' + name + '"? This action cannot be undone.';

        document.getElementById('deleteForm').action = '/employee/' + id;

        document.getElementById('deleteModal').classList.add('show');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('show');
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
</script>

<script>
    let otpSent = false;

    function openOtpModal() {
        otpSent = false;
        document.getElementById('otpModalTitle').textContent = 'Verify Your Identity';
        document.getElementById('otpModalMessage').textContent = 'Click "Send OTP" to receive a verification code on your registered email.';
        document.getElementById('otpInputSection').style.display = 'none';
        document.getElementById('otpInput').value = '';
        document.getElementById('otpError').style.display = 'none';
        document.getElementById('otpActionBtn').textContent = 'Send OTP';
        document.getElementById('otpActionBtn').onclick = sendOtp;
        document.getElementById('otpModal').classList.add('show');
    }

    function closeOtpModal() {
        document.getElementById('otpModal').classList.remove('show');
    }

    function setLoading(state) {
        document.getElementById('otpLoadingSpinner').style.display = state ? 'block' : 'none';
        document.getElementById('otpActions').style.display = state ? 'none' : 'flex';
    }

    function sendOtp() {
        setLoading(true);

        fetch('{{ route("employee.download.sendOtp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                setLoading(false);
                if (data.success) {
                    otpSent = true;
                    document.getElementById('otpModalMessage').textContent = 'OTP sent to your registered email. Valid for 5 minutes.';
                    document.getElementById('otpInputSection').style.display = 'block';
                    document.getElementById('otpActionBtn').textContent = 'Verify & Download';
                    document.getElementById('otpActionBtn').onclick = verifyOtp;
                } else {
                    document.getElementById('otpModalMessage').textContent = data.message || 'Failed to send OTP.';
                }
            })
            .catch(() => {
                setLoading(false);
                document.getElementById('otpModalMessage').textContent = 'Network error. Please try again.';
            });
    }

    function verifyOtp() {
        const otp = document.getElementById('otpInput').value.trim();
        const errEl = document.getElementById('otpError');

        if (otp.length !== 6 || isNaN(otp)) {
            errEl.textContent = 'Please enter a valid 6-digit OTP.';
            errEl.style.display = 'block';
            return;
        }

        errEl.style.display = 'none';
        setLoading(true);

        fetch('{{ route("employee.download.verify") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    otp
                })
            })
            .then(res => res.json())
            .then(data => {
                setLoading(false);
                if (data.success) {
                    closeOtpModal();
                    window.location.href = '{{ route("employee.download.file") }}';
                } else {
                    errEl.textContent = data.message || 'Invalid OTP.';
                    errEl.style.display = 'block';
                }
            })
            .catch(() => {
                setLoading(false);
                errEl.textContent = 'Network error. Please try again.';
                errEl.style.display = 'block';
            });
    }

    document.getElementById('otpModal').addEventListener('click', function(e) {
        if (e.target === this) closeOtpModal();
    });
</script>

@endsection