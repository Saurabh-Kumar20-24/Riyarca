@include('layouts.header')
<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

{{-- Page Header --}}
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

{{-- Table --}}
<div class="table-card">
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
                <td>{{ $i + 1 }}</td>
                <td>{{ $emp->name }}</td>
                <td>{{ $emp->role->role_name ?? '-' }}</td>

                {{-- Last EOD Date --}}
                <td>
                    @if($emp->lastEod)
                        {{ $emp->lastEod->report_date->format('d M Y') }}
                    @else
                        <span style="color:#94a3b8; font-size:12px;">
                            Not submitted
                        </span>
                    @endif
                </td>

                {{-- View Button --}}
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
                        <span style="color:#94a3b8;">—</span>
                    @endif
                </td>

                {{-- Actions --}}
                <td>
                    <div class="action-menu">
                        <button class="action-toggle"
                                onclick="toggleMenu(this)" title="Actions">
                            &#8942;
                        </button>
                        <div class="action-dropdown">
                            @if($emp->lastEod)
                                <a href="{{route('eod.previousEods',['emp_id'=>$emp->id])}}">
                                    📋 Previous
                                </a>
                            @else
                                <span style="padding:8px 16px; color:#94a3b8;
                                    font-size:13px; display:block;">
                                    No EODs yet
                                </span>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;
                    color:#aaa; padding:2rem;">
                    No employees found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- EOD Modal --}}
<div id="eodModal" class="modal-overlay" onclick="handleOverlayClick(event)">
    <div class="modal-box">

        {{-- Modal Header --}}
        <div class="modal-header">
            <div>
                <h3 id="modalTitle" style="color:#fff; font-size:18px;
                    font-weight:700; margin:0;">EOD Reports</h3>
                <p id="modalSubtitle" style="color:#94a3b8;
                    font-size:13px; margin:4px 0 0;">
                </p>
            </div>
            <button onclick="closeModal()" style="
                background:rgba(255,255,255,.08);
                border:none; color:#fff;
                width:32px; height:32px;
                border-radius:8px; cursor:pointer;
                font-size:16px; display:flex;
                align-items:center; justify-content:center;">
                ✖
            </button>
        </div>

        {{-- Modal Body --}}
        <div id="modalBody" class="modal-body"></div>
    </div>
</div>

<style>
/* ── Modal ───────────────────────────────────────── */
.modal-overlay {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.65);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 20px;
}
.modal-box {
    background: #1e293b;
    width: 100%;
    max-width: 540px;
    max-height: 80vh;
    overflow-y: auto;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid #334155;
    box-shadow: 0 25px 60px rgba(0,0,0,.5);
    scrollbar-width: thin;
    scrollbar-color: #334155 transparent;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #334155;
}
.modal-body {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* ── EOD Card inside modal ───────────────────────── */
.eod-card {
    background: #0f172a;
    border-radius: 12px;
    padding: 16px;
    border: 1px solid #334155;
}
.eod-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.eod-date {
    font-size: 15px;
    font-weight: 700;
    color: #fff;
}
.eod-day {
    font-size: 12px;
    color: #94a3b8;
    margin-left: 8px;
}
.eod-hours-badge {
    background: #064e3b;
    color: #10b981;
    padding: 3px 12px;
    border-radius: 99px;
    font-size: 12px;
    font-weight: 600;
}
.eod-task-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #1e293b;
    font-size: 13px;
}
.eod-task-row:last-child { border-bottom: none; }
.eod-task-name { color: #e2e8f0; }
.eod-task-hours { color: #60a5fa; font-weight: 600; }
.eod-submitted {
    margin-top: 10px;
    font-size: 11px;
    color: #475569;
}

/* ── View Button ─────────────────────────────────── */
.btn-view {
    background: rgba(59,130,246,.15);
    color: #60a5fa;
    border: 1px solid rgba(59,130,246,.3);
    padding: 5px 14px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    transition: background .2s;
}
.btn-view:hover {
    background: rgba(59,130,246,.3);
}
</style>

<script>
    // ── 3-dot dropdown ────────────────────────────────────────
    function toggleMenu(btn) {
        document.querySelectorAll('.action-dropdown.show').forEach(function (d) {
            if (d !== btn.nextElementSibling) d.classList.remove('show');
        });
        btn.nextElementSibling.classList.toggle('show');
    }
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.action-menu')) {
            document.querySelectorAll('.action-dropdown.show').forEach(function (d) {
                d.classList.remove('show');
            });
        }
    });

    // ── Modal ─────────────────────────────────────────────────
   function openEodModal(name, eod) {
    document.getElementById('eodModal').style.display  = 'flex';
    document.getElementById('modalTitle').innerText    = name;
    document.getElementById('modalSubtitle').innerText = 'Last EOD Report';

    let tasks = eod.tasks_completed;
    if (typeof tasks === 'string') {
        tasks = JSON.parse(tasks);
    }

    const date      = new Date(eod.report_date);
    const dayName   = date.toLocaleDateString('en-US', { weekday: 'long' });
    const formatted = date.toLocaleDateString('en-GB', {
        day: '2-digit', month: 'short', year: 'numeric'
    });

    // Task rows — tasks is array of strings now
    const taskRows = tasks.map(task => `
        <div class="eod-task-row">
            <span class="eod-task-name">• ${task}</span>
        </div>
    `).join('');

    const html = `
        <div class="eod-card">
            <div class="eod-card-header">
                <div>
                    <span class="eod-date">${formatted}</span>
                    <span class="eod-day">${dayName}</span>
                </div>
            </div>
            ${taskRows}
            <div class="eod-submitted">
                Submitted at ${formatTime(eod.created_at)}
            </div>
        </div>
    `;

    document.getElementById('modalBody').innerHTML = html;
}

    function formatTime(datetime) {
        if (!datetime) return '-';
        const d = new Date(datetime);
        return d.toLocaleTimeString('en-US', {
            hour: '2-digit', minute: '2-digit', hour12: true
        });
    }

    function closeModal() {
        document.getElementById('eodModal').style.display = 'none';
    }

    // Close on overlay click
    function handleOverlayClick(e) {
        if (e.target === document.getElementById('eodModal')) {
            closeModal();
        }
    }
</script>

@include('layouts.footer')