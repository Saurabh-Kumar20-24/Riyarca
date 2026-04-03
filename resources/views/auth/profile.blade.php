
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand:       #5b21b6;
            --brand-light: #ede9fe;
            --brand-mid:   #7c3aed;
            --surface:     #ffffff;
            --border:      #e8e8ed;
            --muted:       #9ca3af;
            --text:        #111827;
            --text-2:      #6b7280;
            --green-bg:    #d1fae5;
            --green-fg:    #065f46;
            --red-bg:      #fee2e2;
            --red-fg:      #991b1b;
            --radius:      14px;
            --shadow:      0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: #f5f4f8;
            color: var(--text);
        }

        /* ── Page ── */
        .page-wrap {
            max-width: 660px;
            margin: 2.5rem auto;
            padding: 0 1.25rem 3rem;
        }
        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.4rem;
            display: flex;
            align-items: center;
            gap: .45rem;
        }
        .page-title i { color: var(--brand); }

        /* ── Avatar hero ── */
        .avatar-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 1.6rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.3rem;
            margin-bottom: 1rem;
        }
        .avatar-wrap {
            position: relative;
            flex-shrink: 0;
        }
        /* camera icon overlay — only clickable in edit mode */
        .avatar-wrap .cam-btn {
            position: absolute;
            bottom: 0; right: 0;
            width: 26px; height: 26px;
            border-radius: 50%;
            background: var(--brand);
            color: #fff;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: .7rem;
            cursor: pointer;
            border: 2px solid #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,.18);
        }
        body.edit-mode .avatar-wrap .cam-btn { display: flex; }
        body.edit-mode .avatar-wrap { cursor: pointer; }

        .avatar {
            width: 76px; height: 76px;
            border-radius: 50%;
            background: var(--brand);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.85rem; font-weight: 700; color: #fff;
            overflow: hidden;
            border: 3px solid var(--brand-light);
        }
        .avatar img { width: 100%; height: 100%; object-fit: cover; }
        #avatarInput { display: none; }

        .hero-info h4 { font-size: 1.05rem; font-weight: 700; margin-bottom: .12rem; }
        .hero-info p  { font-size: .8rem; color: var(--text-2); margin-bottom: .45rem; }
        .badge-pill {
            font-size: .67rem; font-weight: 600;
            padding: .16rem .6rem; border-radius: 20px; display: inline-block;
        }
        .pill-role     { background: var(--brand-light); color: var(--brand); }
        .pill-active   { background: var(--green-bg); color: var(--green-fg); }
        .pill-inactive { background: var(--red-bg);   color: var(--red-fg); }

        /* ── Stats ── */
        .stats-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .8rem;
            margin-bottom: 1rem;
        }
        .stat-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: .8rem 1rem;
            display: flex; align-items: center; gap: .75rem;
        }
        .stat-icon {
            width: 34px; height: 34px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem; flex-shrink: 0;
        }
        .ic-green { background: var(--green-bg); color: #059669; }
        .ic-blue  { background: #dbeafe;         color: #1d4ed8; }
        .stat-label { font-size: .62rem; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
        .stat-val   { font-size: .86rem; font-weight: 700; color: var(--text); font-family: 'DM Mono', monospace; }

        /* ── Main card ── */
        .main-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .card-head {
            padding: .95rem 1.4rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-head-title {
            font-size: .66rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .08em;
            color: var(--muted);
            display: flex; align-items: center; gap: .35rem;
        }
        .card-head-title i { color: var(--brand); font-size: .8rem; }

        /* Edit / Cancel toggle button */
        .btn-edit-toggle {
            font-size: .73rem; font-weight: 600;
            padding: .28rem .8rem;
            border-radius: 7px;
            border: 1.5px solid var(--brand);
            background: transparent;
            color: var(--brand);
            cursor: pointer;
            display: flex; align-items: center; gap: .3rem;
            transition: background .15s, color .15s;
        }
        .btn-edit-toggle:hover { background: var(--brand); color: #fff; }

        /* ── Field row ── */
        .field-row {
            display: flex; align-items: center;
            padding: .78rem 1.4rem;
            border-bottom: 1px solid #f3f4f6;
            gap: .85rem;
        }
        .field-row:last-of-type { border-bottom: none; }
        .field-icon {
            width: 28px; height: 28px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 7px; background: #f5f4f8;
            font-size: .76rem; color: var(--brand); flex-shrink: 0;
        }
        .field-body { flex: 1; }
        .field-label {
            font-size: .6rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .06em;
            color: var(--muted); margin-bottom: .2rem;
        }

        /* ── Input styling ── */
        .field-input {
            width: 100%;
            border: 1px solid transparent;
            border-radius: 7px;
            padding: .38rem .6rem;
            font-size: .85rem;
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            color: var(--text);
            background: transparent;
            outline: none;
            transition: border-color .2s, background .2s, box-shadow .2s;
            /* disabled by default */
            pointer-events: none;
        }

        /* When edit mode is ON and input has data-editable */
        body.edit-mode .field-input[data-editable] {
            pointer-events: auto;
            border-color: var(--border);
            background: #fafafa;
            cursor: text;
        }
        body.edit-mode .field-input[data-editable]:focus {
            border-color: var(--brand-mid);
            background: var(--surface);
            box-shadow: 0 0 0 3px rgba(124,58,237,.1);
        }

        .field-val { font-size: .85rem; font-weight: 600; color: var(--text); }

        /* ── Save button — hidden until edit mode ── */
        .save-bar {
            display: none;
            padding: .85rem 1.4rem 1rem;
            gap: .55rem;
        }
        body.edit-mode .save-bar { display: flex; }

        .btn-save {
            flex: 1;
            padding: .55rem 1rem;
            background: var(--brand);
            color: #fff; border: none;
            border-radius: 8px;
            font-size: .83rem; font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: .4rem;
            transition: background .15s, box-shadow .15s;
        }
        .btn-save:hover { background: var(--brand-mid); box-shadow: 0 4px 12px rgba(91,33,182,.25); }

        .btn-cancel {
            padding: .55rem 1rem;
            background: transparent;
            color: var(--text-2);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: .83rem; font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: border-color .15s, color .15s;
        }
        .btn-cancel:hover { border-color: #bbb; color: var(--text); }

        /* ── Toast ── */
        .toast-wrap { position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999; }
        .toast-msg {
            background: #111; color: #fff;
            padding: .55rem .95rem; border-radius: 9px;
            font-size: .78rem; font-weight: 500;
            display: flex; align-items: center; gap: .4rem;
            opacity: 0; transform: translateY(10px);
            transition: all .25s ease; pointer-events: none;
        }
        .toast-msg.show { opacity: 1; transform: translateY(0); }
        .toast-msg i { color: #4ade80; }

        @media (max-width: 520px) {
            .stats-row { grid-template-columns: 1fr; }
            .avatar-card { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>

<div class="page-wrap">

    <div class="page-title">
        <i class="bi bi-person-circle"></i> Profile
    </div>

    {{-- ── Avatar Hero ── --}}
    <div class="avatar-card">
        <div class="avatar-wrap" onclick="triggerAvatarPick()">
            <div class="avatar" id="avatarDisplay">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar">
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>
            <span class="cam-btn"><i class="bi bi-camera-fill"></i></span>
        </div>
        <input type="file" id="avatarInput" accept="image/*" onchange="previewAvatar(this)">

        <div class="hero-info">
            <h4>{{ $user->name }}</h4>
            <p>{{ $user->email }}</p>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                {{-- role_name from roles table --}}
                <span class="badge-pill pill-role">
                    {{ $user->role->role_name ?? 'Unknown Role' }}
                </span>
                @if($user->is_active)
                    <span class="badge-pill pill-active">Active</span>
                @else
                    <span class="badge-pill pill-inactive">Inactive</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Stats ── --}}
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-icon ic-green"><i class="bi bi-calendar-check"></i></div>
            <div>
                <div class="stat-label">Member Since</div>
                <div class="stat-val">{{ $user->created_at->format('d M Y') }}</div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon ic-blue"><i class="bi bi-arrow-repeat"></i></div>
            <div>
                <div class="stat-label">Last Updated</div>
                <div class="stat-val">{{ $user->updated_at->format('d M Y') }}</div>
            </div>
        </div>
    </div>

    {{-- ── Profile Form ── --}}
    <div class="main-card">
        <div class="card-head">
            <div class="card-head-title">
                <i class="bi bi-person-lines-fill"></i> Profile Information
            </div>
            <button type="button" class="btn-edit-toggle" id="editToggleBtn" onclick="enableEdit()">
                <i class="bi bi-pencil" id="toggleIcon"></i>
                <span id="toggleLabel">Update</span>
            </button>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
            @csrf
            @method('PUT')

            {{-- Hidden file input tied to avatar picker above --}}
            <input type="file" name="profile_photo" id="hiddenPhoto" style="display:none">

            {{-- Full Name — read only always --}}
            <div class="field-row">
                <div class="field-icon"><i class="bi bi-person"></i></div>
                <div class="field-body">
                    <div class="field-label">Full Name</div>
                    <input class="field-input" type="text"
                           value="{{ $user->name }}" readonly>
                </div>
            </div>

            {{-- Email — editable --}}
            <div class="field-row">
                <div class="field-icon"><i class="bi bi-envelope"></i></div>
                <div class="field-body">
                    <div class="field-label">Email Address</div>
                    <input class="field-input" type="email" name="email"
                           value="{{ old('email', $user->email) }}"
                           placeholder="Enter email"
                           data-editable required>
                </div>
            </div>

            {{-- Phone — editable --}}
            <div class="field-row">
                <div class="field-icon"><i class="bi bi-telephone"></i></div>
                <div class="field-body">
                    <div class="field-label">Phone Number</div>
                    <input class="field-input" type="text" name="phone"
                           value="{{ old('phone', $user->phone) }}"
                           placeholder="Enter phone number"
                           data-editable>
                </div>
            </div>

            {{-- Role — always locked, from roles.role_name --}}
            <div class="field-row">
                <div class="field-icon"><i class="bi bi-person-badge"></i></div>
                <div class="field-body">
                    <div class="field-label">Role</div>
                    <input class="field-input" type="text"
                           value="{{ $user->role->role_name ?? 'Unknown Role' }}"
                           readonly>
                </div>
            </div>

            {{-- Assigned Manager — hidden for role_id 1 and 2 --}}
            @if(!in_array($user->role_id, [1, 2]))
            <div class="field-row">
                <div class="field-icon"><i class="bi bi-person-check"></i></div>
                <div class="field-body">
                    <div class="field-label">Assigned Manager</div>
                    <input class="field-input" type="text"
                           value="{{ $user->assigned_manager ?? '—' }}"
                           readonly>
                </div>
            </div>
            @endif

            {{-- Status — display only --}}
            <div class="field-row">
                <div class="field-icon"><i class="bi bi-toggle-on"></i></div>
                <div class="field-body">
                    <div class="field-label">Status</div>
                    <div class="field-val" style="padding:.38rem 0">
                        @if($user->is_active)
                            <span class="badge-pill pill-active">Active</span>
                        @else
                            <span class="badge-pill pill-inactive">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Save / Cancel bar (shown only in edit mode) --}}
            <div class="save-bar">
                <button type="submit" class="btn-save">
                    <i class="bi bi-check2-circle"></i> Save Changes
                </button>
                <button type="button" class="btn-cancel" onclick="cancelEdit()">
                    Cancel
                </button>
            </div>

        </form>
    </div>

</div>

{{-- Toast notification --}}
<div class="toast-wrap">
    <div class="toast-msg" id="toast">
        <i class="bi bi-check-circle-fill"></i> Profile updated successfully
    </div>
</div>

<script>
    // ── Edit mode toggle ──
    function enableEdit() {
        document.body.classList.add('edit-mode');
        // swap button to Cancel
        document.getElementById('toggleIcon').className = 'bi bi-x-lg';
        document.getElementById('toggleLabel').textContent = 'Cancel';
        document.getElementById('editToggleBtn').onclick = cancelEdit;
        // focus email
        document.querySelector('[data-editable]').focus();
    }

    function cancelEdit() {
        document.body.classList.remove('edit-mode');
        document.getElementById('toggleIcon').className = 'bi bi-pencil';
        document.getElementById('toggleLabel').textContent = 'Update';
        document.getElementById('editToggleBtn').onclick = enableEdit;
    }

    // ── Avatar picker — only fires in edit mode ──
    function triggerAvatarPick() {
        if (!document.body.classList.contains('edit-mode')) return;
        document.getElementById('avatarInput').click();
    }

    function previewAvatar(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        // pass file to form input
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('hiddenPhoto').files = dt.files;
        // live preview
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('avatarDisplay').innerHTML =
                `<img src="${e.target.result}" alt="Avatar"
                      style="width:100%;height:100%;object-fit:cover;border-radius:50%">`;
        };
        reader.readAsDataURL(file);
    }

    // ── Flash success toast on redirect ──
    @if(session('success'))
        const toast = document.getElementById('toast');
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3200);
    @endif
</script>

</body>
</html>