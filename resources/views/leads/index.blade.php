@extends('layouts.header')

@section('title', 'Leads')
@section('page-title', 'Leads')

<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

<style>
.status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.status-new       { background:#e8f0ff; color:#1E5ED9; }
.status-contacted { background:#f0ebff; color:#6A2FE0; }
.status-qualified { background:#fde8f8; color:#D92BBF; }
.status-proposal  { background:#fff1ec; color:#FF6A3D; }
.status-closed    { background:#e8faf0; color:#28a745; }

.bda-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
}
.bda-avatar {
    width: 24px; height: 24px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1E5ED9, #6A2FE0);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.bda-name { color: #333; font-weight: 500; }
.bda-sub  { color: #aaa; font-size: 11px; }

.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    padding: 16px;
}
.modal-overlay.show { display: flex; }

.modal-box {
    background: #fff;
    border-radius: 16px;
    width: 100%;
    max-width: 660px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 24px 80px rgba(0,0,0,0.22);
    overflow: hidden;
    animation: modalIn 0.2s ease;
}
@keyframes modalIn {
    from { opacity:0; transform:translateY(16px) scale(0.98); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}

.modal-header {
    padding: 18px 24px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}
.modal-header h3 { margin:0; font-size:16px; font-weight:700; color:#1a1a2e; }
.modal-header h3 span { color:#6A2FE0; }
.modal-close {
    background:none; border:none; font-size:24px;
    color:#bbb; cursor:pointer; line-height:1; padding:0;
    transition: color 0.2s;
}
.modal-close:hover { color:#333; }

.modal-tabs {
    display: flex;
    border-bottom: 1px solid #f0f0f0;
    flex-shrink: 0;
    background: #fafafa;
}
.modal-tab {
    flex: 1;
    padding: 12px;
    text-align: center;
    font-size: 13px;
    font-weight: 600;
    color: #999;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
}
.modal-tab.active {
    color: #6A2FE0;
    border-bottom-color: #6A2FE0;
    background: #fff;
}

.tab-panel { display: none; flex: 1; overflow-y: auto; }
.tab-panel.active { display: flex; flex-direction: column; }

.timeline-wrap { padding: 20px 24px; flex: 1; }
.timeline-empty {
    text-align: center; color: #bbb;
    padding: 40px 0; font-size: 13px;
}
.timeline-loader {
    text-align:center; padding:30px 0; color:#aaa; font-size:13px;
}

.timeline-item {
    display: flex;
    gap: 14px;
    margin-bottom: 20px;
    position: relative;
}
.timeline-item:not(:last-child)::before {
    content:'';
    position: absolute;
    left: 17px; top: 36px;
    width: 2px;
    bottom: -20px;
    background: #f0f0f0;
}
.t-dot {
    flex-shrink: 0;
    width: 36px; height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1E5ED9, #6A2FE0);
    color: #fff;
    font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 2px 8px rgba(106,47,224,0.3);
    position: relative; z-index: 1;
}
.t-body {
    flex: 1;
    background: #fafafa;
    border: 1px solid #efefef;
    border-radius: 10px;
    padding: 12px 14px;
}
.t-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}
.t-user { font-weight: 700; font-size: 13px; color: #1a1a2e; }
.t-time { font-size: 11px; color: #bbb; }
.t-tags { display:flex; gap:6px; flex-wrap:wrap; margin-bottom:8px; }
.t-tag {
    font-size: 11px; padding: 2px 10px;
    border-radius: 20px; font-weight: 600;
}
.t-tag-type     { background:#f0ebff; color:#6A2FE0; }
.t-tag-duration { background:#e8f0ff; color:#1E5ED9; }
.t-tag-followup { background:#fff1ec; color:#FF6A3D; }
.t-discussion   { font-size: 13px; color: #555; line-height: 1.55; }

.modal-form-panel { padding: 20px 24px; }

.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 14px;
}
.fg label {
    display: block;
    font-size: 11px; font-weight: 700;
    color: #888; text-transform: uppercase;
    letter-spacing: 0.4px; margin-bottom: 6px;
}
.fg input, .fg select, .fg textarea {
    width: 100%; box-sizing: border-box;
    border: 1.5px solid #e8e8e8;
    border-radius: 8px;
    padding: 9px 12px;
    font-size: 13px; color: #333;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    background: #fff;
}
.fg input:focus, .fg select:focus, .fg textarea:focus {
    border-color: #6A2FE0;
    box-shadow: 0 0 0 3px rgba(106,47,224,0.1);
}
.fg textarea { resize: vertical; min-height: 80px; }
.fg-full { grid-column: 1 / -1; }

.btn-save {
    background: linear-gradient(135deg, #1E5ED9 0%, #6A2FE0 50%, #D92BBF 100%);
    background-size: 200% 200%;
    animation: gradShift 4s ease infinite;
    color: #fff; border: none;
    border-radius: 9px;
    padding: 10px 24px;
    font-size: 13px; font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(106,47,224,0.35);
    transition: opacity 0.2s, transform 0.2s;
}
.btn-save:hover { opacity:0.9; transform: translateY(-1px); }
.btn-save:disabled { opacity:0.6; cursor:not-allowed; transform:none; }

@keyframes gradShift {
    0%  { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100%{ background-position: 0% 50%; }
}

.btn-cancel-modal {
    background: #f4f4f4; color: #666;
    border: 1.5px solid #e8e8e8;
    border-radius: 9px;
    padding: 10px 18px;
    font-size: 13px; font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}
.btn-cancel-modal:hover { background: #eaeaea; }

.modal-form-actions {
    display: flex; gap: 10px;
    margin-top: 18px;
    padding-top: 16px;
    border-top: 1px solid #f0f0f0;
}

.spinner {
    display: inline-block;
    width: 13px; height: 13px;
    border: 2px solid rgba(255,255,255,0.35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.65s linear infinite;
    vertical-align: middle; margin-right: 5px;
}
@keyframes spin { to { transform: rotate(360deg); } }

.toast {
    position: fixed; bottom: 28px; right: 28px;
    background: linear-gradient(135deg, #1E5ED9, #6A2FE0);
    color: #fff; padding: 13px 22px;
    border-radius: 10px; font-size: 13px; font-weight: 600;
    box-shadow: 0 6px 24px rgba(106,47,224,0.4);
    z-index: 9999;
    opacity: 0; transform: translateY(12px);
    transition: all 0.3s; pointer-events:none;
}
.toast.show { opacity:1; transform: translateY(0); }
</style>

@section('content')

@if(session('success'))
    <div id="successAlert" class="alert-success-custom">{{ session('success') }}</div>
@endif

<div class="page-header">
    <h2>Lead List</h2>
    <div class="header-actions">

        <form method="GET" action="{{ route('leads.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="search-input"
                   placeholder="Search lead..." value="{{ request('search') }}">
            <button class="btn-search">Search</button>
        </form>

        <form method="GET" action="{{ route('leads.index') }}">
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="new"       {{ request('status')=='new'       ?'selected':'' }}>New</option>
                <option value="contacted" {{ request('status')=='contacted' ?'selected':'' }}>Contacted</option>
                <option value="qualified" {{ request('status')=='qualified' ?'selected':'' }}>Qualified</option>
                <option value="proposal"  {{ request('status')=='proposal'  ?'selected':'' }}>Proposal</option>
                <option value="closed"    {{ request('status')=='closed'    ?'selected':'' }}>Closed</option>
            </select>
        </form>

        <a href="{{ route('leads.create') }}" class="btn-add">+ Add Lead</a>
    </div>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Company</th>
                <th>Phone</th>
                <th>Status</th>
                <!-- <th>Assigned To</th> -->
                <th>Recent BDA</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leads as $i => $lead)
            <tr>
                <td>{{ $leads->firstItem() + $i }}</td>
                <td><strong>{{ $lead->name }}</strong></td>
                <td>{{ $lead->company ?? '—' }}</td>
                <td>{{ $lead->phone }}</td>

                {{-- Status badge --}}
                <td>
                    <span class="status-badge status-{{ $lead->status ?? 'new' }}">
                        {{ ucfirst($lead->status ?? 'New') }}
                    </span>
                </td>

                {{-- Formally assigned BDA --}}
                <!-- <td>
                    @if($lead->assignedUser)
                        <div class="bda-chip">
                            <div class="bda-avatar">{{ strtoupper(substr($lead->assignedUser->name,0,1)) }}</div>
                            <span class="bda-name">{{ $lead->assignedUser->name }}</span>
                        </div>
                    @else
                        <span style="color:#ccc">—</span>
                    @endif
                </td> -->

                <td>
                    @if($lead->latestActivity && $lead->latestActivity->user)
                        <div class="bda-chip">
                            <div class="bda-avatar" style="background:linear-gradient(135deg,#D92BBF,#FF6A3D)">
                                {{ strtoupper(substr($lead->latestActivity->user->name,0,1)) }}
                            </div>
                            <div>
                                <div class="bda-name">{{ $lead->latestActivity->user->name }}</div>
                                <div class="bda-sub">{{ $lead->latestActivity->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @else
                        <span style="color:#ccc">—</span>
                    @endif
                </td>

                <td>{{ $lead->created_at->format('d M Y') }}</td>

                <td>
                   <div class="action-menu">
                        <button class="action-toggle" onclick="toggleMenu(this)" title="Actions">&#8942;</button>
                        <div class="action-dropdown">

                            @if(Auth::user()->role_id === 11)
                                <button type="button"
                                    onclick="openModal({{ $lead->id }}, '{{ addslashes($lead->name) }}')">
                                    📞 Add Activity
                                </button>
                            @endif

                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center;color:#aaa;padding:2rem;">No leads found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $leads->links() }}

<div class="modal-overlay" id="activityModal">
    <div class="modal-box">

        <div class="modal-header">
            <h3>📞 <span id="modalLeadName">Lead</span></h3>
            <button class="modal-close" onclick="closeModal()">×</button>
        </div>

        <div class="modal-tabs">
            <div class="modal-tab active" onclick="switchTab('timeline')">🕐 Activity History</div>
            <div class="modal-tab"        onclick="switchTab('add')">➕ Add Activity</div>
        </div>

        <div class="tab-panel active" id="tab-timeline">
            <div class="timeline-wrap" id="timelineWrap">
                <div class="timeline-loader">Loading activities…</div>
            </div>
        </div>

        <div class="tab-panel" id="tab-add">
            <div class="modal-form-panel">
                <div class="form-grid-2">
                    <div class="fg">
                        <label>Contacted Via</label>
                        <select id="f_contact_type">
                            <option value="call">📞 Call</option>
                            <option value="onsite">🏢 Onsite</option>
                            <option value="whatsapp">💬 WhatsApp</option>
                            <option value="email">✉️ Email</option>
                        </select>
                    </div>
                    <div class="fg">
                        <label>Duration (minutes)</label>
                        <input type="number" id="f_duration" placeholder="e.g. 15" min="0">
                    </div>
                    <div class="fg fg-full">
                        <label>Discussion</label>
                        <textarea id="f_discussion" placeholder="What was discussed…"></textarea>
                    </div>
                    <div class="fg">
                        <label>Next Follow-up Date</label>
                        <input type="date" id="f_followup_date">
                    </div>
                </div>

                <div class="modal-form-actions">
                    <button class="btn-save" id="saveBtn" onclick="saveActivity()">Save Activity</button>
                    <button class="btn-cancel-modal" onclick="closeModal()">Cancel</button>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="toast" id="toast">✅ Activity saved successfully!</div>

<script>
const CSRF         = '{{ csrf_token() }}';
const STORE_URL    = '{{ route("lead.activity.store") }}';
const FETCH_URL    = '/lead-activities/';

const typeIcons    = { call:'📞', onsite:'🏢', whatsapp:'💬', email:'✉️' };
let   currentLeadId = null;

function switchTab(tab) {
    document.querySelectorAll('.modal-tab').forEach((t, i) => {
        t.classList.toggle('active', (i === 0 && tab==='timeline') || (i === 1 && tab==='add'));
    });
    document.getElementById('tab-timeline').classList.toggle('active', tab === 'timeline');
    document.getElementById('tab-add').classList.toggle('active',      tab === 'add');
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
                '<div class="timeline-empty" style="color:#e74c3c">Failed to load. Please try again.</div>';
        });
}

function renderTimeline(activities) {
    const wrap = document.getElementById('timelineWrap');

    if (!activities.length) {
        wrap.innerHTML = '<div class="timeline-empty">No activities logged yet.<br>Switch to <strong>Add Activity</strong> to log the first one.</div>';
        return;
    }

    wrap.innerHTML = activities.map(a => {
        const initials = (a.user || '?')[0].toUpperCase();
        const icon     = typeIcons[a.contact_type] || '📋';
        const typeLabel= a.contact_type.charAt(0).toUpperCase() + a.contact_type.slice(1);

        return `
        <div class="timeline-item">
            <div class="t-dot">${initials}</div>
            <div class="t-body">
                <div class="t-head">
                    <span class="t-user">${a.user}</span>
                    <span class="t-time">${a.created_at}</span>
                </div>
                <div class="t-tags">
                    <span class="t-tag t-tag-type">${icon} ${typeLabel}</span>
                    ${a.duration     ? `<span class="t-tag t-tag-duration">⏱ ${a.duration} min</span>` : ''}
                    ${a.followup_date? `<span class="t-tag t-tag-followup">📅 Follow-up: ${a.followup_date}</span>` : ''}
                </div>
                ${a.discussion ? `<div class="t-discussion">${a.discussion}</div>` : ''}
            </div>
        </div>`;
    }).join('');
}

function saveActivity() {
    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span>Saving…';

    fetch(STORE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF
        },
        body: JSON.stringify({
            lead_id:       currentLeadId,
            contact_type:  document.getElementById('f_contact_type').value,
            duration:      document.getElementById('f_duration').value,
            discussion:    document.getElementById('f_discussion').value,
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
    document.getElementById('f_contact_type').value  = 'call';
    document.getElementById('f_duration').value      = '';
    document.getElementById('f_discussion').value    = '';
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