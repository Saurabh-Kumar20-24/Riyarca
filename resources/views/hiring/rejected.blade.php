@extends('layouts.header')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@section('content')
<div class="page-header">
    <h2>Rejected Candidates</h2>
    <div class="header-actions">
        <form method="GET" action="{{ route('hiring.rejected') }}" class="d-flex gap-2">
            <input type="text" name="search" class="search-input"
                placeholder="Search name..."
                value="{{ request('search') }}">
            <a href="{{ route('hiring.rejected') }}"
                class="btn-search btn-search-reset">Reset</a>
        </form>
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
                    <th>Phone</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Applied Date</th>
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobSeekers as $i => $seeker)
                <tr>
                    <td>{{ $jobSeekers->firstItem() + $i }}</td>
                    <td>{{ $seeker->name }}</td>
                    <td>{{ $seeker->email }}</td>
                    <td>{{ $seeker->phone ?? '-' }}</td>
                    <td>{{ $seeker->position->title ?? '-' }}</td>
                    <td>{{ $seeker->position->department ?? '-' }}</td>
                    <td>{{ $seeker->applied_date->format('d M Y') }}</td>
                    <td>{{ $seeker->notes ?? '-' }}</td>
                    <td>
                        @if($seeker->resume_path)
                        <a href="{{ asset('storage/' . $seeker->resume_path) }}"
                            target="_blank" class="resume-btn">
                            <i class="bi bi-file-earmark-text"></i> View
                        </a>
                        @else
                        <span>—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;
                    color:#94a3b8; padding:2rem;">
                        No rejected candidates found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:20px;">{{ $jobSeekers->links() }}</div>

<style>
    .hiring-summary {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
    }

    .hiring-stat-card {
        background: var(--primary);
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 200px;
    }

    .hiring-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .hiring-stat-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .35);
        margin-bottom: 4px;
    }

    .hiring-stat-value {
        font-size: 24px;
        font-weight: 800;
        line-height: 1;
    }
</style>
@endsection