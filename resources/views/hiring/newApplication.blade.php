@extends('layouts.header')

@section('title', 'New Applications')
@section('page-title', 'New Applications')

@section('content')

<div class="page-header">
    <h2>New Applications</h2>
    <div class="header-actions">
        <form method="GET" action="{{ route('hiring.newApplication') }}" class="d-flex gap-2">
            <input type="text" name="search" class="search-input"
                   placeholder="Search candidate..."
                   value="{{ request('search') }}">

            <!-- <input type="date" name="from" class="search-input" value="{{ request('from') }}">
            <input type="date" name="to" class="search-input" value="{{ request('to') }}"> -->

            <button type="submit" class="btn-search">Filter</button>
            <a href="{{ route('hiring.newApplication') }}" class="btn-search">Reset</a>
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert-success-custom">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Position</th>
                <th>Experience</th>
                <th>Applied Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobSeekers as $i => $job)
            <tr>
                <td>{{ $jobSeekers->firstItem() + $i }}</td>
                <td>{{ $job->name }}</td>
                <td>{{ $job->email }}</td>
                <td>{{ $job->phone }}</td>
                <td>{{ $job->position->title ?? '-' }}</td>
                <td>{{ $job->position->experiences ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($job->applied_date)->format('d M Y') }}</td>

                <td>
                    <span class="status-badge status-pending">
                        Pending
                    </span>
                </td>

                <td>
                    <div class="action-menu">
                        <button class="action-toggle" onclick="toggleMenu({{ $job->id }})">
                            ⋯
                        </button>

                        <div class="action-dropdown" id="menu-{{ $job->id }}">

                            {{-- Accept --}}
                            <form method="POST" action="{{ route('hiring.updateStatus', $job->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="leave-action-approve">
                                    <i class="bi bi-check-circle"></i> Accept
                                </button>
                            </form>

                            {{-- Reject --}}
                            <form method="POST" action="{{ route('hiring.updateStatus', $job->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="leave-action-reject">
                                    <i class="bi bi-x-circle"></i> Reject
                                </button>
                            </form>

                        </div>
                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="9" class="table-empty">
                    No pending applications found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $jobSeekers->links() }}
</div>

<style>
    .action-menu{
    position: relative;
}

.action-dropdown{
    position: absolute;
    right: 0;
    top: 100%;
    background: #ffffff;
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 8px;
    padding: 6px 0;
    min-width: 150px;
    display: none;
    z-index: 9999;
}

.action-dropdown.show{
    display: block;
}

/* buttons */
.action-dropdown form{
    margin: 0;
}

.leave-action-approve,
.leave-action-reject{
    width: 100%;
    background: transparent;
    border: none;
    padding: 8px 12px;
    text-align: left;
    cursor: pointer;
    color: #cbd5e1;
}

.leave-action-approve:hover{
    background: rgba(16,185,129,.2);
    color: #10b981;
}

.leave-action-reject:hover{
    background: rgba(239,68,68,.2);
    color: #ef4444;
}
</style>

@endsection

@push('scripts')
<script>
        function toggleMenu(id) {
            document.querySelectorAll('.action-dropdown').forEach(menu => {
                if (menu.id !== 'menu-' + id) {
                    menu.classList.remove('show');
                }
            });

            document.getElementById('menu-' + id).classList.toggle('show');
        }

        document.addEventListener('click', function(e){
            if(!e.target.closest('.action-menu')){
                document.querySelectorAll('.action-dropdown').forEach(menu=>{
                    menu.classList.remove('show');
                });
            }
        });
</script>
@endpush