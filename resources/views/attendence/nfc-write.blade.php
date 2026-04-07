@include('layouts.header')

<div style="max-width:480px; margin:60px auto;
    background:var(--primary);
    border:1px solid rgba(255,255,255,.08);
    border-radius:16px;
    padding:32px;
    color:#fff;
    box-shadow:0 10px 30px rgba(0,0,0,.25);">

    <div style="font-size:48px; margin-bottom:12px;">🪪</div>
    <h2 style="margin-bottom:6px;">Write NFC Card</h2>
    <p style="color:#94a3b8; font-size:14px; margin-bottom:28px;">
        Select employee → tap Write → hold card to phone back
    </p>

    {{-- Steps --}}
    <div style="display:flex; justify-content:center;
        gap:12px; margin-bottom:28px; align-items:center;">
        <div id="step1" class="step active">1. Select</div>
        <div style="color:#94a3b8;">→</div>
        <div id="step2" class="step">2. Write</div>
        <div style="color:#94a3b8;">→</div>
        <div id="step3" class="step">3. Done</div>
    </div>

    {{-- Dropdown --}}
    <select id="empSelect" onchange="onSelect(this)" style="width:100%;
    padding:12px 16px;
    border-radius:10px;
    background:rgba(255,255,255,.05);
    color:#fff;
    border:1px solid rgba(255,255,255,.08);
    font-size:15px;
    margin-bottom:16px;">
        <option value="">-- Select Employee --</option>
        @foreach($employees as $emp)
            <option value="{{ $emp->employee_id }}">
                {{ $emp->name }} — {{ $emp->employee_id }}
            </option>
        @endforeach
    </select>

    {{-- Preview --}}
    <div style="background:rgba(0,0,0,.15); border-radius:10px; padding:14px;
        margin-bottom:20px; border:1px dashed rgba(255,255,255,.1);">
        <div style="font-size:12px; color:#94a3b8; margin-bottom:6px;">
            Will write to card:
        </div>
        <div id="previewId" style="font-size:26px; font-weight:700;
            color:var(--accent); letter-spacing:3px;">—</div>
    </div>

    {{-- Write Button --}}
    <button onclick="writeNFC()" id="writeBtn" disabled style="width:100%;
        padding:15px; background:#334155; color:#94a3b8;
        border:none; border-radius:12px; font-size:16px;
        font-weight:600; cursor:not-allowed;
        margin-bottom:16px; transition:all 0.2s;">
        📝 Write to NFC Card
    </button>

    {{-- Status --}}
    <div id="statusMsg" style="font-size:14px; border-radius:10px;
        padding:12px; display:none; margin-top:8px;"></div>

    {{-- Not Supported --}}
    <div id="notSupported" style="display:none; background:#7c2d12;
        border:1px solid #ea580c; border-radius:10px; padding:14px;
        color:#fed7aa; font-size:13px; margin-top:12px;">
        ⚠️ Web NFC only works on <strong>Chrome for Android</strong>.
    </div>
</div>

<style>
.step {
    background:rgba(255,255,255,.05);
    color:rgba(255,255,255,.5);
}

.step.active {
    background:var(--accent);
    color:#fff;
}

.step.done {
    background:rgba(16,185,129,.15);
    color:var(--success);
}

#empSelect {
    width:100%;
    padding:12px 16px;
    border-radius:10px;
    background:rgba(255,255,255,.05);
    color:#fff;
    border:1px solid rgba(255,255,255,.08);
    font-size:15px;
    margin-bottom:16px;
    color-scheme: dark; /* important */
}

#empSelect option {
    background: var(--primary);
    color: #fff;
}
</style>

<script>
window.addEventListener('load', () => {
    if (!('NDEFReader' in window)) {
        document.getElementById('notSupported').style.display = 'block';
        document.getElementById('writeBtn').style.display = 'none';
    }
});

function onSelect(select) {
    const val = select.value;
    const btn = document.getElementById('writeBtn');
    document.getElementById('previewId').textContent = val || '—';

    if (val) {
        btn.disabled         = false;
        btn.style.background = 'var(--accent)';
        btn.style.color      = '#fff';
        btn.style.cursor     = 'pointer';
        setStep(2);
    } else {
        btn.disabled         = true;
        btn.style.background = '#334155';
        btn.style.color      = '#94a3b8';
        btn.style.cursor     = 'not-allowed';
        setStep(1);
    }
}

async function writeNFC() {
    const empId = document.getElementById('empSelect').value;
    if (!empId) return;

    showMsg('info', '📡 Hold NFC card to the back of your phone...');

    try {
        // NDEFReader is a built-in Browser API that lets your phone's Chrome browser talk to NFC hardware directly.
        // No app needed. No plugin needed.
        // Just JavaScript in your browser.
        //it has two major work, first detect card and read the data, secornd write data on blank card  
        const ndef = new NDEFReader();
        await ndef.write({
            records: [{ recordType: "text", data: empId }]
        });

        showMsg('success', '✅ Card written! <strong>' + empId + '</strong>');
        setStep(3);

        // Reset after 4 seconds
        setTimeout(() => {
            document.getElementById('empSelect').value         = '';
            document.getElementById('previewId').textContent   = '—';
            document.getElementById('statusMsg').style.display = 'none';
            const btn            = document.getElementById('writeBtn');
            btn.disabled         = true;
            btn.style.background = '#334155';
            btn.style.color      = '#94a3b8';
            btn.style.cursor     = 'not-allowed';
            setStep(1);
        }, 4000);

    } catch (err) {
        showMsg('error',
            err.name === 'NotAllowedError'
                ? '❌ NFC permission denied.'
                : '❌ ' + err.message
        );
    }
}

function showMsg(type, html) {
    const box = document.getElementById('statusMsg');
    const map = {
        info:    ['#1e3a5f', '#93c5fd', '#3b82f6'],
        success: ['#064e3b', '#6ee7b7', '#10b981'],
        error:   ['#450a0a', '#fca5a5', '#ef4444'],
    };
    const [bg, color, border] = map[type];
    box.style.cssText = `display:block; background:${bg}; color:${color};
        border:1px solid ${border}; border-radius:10px; padding:12px;`;
    box.innerHTML = html;
}

function setStep(n) {
    [1, 2, 3].forEach(i => {
        document.getElementById('step' + i).className =
            'step' + (i < n ? ' done' : i === n ? ' active' : '');
    });
}
</script>

@include('layouts.footer')