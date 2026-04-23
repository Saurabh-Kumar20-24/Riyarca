@extends('layouts.header')

@section('title', 'Policies')
@section('page-title', 'Policies')

<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@section('content')

@if(session('success'))
<div id="successAlert" class="alert-success-custom">{{ session('success') }}</div>
@endif

<div class="page-header">
    <h2>Policies List</h2>
    <div class="header-actions">
        <form method="GET" action="{{ route('policies.index') }}" class="d-flex gap-2">
            <select name="role_id" class="filter-select" onchange="this.form.submit()">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                    {{ $role->role_name }}
                </option>
                @endforeach
            </select>
        </form>
        @if(Auth::user()->role_id === 1 || Auth::user()->role_id === 9)
        <a href="{{ route('policies.create') }}" class="btn-add">+ Add Policy</a>
        @endif
    </div>
</div>

<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Title</th>
                    <th>Role</th>
                    <th>Version</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($policies as $i => $policy)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $policy->title }}</td>
                    <td>{{ $policy->role->role_name ?? '-' }}</td>
                    <td>v{{ $policy->version }}</td>
                    <td>
                        <span class="{{ $policy->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $policy->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $policy->createdBy->name ?? '-' }}</td>
                    <td>{{ $policy->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="action-menu">
                            <button class="action-toggle" onclick="toggleMenu(this)" title="Actions">
                                &#8942;
                            </button>
                            <div class="action-dropdown">
                                <a href="{{ route('policies.edit', $policy->id) }}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('policies.toggle', $policy->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-delete-action">
                                        <i class="bi bi-toggle-on"></i>
                                        {{ $policy->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <!-- <button type="button" class="btn-delete-action"
                                    onclick="openViewModal(`{{ addslashes($policy->title) }}`, `{{ addslashes(strip_tags($policy->content)) }}`)">
                                    <i class="bi bi-eye"></i> View
                                </button> -->


                                <button type="button" class="btn-delete-action"
                                    onclick="openViewModal('{{ addslashes($policy->title) }}', '{{ addslashes($policy->content) }}')">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; color:#aaa; padding: 2rem;">
                        No policies found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal-overlay" id="viewModal">
    <div class="modal-box" style="max-width:600px;">
        <div class="modal-icon">📄</div>
        <h5 id="viewModalTitle"></h5>
        <div id="viewModalContent"
            style="text-align:left; max-height:300px; overflow-y:auto;
                    background:#f9f9f9; padding:16px; border-radius:8px;
                    font-size:14px; line-height:1.7; margin: 12px 0;">
        </div>
        <div class="modal-actions">
            <button class="btn-cancel-modal" onclick="closeViewModal()">Close</button>
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

    function openViewModal(title, content) {
        document.getElementById('viewModalTitle').textContent = title;

        try {
            const points = JSON.parse(content);
            if (Array.isArray(points)) {
                let html = '<ol style="padding-left:20px; margin:0;">';
                points.forEach(p => {
                    html += `<li style="margin-bottom:10px; line-height:1.7;">${p}</li>`;
                });
                html += '</ol>';
                document.getElementById('viewModalContent').innerHTML = html;
            } else {
                document.getElementById('viewModalContent').textContent = content;
            }
        } catch (e) {
            document.getElementById('viewModalContent').textContent = content;
        }

        document.getElementById('viewModal').classList.add('show');
    }

    function closeViewModal() {
        document.getElementById('viewModal').classList.remove('show');
    }

    document.getElementById('viewModal').addEventListener('click', function(e) {
        if (e.target === this) closeViewModal();
    });
</script>

@endsection