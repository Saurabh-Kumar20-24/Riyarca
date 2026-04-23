@extends('layouts.header')

@section('title', 'EOD Reports')
@section('page-title', 'EOD Reports')

@section('content')

<div class="page-header">
    <h2>EOD Reports</h2>
    <div class="header-actions">
        <form method="GET" action="{{ route('eod.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="search-input"
                   placeholder="Search employee..."
                   value="{{ request('search') }}">
            <button type="submit" class="btn-search">Search</button>
        </form>
    </div>
</div>

<div class="table-card">
    <div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>S.No.</th>
                <th>Employee</th>
                <th>Role</th>
                <th>Last EOD Date</th>
                <th>Last EOD Tasks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $i => $emp)
            <tr>
                <td>{{ $employees->firstItem() + $i }}</td>
                <td>{{ $emp->name }}</td>
                <td>{{ $emp->role->role_name ?? '-' }}</td>

                <td>
                    @if($emp->lastEod)
                        {{ $emp->lastEod->report_date->format('d M Y') }}
                    @else
                        <span style="color:var(--muted); font-size:12px;">Not submitted</span>
                    @endif
                </td>

                <td>
                    @if($emp->lastEod)
                        <button class="btn-view"
                            onclick='openEodModal(
                                "{{ addslashes($emp->name) }}",
                                @json($emp->lastEod)
                            )'>
                            👁 View Tasks
                        </button>
                    @else
                        <span style="color:var(--muted);">—</span>
                    @endif
                </td>

                <td>
                    <div class="action-menu">
                        <button class="action-toggle" onclick="toggleMenu(this)" title="Actions">
                            &#8942;
                        </button>
                        <div class="action-dropdown">
                            @if($emp->lastEod)
                                <a href="{{ route('eod.previousEods', ['emp_id' => $emp->id]) }}">
                                    📋 Previous
                                </a>
                            @else
                                <span style="padding:8px 16px; color:var(--muted); font-size:13px; display:block;">
                                    No EODs yet
                                </span>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; color:var(--muted); padding:2rem;">
                    No employees found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>
{{ $employees->links() }}
<div id="eodModal" class="eod-modal-overlay" onclick="handleOverlayClick(event)">
    <div class="eod-modal-box">

        <div class="eod-modal-header">
            <div>
                <h3 id="modalTitle">EOD Reports</h3>
                <p id="modalSubtitle"></p>
            </div>
            <button class="eod-modal-close" onclick="closeModal()">✖</button>
        </div>

        <div id="modalBody" class="eod-modal-body"></div>
    </div>
</div>

@push('scripts')
<script>
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

    function openEodModal(name, eod) {
        document.getElementById('eodModal').classList.add('open');
        document.getElementById('modalTitle').innerText    = name;
        document.getElementById('modalSubtitle').innerText = 'Last EOD Report';

        let tasks = eod.tasks_completed;
        if (typeof tasks === 'string') tasks = JSON.parse(tasks);

        const date      = new Date(eod.report_date);
        const dayName   = date.toLocaleDateString('en-US', { weekday: 'long' });
        const formatted = date.toLocaleDateString('en-GB', {
            day: '2-digit', month: 'short', year: 'numeric'
        });

        const taskRows = tasks.map(task => `
            <div class="eod-task-row">
                <span class="eod-task-name">• ${task}</span>
            </div>
        `).join('');

        document.getElementById('modalBody').innerHTML = `
            <div class="eod-card">
                <div class="eod-card-header">
                    <div>
                        <span class="eod-date">${formatted}</span>
                        <span class="eod-day">${dayName}</span>
                    </div>
                </div>
                ${taskRows}
                <div class="eod-submitted">Submitted at ${formatTime(eod.created_at)}</div>
            </div>
        `;
    }

    function formatTime(datetime) {
        if (!datetime) return '-';
        return new Date(datetime).toLocaleTimeString('en-US', {
            hour: '2-digit', minute: '2-digit', hour12: true
        });
    }

    function closeModal() {
        document.getElementById('eodModal').classList.remove('open');
    }

    function handleOverlayClick(e) {
        if (e.target === document.getElementById('eodModal')) closeModal();
    }
</script>
@endpush

@endsection