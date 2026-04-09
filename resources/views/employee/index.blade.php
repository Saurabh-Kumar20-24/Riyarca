@extends('layouts.header')

@section('title', 'Employee')
@section('page-title', 'Employee')

{{-- Common Shared CSS --}}
<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@section('content')

@if(session('success'))
    <div id="successAlert" class="alert-success-custom">{{ session('success') }}</div>
@endif

{{-- Page Header --}}
<div class="page-header">
    <h2>Employee List</h2>

    <div class="header-actions">

        {{-- Search Form --}}
        <form method="GET" action="{{ route('employee.index') }}" class="d-flex gap-2">
            <input type="text"
                   name="search"
                   class="search-input"
                   placeholder="Search employee..."
                   value="{{ request('search') }}">
            <button type="submit" class="btn-search">Search</button>
        </form>

        {{-- Role Filter --}}
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

        @if(Auth::user()->role_id === 1 || Auth::user()->role_id === 9)
            <a href="{{ route('employee.create') }}" class="btn-add">+ Add Employee</a>
        @endif

    </div>
</div>

{{-- Table --}}
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>S.No.</th>
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
                <td>{{ $i + 1 }}</td>
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
                    {{-- 3-Dot Action Menu --}}
                    <div class="action-menu">
                        <button class="action-toggle" onclick="toggleMenu(this)" title="Actions">
                            &#8942;
                        </button>
                        <div class="action-dropdown">
                            <a href="{{ route('employee.edit', $emp->id) }}">
                                ✏️ Edit
                            </a>
                            <button type="button" class="btn-delete-action"
                                    onclick="openDeleteModal({{ $emp->id }}, '{{ addslashes($emp->name) }}')">
                                🗑️ Delete
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
    {{ $employees->links() }}

{{-- Delete Confirmation Modal --}}
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
    // Auto-hide success alert
    setTimeout(function () {
        let alert = document.getElementById('successAlert');
        if (alert) alert.style.display = 'none';
    }, 3000);

    // 3-dot dropdown toggle
    function toggleMenu(btn) {
        // Close all other open dropdowns
        document.querySelectorAll('.action-dropdown.show').forEach(function (d) {
            if (d !== btn.nextElementSibling) d.classList.remove('show');
        });
        btn.nextElementSibling.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.action-menu')) {
            document.querySelectorAll('.action-dropdown.show').forEach(function (d) {
                d.classList.remove('show');
            });
        }
    });

    // Delete modal
    function openDeleteModal(id, name) {
        // Close any open dropdowns
        document.querySelectorAll('.action-dropdown.show').forEach(function (d) {
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

    // Close modal on overlay click
    document.getElementById('deleteModal').addEventListener('click', function (e) {
        if (e.target === this) closeDeleteModal();
    });
</script>

@endsection