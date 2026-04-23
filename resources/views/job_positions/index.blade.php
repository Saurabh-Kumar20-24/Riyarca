@extends('layouts.header')

@section('title', 'Job Posts')
@section('page-title', 'Job Posts')

<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@section('content')

@if(session('success'))
<div id="successAlert" class="alert-success-custom">{{ session('success') }}</div>
@endif

<div class="page-header">
    <h2>Job Post List</h2>

    <div class="header-actions">

        <form method="GET" action="{{ route('job_positions.index') }}" class="d-flex gap-2">
            <input type="text"
                name="search"
                class="search-input"
                placeholder="Search job..."
                value="{{ request('search') }}">
            <button type="submit" class="btn-search">Search</button>
        </form>

        <form method="GET" action="{{ route('job_positions.index') }}">
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </form>

        <button class="btn-add" onclick="openJobModal()">+ Add Job</button>
    </div>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Title</th>
                <th>Department</th>
                <th>Vacancies</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobs as $i => $job)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $job->title }}</td>
                <td>{{ $job->department }}</td>
                <td>{{ $job->vacancies }}</td>
                <td>
                    <span class="{{ $job->status == 'open' ? 'badge-active' : 'badge-inactive' }}">
                        {{ ucfirst($job->status) }}
                    </span>
                </td>
                <td>
                    <div class="action-menu">
                        <button class="action-toggle" onclick="toggleMenu(this)" title="Actions">
                            &#8942;
                        </button>
                        <div class="action-dropdown">
                            <a href="">
                                ✏️ Edit
                            </a>

                            <button type="button" class="btn-delete-action"
                                onclick="openDeleteModal({{ $job->id }}, '{{ addslashes($job->title) }}')">
                                🗑️ Delete
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; color:#aaa; padding: 2rem;">
                    No job posts found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $jobs->links() }}

<div class="modal-overlay" id="jobModal">
    <div class="modal-box">
        <h5>Add Job Post</h5>

        <form action="{{ route('job_positions.store') }}" method="POST">
            @csrf

            <input type="text" name="title" placeholder="Job Title" required><br><br>

            <input type="text" name="department" placeholder="Department" required><br><br>

            <input type="number" name="vacancies" placeholder="Vacancies" required><br><br>

            <select name="status" required>
                <option value="">Select Status</option>
                <option value="open">Open</option>
                <option value="closed">Closed</option>
            </select><br><br>

            <div class="modal-actions">
                <button type="button" class="btn-cancel-modal" onclick="closeJobModal()">Cancel</button>
                <button type="submit" class="btn-confirm-delete">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon">🗑️</div>
        <h5>Delete Job Post?</h5>
        <p id="deleteModalText">Are you sure you want to delete this job?</p>
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

    function openDeleteModal(id, title) {
        document.querySelectorAll('.action-dropdown.show').forEach(function(d) {
            d.classList.remove('show');
        });

        document.getElementById('deleteModalText').textContent =
            'Are you sure you want to delete "' + title + '"? This action cannot be undone.';

        document.getElementById('deleteForm').action = '/job-positions/' + id;

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
    function openJobModal() {
        document.getElementById('jobModal').classList.add('show');
    }

    function closeJobModal() {
        document.getElementById('jobModal').classList.remove('show');
    }

    document.getElementById('jobModal').addEventListener('click', function(e) {
        if (e.target === this) closeJobModal();
    });
</script>

@endsection