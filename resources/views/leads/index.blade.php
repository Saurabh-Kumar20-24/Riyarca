@extends('layouts.header')

@section('title', 'Leads')
@section('page-title', 'Leads')

@section('content')

@if(session('success'))
<div id="successAlert" class="alert-success-custom">{{ session('success') }}</div>
@endif

<div class="page-header">
    <h2>Lead List</h2>
    <div class="header-actions">

        <form method="GET" action="{{ route('leads.index') }}">
            <input type="text" name="search" class="search-input"
                placeholder="Search lead..." value="{{ request('search') }}">
            <button class="btn-search">Search</button>
        </form>

        <form method="GET" action="{{ route('leads.index') }}">
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="new" {{ request('status')=='new'       ?'selected':'' }}>New</option>
                <option value="contacted" {{ request('status')=='contacted' ?'selected':'' }}>Contacted</option>
                <option value="qualified" {{ request('status')=='qualified' ?'selected':'' }}>Qualified</option>
                <option value="proposal" {{ request('status')=='proposal'  ?'selected':'' }}>Proposal</option>
                <option value="closed" {{ request('status')=='closed'    ?'selected':'' }}>Closed</option>
            </select>
        </form>

        <a href="{{ route('leads.create') }}" class="btn-add">+ Add Lead</a>
    </div>
</div>

<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Recent BDA</th>
                    <th>Created</th>
                    @if(Auth::user()->role_id === 11)
                    <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $i => $lead)
                <tr>
                    <td>{{ $leads->firstItem() + $i }}</td>
                    <td><strong>{{ $lead->name }}</strong></td>
                    <td>{{ $lead->company ?? '—' }}</td>
                    <td>{{ $lead->phone }}</td>

                    <td>
                        <span class="status-badge status-{{ $lead->status ?? 'new' }}">
                            {{ ucfirst($lead->status ?? 'New') }}
                        </span>
                    </td>

                    <td>
                        @if($lead->latestActivity && $lead->latestActivity->user)
                        <div class="bda-chip">
                            <!-- <div class="bda-avatar">
                                {{ strtoupper(substr($lead->latestActivity->user->name, 0, 1)) }}
                            </div> -->
                            <div>
                                <div class="bda-name">{{ $lead->latestActivity->user->name }}</div>
                                <div class="bda-sub">{{ $lead->latestActivity->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @else
                        <span class="bda-sub">—</span>
                        @endif
                    </td>

                    <td>{{ $lead->created_at->format('d M Y') }}</td>

                    @if(Auth::user()->role_id === 11)
                    <td>
                        <div class="action-menu">
                            <button class="action-toggle" onclick="toggleMenu(this)" title="Actions">&#8942;</button>
                            <div class="action-dropdown">
                                <button type="button"
                                    onclick="openModal({{ $lead->id }}, '{{ addslashes($lead->name) }}')">
                                    <i class="bi bi-journal-plus"></i> Add Activity
                                </button>
                            </div>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="table-empty">No leads found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination-wrap">
    {{ $leads->links() }}
</div>

<div class="modal-overlay" id="activityModal">
    <div class="modal-box activity-modal-box">

        <div class="activity-modal-header">
            <h3>8.pl<span id="modalLeadName">Lead</span></h3>
            <button class="activity-modal-close" onclick="closeModal()">✕</button>
        </div>

        <div class="modal-tabs">
            <div class="modal-tab active" onclick="switchTab('timeline')"> Activity History</div>
            <div class="modal-tab" onclick="switchTab('add')"> Add Activity</div>
        </div>

        <div class="tab-panel active" id="tab-timeline">
            <div class="timeline-wrap" id="timelineWrap">
                <div class="timeline-loader">Loading activities…</div>
            </div>
        </div>

        <div class="tab-panel" id="tab-add">
            <div class="activity-form-panel">

                <div class="activity-form-grid">

                    <div class="form-group">
                        <label class="form-label">Contacted Via</label>
                        <select id="f_contact_type" class="form-control-custom">
                            <option value="call">📞 Call</option>
                            <option value="onsite">🏢 Onsite</option>
                            <option value="whatsapp">💬 WhatsApp</option>
                            <option value="email">✉️ Email</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Duration (minutes)</label>
                        <input type="number" id="f_duration" class="form-control-custom"
                            placeholder="e.g. 15" min="0">
                    </div>

                    <div class="form-group fg-full">
                        <label class="form-label">Discussion</label>
                        <textarea id="f_discussion" class="form-control-custom"
                            placeholder="What was discussed…"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Next Follow-up Date</label>
                        <input type="date" id="f_followup_date" class="form-control-custom">
                    </div>

                </div>

                <div class="activity-form-footer">
                    <button class="btn-save-activity" id="saveBtn" onclick="saveActivity()">
                        Save Activity
                    </button>
                    <button class="btn-cancel-modal" onclick="closeModal()">Cancel</button>
                </div>

            </div>
        </div>

    </div>
</div>

<div class="leads-toast" id="toast">✅ Activity saved successfully!</div>

<script>
    const CSRF = '{{ csrf_token() }}';
    const STORE_URL = '{{ route("lead.activity.store") }}';
    const FETCH_URL = '/lead-activities/';

    const typeIcons = {
        call: '📞',
        onsite: '🏢',
        whatsapp: '💬',
        email: '✉️'
    };
    let currentLeadId = null;

    function switchTab(tab) {
        document.querySelectorAll('.modal-tab').forEach((t, i) => {
            t.classList.toggle('active', (i === 0 && tab === 'timeline') || (i === 1 && tab === 'add'));
        });
        document.getElementById('tab-timeline').classList.toggle('active', tab === 'timeline');
        document.getElementById('tab-add').classList.toggle('active', tab === 'add');
    }

    function openModal(id, name) {
        currentLeadId = id;
        document.getElementById('modalLeadName').textContent = name;
        document.getElementById('activityModal').classList.add('show');
        switchTab('timeline');
        loadActivities(id);
    }

    function closeModal() {
        document.getElementById('activityModal').classList.remove('show');
        resetForm();
    }

    function loadActivities(id) {
        document.getElementById('timelineWrap').innerHTML =
            '<div class="timeline-loader">Loading activities…</div>';

        fetch(FETCH_URL + id)
            .then(r => r.json())
            .then(data => renderTimeline(data))
            .catch(() => {
                document.getElementById('timelineWrap').innerHTML =
                    '<div class="timeline-error">Failed to load. Please try again.</div>';
            });
    }

    function renderTimeline(activities) {
        const wrap = document.getElementById('timelineWrap');

        if (!activities.length) {
            wrap.innerHTML = `<div class="timeline-empty">
            No activities logged yet.<br>
            Switch to <strong>Add Activity</strong> to log the first one.
        </div>`;
            return;
        }

        wrap.innerHTML = activities.map(a => {
            const initials = (a.user || '?')[0].toUpperCase();
            const icon = typeIcons[a.contact_type] || '📋';
            const typeLabel = a.contact_type.charAt(0).toUpperCase() + a.contact_type.slice(1);

            return `
        <div class="timeline-item">
            <div class="timeline-dot">${initials}</div>
            <div class="timeline-body">
                <div class="timeline-head">
                    <span class="timeline-user">${a.user}</span>
                    <span class="timeline-time">${a.created_at}</span>
                </div>
                <div class="timeline-tags">
                    <span class="timeline-tag timeline-tag-type">${icon} ${typeLabel}</span>
                    ${a.duration      ? `<span class="timeline-tag timeline-tag-duration">⏱ ${a.duration} min</span>` : ''}
                    ${a.followup_date ? `<span class="timeline-tag timeline-tag-followup">📅 ${a.followup_date}</span>` : ''}
                </div>
                ${a.discussion ? `<div class="timeline-discussion">${a.discussion}</div>` : ''}
            </div>
        </div>`;
        }).join('');
    }

    function saveActivity() {
        const btn = document.getElementById('saveBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="btn-spinner"></span>Saving…';

        fetch(STORE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF
                },
                body: JSON.stringify({
                    lead_id: currentLeadId,
                    contact_type: document.getElementById('f_contact_type').value,
                    duration: document.getElementById('f_duration').value,
                    discussion: document.getElementById('f_discussion').value,
                    followup_date: document.getElementById('f_followup_date').value,
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    resetForm();
                    renderTimeline(data.activities);
                    switchTab('timeline');
                    showToast();
                }
            })
            .catch(() => alert('Something went wrong. Please try again.'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = 'Save Activity';
            });
    }

    function resetForm() {
        document.getElementById('f_contact_type').value = 'call';
        document.getElementById('f_duration').value = '';
        document.getElementById('f_discussion').value = '';
        document.getElementById('f_followup_date').value = '';
    }

    function showToast() {
        const t = document.getElementById('toast');
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2800);
    }

    function toggleMenu(btn) {
        document.querySelectorAll('.action-dropdown.show').forEach(d => {
            if (d !== btn.nextElementSibling) d.classList.remove('show');
        });
        btn.nextElementSibling.classList.toggle('show');
    }
    document.addEventListener('click', e => {
        if (!e.target.closest('.action-menu'))
            document.querySelectorAll('.action-dropdown').forEach(d => d.classList.remove('show'));
    });
    document.getElementById('activityModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    setTimeout(() => {
        const el = document.getElementById('successAlert');
        if (el) el.style.display = 'none';
    }, 3000);
</script>

@endsection