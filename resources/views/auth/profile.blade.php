@include('layouts.header')
{{-- Common Shared CSS --}}
<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@if(session('success'))
    <div id="successAlert" class="alert-success-custom">{{ session('success') }}</div>
@endif



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


@include('layouts.footer')