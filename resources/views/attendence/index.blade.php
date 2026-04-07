@include('layouts.header')

{{-- Common Shared CSS --}}
<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@if(session('success'))
    <div id="successAlert" class="alert-success-custom">{{ session('success') }}</div>
@endif

{{-- Page Header --}}
<div class="page-header">
    <h2>Employee List</h2>

    <div class="header-actions">

        {{-- Search Form --}}
        <form method="GET" action="{{ route('attendence.index') }}" class="d-flex gap-2">
            <input type="text"
                   name="search"
                   class="search-input"
                   placeholder="Search employee..."
                   value="{{ request('search') }}">
            <button type="submit" class="btn-search">Search</button>
        </form>

        <!-- {{-- Role Filter --}}
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
        </form> -->

        <!-- @if(Auth::user()->role_id === 1 || Auth::user()->role_id === 2)
            <a href="{{ route('employee.create') }}" class="btn-add">+ Add Employee</a>
        @endif -->

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
                <!--<th>Phone</th>
                <th>DOB</th>
                <th>Joining Date</th>
                <th>Role</th> -->
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
                <!-- <td>{{ $emp->phone ?? '-' }}</td>
                <td>{{ $emp->dob ?? '-' }}</td>
                <td>{{ $emp->joining_date ?? '-' }}</td>-->
                <td>{{ $emp->role->role_name ?? '-' }}</td> 
                <!-- <td>{{ $emp->manager->name ?? '-' }}</td> -->
                <td>
                    active/inactive
                </td>
                <td>
                    {{-- 3-Dot Action Menu --}}
                    <div class="action-menu">
                        <button class="action-toggle" onclick="toggleMenu(this)" title="Actions">
                            &#8942;
                        </button>
                        <div class="action-dropdown">
                             <a href="{{ route('attendence.ShowAttendence', ['emp_id' => $emp->id]) }}">
                                Previous
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

 

    // Close modal on overlay click
    document.getElementById('deleteModal').addEventListener('click', function (e) {
        if (e.target === this) closeDeleteModal();
    });
</script>

@include('layouts.footer')