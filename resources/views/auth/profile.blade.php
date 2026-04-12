@extends('layouts.header')
@section('title', 'My Profile')
@section('page-title', 'My Profile')
@push('styles')
    <style>
        .prof-page {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 2rem 1rem;
            min-height: 80vh;
        }
        .id-card {
            width: 340px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,.13), 0 6px 20px rgba(106,47,224,.16);
            overflow: hidden;
            position: relative;
            transition: transform .3s ease, box-shadow .3s ease;
        }
        .id-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 28px 70px rgba(0,0,0,.17), 0 10px 30px rgba(106,47,224,.2);
        } 
        .card-stripe {
            background: var(--brand-gradient, linear-gradient(135deg,#6a2fe0,#9b5de5));
            padding: 28px 24px 68px;
            position: relative;
            text-align: center;
            overflow: hidden;
        }
        .card-stripe::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 0; right: 0;
            height: 60px;
            background: #fff;
            border-radius: 50% 50% 0 0 / 100% 100% 0 0;
        }
        .card-stripe::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 120px; height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,.08);
        }
        .stripe-dot {
            position: absolute;
            bottom: 30px; left: -20px;
            width: 90px; height: 90px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
        }
        .card-org {
            position: relative;
            z-index: 2;
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: rgba(255,255,255,.7);
            margin-bottom: 16px;
        }
        .card-avatar-wrap {
            position: relative;
            z-index: 2;
            display: inline-block;
            margin-bottom: 12px;
        }
        .card-avatar-circle {
            width: 92px; height: 92px;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,.9);
            box-shadow: 0 6px 20px rgba(0,0,0,.22);
            overflow: hidden;
            background: rgba(255,255,255,.2);
            display: flex; align-items: center; justify-content: center;
        }
        .card-avatar-circle img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
        }
        .card-avatar-init {
            font-size: 2.2rem;
            font-weight: 800;
            color: #fff;
        }
        .card-role-badge {
            position: absolute;
            bottom: -8px; left: 50%;
            transform: translateX(-50%);
            background: var(--brand-gradient, linear-gradient(135deg,#6a2fe0,#9b5de5));
            color: #fff;
            font-size: .55rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: .22rem .8rem;
            border-radius: 20px;
            border: 2px solid #fff;
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(106,47,224,.3);
        }
        .card-body {
            padding: 0 24px 20px;
            text-align: center;
        }
        .card-name {
            font-size: 1.2rem;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 2px;
            margin-top: 32px;
        }
        .card-empid {
            font-size: .68rem;
            font-weight: 600;
            color: #7c3aed;
            letter-spacing: .08em;
            margin-bottom: 18px;
        }
        .card-info {
            border-top: 1px solid #f0f0f8;
            padding-top: 14px;
            text-align: left;
        }
        .ci-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 6px 0;
            border-bottom: 1px solid #f5f5fc;
        }
        .ci-row:last-child { border-bottom: none; }

        .ci-icon {
            width: 30px; height: 30px;
            border-radius: 8px;
            background: linear-gradient(135deg, rgba(106,47,224,.1), rgba(155,93,229,.07));
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            color: #7c3aed;
            font-size: .75rem;
            margin-top: 1px;
        }
        .ci-text { flex: 1; }
        .ci-lbl {
            font-size: .5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #a0aec0;
            margin-bottom: 2px;
        }
        .ci-val {
            font-size: .8rem;
            font-weight: 600;
            color: #2d3748;
            word-break: break-word;
            line-height: 1.35;
        }
        .spl-on  { color:#065f46; background:#d1fae5; border:1px solid #a7f3d0; border-radius:20px; padding:.12rem .65rem; font-size:.62rem; font-weight:700; }
        .spl-off { color:#991b1b; background:#fee2e2; border:1px solid #fecaca; border-radius:20px; padding:.12rem .65rem; font-size:.62rem; font-weight:700; }

        .card-barcode {
            margin: 4px 24px 16px;
            border-top: 1px dashed #e9ecef;
            padding-top: 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }
        .barcode-lines {
            display: flex;
            gap: 2px;
            height: 30px;
            align-items: flex-end;
        }
        .barcode-lines span {
            display: block;
            background: #d1d5db;
            border-radius: 1px;
        }
        .barcode-num {
            font-size: .52rem;
            font-weight: 600;
            letter-spacing: .24em;
            color: #cbd5e1;
        }
        .card-actions {
            display: flex;
            gap: .55rem;
            padding: 0 24px 22px;
        }
        .ca-btn {
            flex: 1;
            display: flex; align-items: center; justify-content: center;
            gap: .35rem;
            padding: .6rem .5rem;
            border-radius: 12px;
            font-size: .72rem;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all .18s;
            text-decoration: none;
        }
        .ca-btn-primary {
            background: var(--brand-gradient, linear-gradient(135deg,#6a2fe0,#9b5de5));
            color: #fff;
            box-shadow: 0 4px 14px rgba(106,47,224,.35);
        }
        .ca-btn-primary:hover { box-shadow: 0 6px 20px rgba(106,47,224,.5); transform: translateY(-1px); color: #fff; }
        .ca-btn-outline {
            background: #f8f5ff;
            color: #7c3aed;
            border: 1.5px solid #d8b4fe;
        }
        .ca-btn-outline:hover { background: #ede9fe; transform: translateY(-1px); }
        .ca-btn-outline.disabled { opacity: .35; pointer-events: none; }

        .modal-bg {
            position: fixed; inset: 0;
            background: rgba(0,0,0,.55);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: flex; align-items: center; justify-content: center;
            padding: 1rem;
            opacity: 0; pointer-events: none;
            transition: opacity .22s;
        }
        .modal-bg.open { opacity: 1; pointer-events: auto; }

        .modal-box {
            background: #fff;
            border-radius: 20px;
            width: 100%; max-width: 440px;
            max-height: 92vh;
            overflow-y: auto;
            box-shadow: 0 24px 80px rgba(0,0,0,.25);
            transform: scale(.96) translateY(12px);
            transition: transform .22s;
        }
        .modal-bg.open .modal-box { transform: scale(1) translateY(0); }

        .modal-head {
            padding: 1.25rem 1.5rem;
            background: var(--brand-gradient, linear-gradient(135deg,#6a2fe0,#9b5de5));
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 2;
        }
        .modal-head h3 { color: #fff; font-size: .95rem; font-weight: 700; margin: 0; }
        .modal-close {
            width: 30px; height: 30px; border-radius: 50%;
            background: rgba(255,255,255,.18); color: #fff;
            border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem; transition: background .18s;
        }
        .modal-close:hover { background: rgba(255,255,255,.3); }

        .modal-body { padding: 1.4rem 1.5rem 0; }
        .modal-avatar-wrap {
            display: flex; justify-content: center;
            margin-bottom: 1.2rem;
        }
        .modal-avatar-btn { position: relative; cursor: pointer; display: inline-block; }
        .modal-av-img {
            width: 76px; height: 76px; border-radius: 50%; object-fit: cover;
            border: 3px solid #d8b4fe;
            box-shadow: 0 4px 14px rgba(106,47,224,.2);
            display: block;
        }
        .modal-av-init {
            width: 76px; height: 76px; border-radius: 50%;
            background: var(--brand-gradient, linear-gradient(135deg,#6a2fe0,#9b5de5));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; font-weight: 800; color: #fff;
            border: 3px solid #d8b4fe;
        }
        .modal-cam {
            position: absolute; bottom: 0; right: 0;
            width: 24px; height: 24px; border-radius: 50%;
            background: #7c3aed; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: .6rem; border: 2px solid #fff;
        }
        .mf-group  { margin-bottom: .9rem; }
        .mf-row    { display: flex; gap: .75rem; }
        .mf-row .mf-group { flex: 1; }
        .mf-label  {
            display: block; font-size: .58rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .1em;
            color: #6b7280; margin-bottom: .3rem;
        }
        .mf-input  {
            width: 100%; padding: .58rem .8rem;
            border: 1.5px solid #e5e7eb; border-radius: 9px;
            font-size: .87rem; font-weight: 500; color: #1f2937;
            outline: none; transition: border-color .18s, box-shadow .18s;
            background: #fafafa;
            font-family: inherit;
        }
        .mf-input:focus { border-color: #7c3aed; background: #fff; box-shadow: 0 0 0 3px rgba(106,47,224,.1); }
        .mf-input[readonly] { background: #f3f4f6; color: #9ca3af; cursor: not-allowed; }
        .mf-note  { font-size: .58rem; color: #9ca3af; margin-top: .22rem; }
        .mf-error { font-size: .62rem; color: #e53e3e; margin-top: .25rem; }

        .modal-footer {
            padding: 1rem 1.5rem 1.4rem;
            display: flex; gap: .65rem;
            position: sticky; bottom: 0;
            background: #fff;
            border-top: 1px solid #f0f0f0;
            z-index: 1;
        }
        .mf-cancel {
            flex: 1; padding: .62rem; border-radius: 10px;
            border: 1.5px solid #e5e7eb; background: #fff; color: #6b7280;
            font-size: .82rem; font-weight: 600; cursor: pointer;
            transition: all .18s; font-family: inherit;
        }
        .mf-cancel:hover { background: #f9fafb; }
        .mf-save {
            flex: 2; padding: .62rem; border-radius: 10px;
            background: var(--brand-gradient, linear-gradient(135deg,#6a2fe0,#9b5de5));
            color: #fff; border: none;
            font-size: .82rem; font-weight: 700; cursor: pointer;
            box-shadow: 0 4px 14px rgba(106,47,224,.3);
            transition: all .18s; font-family: inherit;
            display: flex; align-items: center; justify-content: center; gap: .4rem;
        }
        .mf-save:hover { box-shadow: 0 6px 20px rgba(106,47,224,.45); transform: translateY(-1px); }
        .toast-wrap { position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999; }
        .toast-msg {
            background: var(--dark-text, #1a1a2e); color: #fff;
            padding: .65rem 1.15rem; border-radius: 11px;
            font-size: .78rem; font-weight: 500;
            display: flex; align-items: center; gap: .45rem;
            opacity: 0; transform: translateY(10px);
            transition: all .24s ease; pointer-events: none;
            border: 1px solid rgba(106,47,224,.2);
        }
        .toast-msg.show { opacity: 1; transform: translateY(0); }
        .toast-msg i { color: #6ee7b7; }

        @media(max-width: 400px) { .id-card { width: 100%; } }
    </style>
@endpush
@section('content')
<div class="prof-page">
    <div class="id-card" id="idCard">
        <div class="card-stripe">
            <div class="stripe-dot"></div>
            <div class="card-org">{{ config('app.name', 'Employee Identity Card') }}</div>
            <div class="card-avatar-wrap">
                <div class="card-avatar-circle">
                    @if($user->profile_image)
                        <img id="cardAvatarImg"
                             src="{{ asset('storage/profile_image/' . $user->profile_image) }}"
                             alt="{{ $user->name }}"
                             crossorigin="anonymous">
                    @else
                        <div class="card-avatar-init" id="cardAvatarInit">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <span class="card-role-badge">{{ $user->role->role_name ?? 'Staff' }}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="card-name">{{ $user->name }}</div>
            <div class="card-empid">
                {{ $user->employee_id ? 'EMP ID: ' . $user->employee_id : 'EMP ID: N/A' }}
            </div>
            <div class="card-info">
                <div class="ci-row">
                    <div class="ci-icon"><i class="bi bi-calendar-heart-fill"></i></div>
                    <div class="ci-text">
                        <div class="ci-lbl">Date of Birth</div>
                        <div class="ci-val">
                            {{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d M Y') : '—' }}
                        </div>
                    </div>
                </div>
                <div class="ci-row">
                    <div class="ci-icon"><i class="bi bi-telephone-fill"></i></div>
                    <div class="ci-text">
                        <div class="ci-lbl">Phone</div>
                        <div class="ci-val">{{ $user->phone ?? '—' }}</div>
                    </div>
                </div>
                @if($user->role_id != 1)
                <div class="ci-row">
                    <div class="ci-icon"><i class="bi bi-briefcase-fill"></i></div>
                    <div class="ci-text">
                        <div class="ci-lbl">Joining Date</div>
                        <div class="ci-val">
                            {{ $user->joining_date ? \Carbon\Carbon::parse($user->joining_date)->format('d M Y') : '—' }}
                        </div>
                    </div>
                </div>
                @endif
                @if(!in_array($user->role_id, [1, 2]))
                <div class="ci-row">
                    <div class="ci-icon"><i class="bi bi-person-badge-fill"></i></div>
                    <div class="ci-text">
                        <div class="ci-lbl">Manager</div>
                        <div class="ci-val">{{ $user->managerName ?? '—' }}</div>
                    </div>
                </div>
                @endif
                <div class="ci-row">
                    <div class="ci-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <div class="ci-text">
                        <div class="ci-lbl">Address</div>
                        <div class="ci-val">{{ $user->address ?? '—' }}</div>
                    </div>
                </div>
                <div class="ci-row">
                    <div class="ci-icon"><i class="bi bi-shield-fill-check"></i></div>
                    <div class="ci-text">
                        <div class="ci-lbl">Status</div>
                        <div class="ci-val">
                            @if($user->is_active)
                                <span class="spl-on">● Active</span>
                            @else
                                <span class="spl-off">● Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="card-barcode">
            <div class="barcode-lines" id="barcodeLines"></div>
            <div class="barcode-num"  id="barcodeNum"></div>
        </div> --}}
        <div class="card-actions" id="cardActions">
            <button type="button" class="ca-btn ca-btn-primary" onclick="openModal()">
                <i class="bi bi-pencil-square"></i> Update Profile
            </button>
            <button type="button"
                    id="dlBtn"
                    class="ca-btn ca-btn-outline {{ $user->profile_image ? '' : 'disabled' }}"
                    onclick="downloadCard()">
                <i class="bi bi-download"></i> Download
            </button>
        </div>
    </div>
</div>
<div class="modal-bg" id="updateModal" onclick="bgClose(event)">
    <div class="modal-box">
        <div class="modal-head">
            <h3><i class="bi bi-pencil-square me-2"></i> Update Profile</h3>
            <button type="button" class="modal-close" onclick="closeModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form method="POST"
              action="{{ route('profile.update') }}"
              enctype="multipart/form-data"
              id="updateForm">
            @csrf
            @method('PUT')
            <div class="modal-body">    
                <div class="modal-avatar-wrap">
                    <label class="modal-avatar-btn" for="profileImageInput" title="Click to change photo">
                        @if($user->profile_image)
                            <img class="modal-av-img" id="modalAvImg"
                                 src="{{ asset('storage/profile_image/' . $user->profile_image) }}"
                                 alt="avatar">
                        @else
                            <div class="modal-av-init" id="modalAvInit">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="modal-cam"><i class="bi bi-camera-fill"></i></span>
                    </label>
                    <input type="file" id="profileImageInput" name="profile_image"
                           accept="image/*" style="display:none"
                           onchange="previewAvatar(this)">
                </div>
                @error('profile_image')
                    <p class="mf-error" style="text-align:center;margin-top:-.5rem;margin-bottom:.8rem;">{{ $message }}</p>
                @enderror   
                <div class="mf-group">
                    <label class="mf-label">Full Name</label>
                    <input class="mf-input" type="text" value="{{ $user->name }}" readonly>
                    <div class="mf-note">Cannot be changed.</div>
                </div>
                <div class="mf-row">
                    <div class="mf-group">
                        <label class="mf-label">Email</label>
                        <input class="mf-input" type="email" value="{{ $user->email }}" readonly>
                        <div class="mf-note">Cannot be changed.</div>
                    </div>
                    <div class="mf-group">
                        <label class="mf-label">Employee ID</label>
                        <input class="mf-input" type="text" value="{{ $user->employee_id ?? '—' }}" readonly>
                        <div class="mf-note">Cannot be changed.</div>
                    </div>
                </div>               
                <div class="mf-row">
                    <div class="mf-group">
                        <label class="mf-label">Date of Birth</label>
                        <input class="mf-input" type="date" name="dob"
                               value="{{ old('dob', $user->dob) }}">
                        @error('dob')<p class="mf-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="mf-group">
                        <label class="mf-label">Phone Number</label>
                        <input class="mf-input" type="tel" name="phone"
                               value="{{ old('phone', $user->phone) }}"
                               placeholder="+91 00000 00000">
                        @error('phone')<p class="mf-error">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="mf-group">
                    <label class="mf-label">Address</label>
                    <textarea class="mf-input" name="address" rows="2"
                              style="resize:vertical"
                              placeholder="Enter your address">{{ old('address', $user->address) }}</textarea>
                    @error('address')<p class="mf-error">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="mf-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="mf-save">
                    <i class="bi bi-check2-circle"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
<div class="toast-wrap">
    <div class="toast-msg" id="toast">
        <i class="bi bi-check-circle-fill"></i> Profile updated successfully
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
/* ── Modal ── */
function openModal()  { document.getElementById('updateModal').classList.add('open'); }
function closeModal() { document.getElementById('updateModal').classList.remove('open'); }
function bgClose(e)   { if (e.target === document.getElementById('updateModal')) closeModal(); }

/* ── Avatar live preview ── */
function previewAvatar(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const src = e.target.result;

        /* --- modal preview --- */
        const init = document.getElementById('modalAvInit');
        let   img  = document.getElementById('modalAvImg');
        if (init) init.style.display = 'none';
        if (!img) {
            img           = document.createElement('img');
            img.id        = 'modalAvImg';
            img.className = 'modal-av-img';
            img.alt       = 'avatar';
            document.querySelector('.modal-avatar-btn').prepend(img);
        }
        img.src = src;

        /* --- card live preview --- */
        const cardInit = document.getElementById('cardAvatarInit');
        let   cardImg  = document.getElementById('cardAvatarImg');
        if (cardInit) cardInit.style.display = 'none';
        if (!cardImg) {
            cardImg                 = document.createElement('img');
            cardImg.id              = 'cardAvatarImg';
            cardImg.alt             = 'avatar';
            cardImg.crossOrigin     = 'anonymous';
            cardImg.style.cssText   = 'width:100%;height:100%;object-fit:cover;display:block;';
            document.querySelector('.card-avatar-circle').prepend(cardImg);
        }
        cardImg.src = src;

        /* enable download */
        document.getElementById('dlBtn').classList.remove('disabled');
    };
    reader.readAsDataURL(input.files[0]);
}

/* ── Barcode decoration ── */
(function () {
    const wrap  = document.getElementById('barcodeLines');
    const numEl = document.getElementById('barcodeNum');
    if (!wrap) return;
    [1,2,1,3,1,2,2,1,3,2,1,1,2,3,1,2,1,3,1,2,3,1,2,1,2].forEach(function(w) {
        const s       = document.createElement('span');
        s.style.width = (w * 2.2) + 'px';
        s.style.height = (16 + Math.floor(Math.random() * 14)) + 'px';
        wrap.appendChild(s);
    });
    const empId = '{{ addslashes($user->employee_id ?? "") }}';
    let code = empId
        ? empId.toString().replace(/\D/g,'').padStart(12,'0').substring(0,12)
        : Array.from({length:12}, function(){ return Math.floor(Math.random()*10); }).join('');
    numEl.textContent = code.replace(/(.{4})/g, '$1 ').trim();
})();

/* ── Download card as JPG ── */
function downloadCard() {
    const card    = document.getElementById('idCard');
    const actions = document.getElementById('cardActions');
    actions.style.display = 'none';

    html2canvas(card, {
        scale: 3,
        useCORS: true,
        allowTaint: true,
        backgroundColor: '#ffffff',
        logging: false,
        imageTimeout: 0
    }).then(function(canvas) {
        actions.style.display = '';
        const link    = document.createElement('a');
        link.download = '{{ \Illuminate\Support\Str::slug($user->name) }}_id_card.jpg';
        link.href     = canvas.toDataURL('image/jpeg', 0.95);
        link.click();
    }).catch(function() {
        actions.style.display = '';
        alert('Download failed. Please try again.');
    });
}

/* ── Toast ── */
@if(session('success'))
    (function(){
        var t = document.getElementById('toast');
        t.classList.add('show');
        setTimeout(function(){ t.classList.remove('show'); }, 3200);
    })();
@endif

/* ── Re-open modal on validation errors ── */
@if($errors->any())
    openModal();
@endif
</script>
@endpush