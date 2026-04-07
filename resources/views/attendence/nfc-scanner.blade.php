@include('layouts.header')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div style="max-width:420px; margin:40px auto;
    background:rgba(255,255,255,.03);
    border-radius:24px; padding:40px 32px; text-align:center;
    color:#fff; font-family:'Segoe UI',sans-serif;
    border:1px solid rgba(255,255,255,.08);
    box-shadow:0 25px 60px rgba(0,0,0,0.4);">

    <p style="font-size:14px; color:#94a3b8; margin-bottom:4px;">
        Attendance System
    </p>
    <p style="font-size:24px; font-weight:700; margin-bottom:28px;">
        {{ config('app.name') }}
    </p>

    {{-- NFC Ring --}}
    <div id="nfcRing" style="width:140px; height:140px; border-radius:50%;
    background:var(--primary); margin:0 auto 28px; position:relative;
    display:flex; align-items:center; justify-content:center;
    border:1px solid rgba(255,255,255,.08);">
        <span style="font-size:52px; position:relative; z-index:1;">📡</span>
    </div>

    <p id="statusText" style="font-size:15px; color:#94a3b8;
        margin-bottom:20px; min-height:24px;">
        Tap "Start Scanning" to begin
    </p>

    <div id="result" style="display:none; border-radius:14px;
        padding:20px; margin-bottom:20px;"></div>

    <button id="scanBtn" onclick="startNFC()" style="width:100%;
    padding:14px; background:var(--accent); color:#fff; border:none;
    border-radius:12px; font-size:16px; font-weight:600; cursor:pointer;">
    📲 Start Scanning
</button>

    <div id="notSupported" style="display:none; background:#7c2d12;
        border:1px solid #ea580c; border-radius:10px; padding:14px;
        color:#fed7aa; font-size:13px; margin-top:16px;">
        ⚠️ Use <strong>Chrome on Android</strong> only.
    </div>
</div>

<style>
@keyframes ripple {
    0%   { opacity:1; transform:scale(0.9); }
    100% { opacity:0; transform:scale(1.3); }
}
#nfcRing::before, #nfcRing::after {
    content:''; position:absolute; border-radius:50%;
    border:2px solid #3b82f6; animation:ripple 2s infinite;
}
#nfcRing::before { width:165px; height:165px; animation-delay:0s; }
#nfcRing::after  { width:195px; height:195px; animation-delay:0.5s; }
</style>

<script>
const SCAN_URL = "{{ route('nfc.scan') }}";
const CSRF     = document.querySelector('meta[name="csrf-token"]').content;
let scanning   = false;
let nfcReader  = null;

window.addEventListener('load', () => {
    if (!('NDEFReader' in window)) {
        document.getElementById('notSupported').style.display = 'block';
        document.getElementById('scanBtn').disabled           = true;
        document.getElementById('scanBtn').style.background   = '#334155';
    }
});

async function startNFC() {
    if (scanning) { stopNFC(); return; }

    try {
        nfcReader = new NDEFReader();
        await nfcReader.scan();

        scanning = true;
        document.getElementById('scanBtn').textContent    = '⏹ Stop Scanning';
        document.getElementById('statusText').textContent = '🟢 Ready — Hold card near phone...';
        document.getElementById('result').style.display   = 'none';

        nfcReader.onreading = ({ message }) => {
            for (const record of message.records) {
                if (record.recordType === 'text') {
                    const decoder    = new TextDecoder(record.encoding || 'utf-8');
                    const employeeId = decoder.decode(record.data).trim();
                    handleScan(employeeId);
                    break;
                }
            }
        };

        nfcReader.onreadingerror = () => {
            document.getElementById('statusText').textContent = '❌ Could not read card. Try again.';
        };

    } catch (err) {
        document.getElementById('statusText').textContent =
            err.name === 'NotAllowedError'
                ? '❌ NFC permission denied.'
                : '❌ ' + err.message;
    }
}

function stopNFC() {
    scanning  = false;
    nfcReader = null;
    document.getElementById('scanBtn').textContent    = '📲 Start Scanning';
    document.getElementById('statusText').textContent = 'Tap "Start Scanning" to begin';
}

async function handleScan(employeeId) {
    document.getElementById('statusText').textContent = '⏳ Processing...';

    try {
        const res  = await fetch(SCAN_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept':       'application/json',
            },
            body: JSON.stringify({ employee_id: employeeId }),
        });

        const data = await res.json();

        if (data.status === 'success') {
            showSuccess(data);
        } else {
            showError(data.message || 'Something went wrong.');
        }

    } catch (e) {
        showError('Server error. Please try again.');
    }
}

function showSuccess(data) {
    const box  = document.getElementById('result');
    const isIn = data.type === 'check_in';

    box.style.cssText = `
        display:block; border-radius:14px; padding:20px; margin-bottom:20px;
        background:${isIn ? '#064e3b' : '#1e3a5f'};
        border:1px solid ${isIn ? '#10b981' : '#3b82f6'};
    `;
    box.innerHTML = `
        <div style="font-size:40px;">${isIn ? '✅' : '👋'}</div>
        <div style="font-size:22px; font-weight:700; margin:10px 0;">
            ${data.employee.name}
        </div>
        <div style="font-size:13px; color:#94a3b8; margin-bottom:12px;">
            ${data.employee.role} &bull; ${data.employee.employee_id}
        </div>
        <span style="padding:5px 18px; border-radius:99px; font-size:13px;
            font-weight:600;
            background:${isIn ? '#10b981' : '#3b82f6'}; color:#fff;">
            ${isIn ? '✅ Checked In' : '🔵 Checked Out'}
        </span>
        <div style="font-size:13px; color:#94a3b8; margin-top:12px;">
            ${data.date} at ${data.time}
        </div>
    `;

    document.getElementById('statusText').textContent = '✅ Scan successful!';

    // Auto reset after 5s for next employee
    setTimeout(() => {
        box.style.display = 'none';
        document.getElementById('statusText').textContent =
            '🟢 Ready — Hold card near phone...';
    }, 5000);
}

function showError(message) {
    const box = document.getElementById('result');
    box.style.cssText = `
        display:block; border-radius:14px; padding:20px; margin-bottom:20px;
        background:#450a0a; border:1px solid #ef4444;
    `;
    box.innerHTML = `
        <div style="font-size:40px;">❌</div>
        <div style="font-size:16px; font-weight:600;
            color:#fca5a5; margin-top:10px;">${message}</div>
        <div style="font-size:13px; color:#94a3b8; margin-top:6px;">
            Card not registered in system
        </div>
    `;
    document.getElementById('statusText').textContent = '❌ Unknown card';
}
</script>

@include('layouts.footer')