<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding – SeoMagics</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f4f6fb;
            font-family: Arial, sans-serif;
        }

        .onboard-card {
            max-width: 720px;
            margin: 50px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .onboard-header {
            background: #2c3e50;
            padding: 28px 32px;
            color: #fff;
        }

        .onboard-header h2 {
            margin: 0;
            font-size: 22px;
        }

        .onboard-header p {
            margin: 6px 0 0;
            opacity: 0.8;
            font-size: 14px;
        }

        .onboard-body {
            padding: 32px;
        }

        .policy-box {
            background: #f9f9f9;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            max-height: 220px;
            overflow-y: auto;
            font-size: 14px;
            line-height: 1.8;
            color: #444;
        }

        .policy-title {
            font-weight: 700;
            font-size: 15px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .sign-box {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            overflow: hidden;
            cursor: crosshair;
            background: #fff;
        }

        .btn-submit {
            background: linear-gradient(90deg, #1E5ED9, #6A2FE0, #D92BBF, #FF6A3D);
            color: #fff !important;
            padding: 12px 32px;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: #1a252f;
        }

        .btn-clear {
            background: #e5e7eb;
            color: #333;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            margin-top: 8px;
        }

        canvas {
            display: block;
            width: 100%;
        }
    </style>
</head>

<body>

    <div class="onboard-card">
        <div class="onboard-header">
            <h2>Welcome, {{ $employee->name }}! 👋</h2>
            <p>Please review and acknowledge the company policies below to complete your onboarding.</p>
        </div>
        <div class="onboard-body">

            @if($policies->isEmpty())
            <div class="alert alert-info">No policies assigned for your role yet. Please contact HR.</div>
            @else
            @foreach($policies as $policy)
            <div style="margin-bottom: 24px;">
                <div class="policy-title">
                    📄 {{ $policy->title }}
                    <span style="font-size:12px; color:#888; font-weight:400;">
                        (v{{ $policy->version }})
                    </span>
                </div>
                <div class="policy-box">{{ $policy->content }}</div>
            </div>
            @endforeach

            <form method="POST" action="{{ route('onboarding.acknowledge', $token) }}" id="onboardForm">
                @csrf

                <div style="margin-bottom: 20px;">
                    <label style="font-weight:600; display:block; margin-bottom:10px;">
                        E-Signature <span style="color:red;">*</span>
                        <span style="font-size:12px; font-weight:400; color:#888;">
                            — Draw your signature below
                        </span>
                    </label>
                    <canvas id="signatureCanvas" class="sign-box" width="640" height="160"></canvas>
                    <button type="button" class="btn-clear" onclick="clearSignature()">
                        ✕ Clear Signature
                    </button>
                    <input type="hidden" name="signature" id="signatureInput">
                    @if($errors->has('signature'))
                    <p style="color:red; font-size:13px; margin-top:6px;">
                        {{ $errors->first('signature') }}
                    </p>
                    @endif
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display:flex; align-items:flex-start; gap:10px; cursor:pointer;">
                        <input type="checkbox" id="confirmCheck"
                            style="width:16px; height:16px; margin-top:3px; flex-shrink:0;">
                        <span style="font-size:14px; color:#444;">
                            I have read and understood all the above policies. I agree to abide by them.
                        </span>
                    </label>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn" disabled>
                    ✅ Acknowledge & Submit
                </button>
            </form>
            @endif
        </div>
    </div>

    <script>
        const canvas = document.getElementById('signatureCanvas');
        const ctx = canvas.getContext('2d');
        let drawing = false;

        canvas.addEventListener('mousedown', e => {
            drawing = true;
            ctx.beginPath();
            ctx.moveTo(pos(e).x, pos(e).y);
        });
        canvas.addEventListener('mousemove', e => {
            if (!drawing) return;
            ctx.lineTo(pos(e).x, pos(e).y);
            ctx.strokeStyle = '#1a252f';
            ctx.lineWidth = 2;
            ctx.stroke();
        });
        canvas.addEventListener('mouseup', () => drawing = false);
        canvas.addEventListener('mouseleave', () => drawing = false);

        canvas.addEventListener('touchstart', e => {
            e.preventDefault();
            drawing = true;
            ctx.beginPath();
            ctx.moveTo(pos(e.touches[0]).x, pos(e.touches[0]).y);
        });
        canvas.addEventListener('touchmove', e => {
            e.preventDefault();
            if (!drawing) return;
            ctx.lineTo(pos(e.touches[0]).x, pos(e.touches[0]).y);
            ctx.strokeStyle = '#1a252f';
            ctx.lineWidth = 2;
            ctx.stroke();
        });
        canvas.addEventListener('touchend', () => drawing = false);

        function pos(e) {
            const r = canvas.getBoundingClientRect();
            return {
                x: (e.clientX - r.left) * (canvas.width / r.width),
                y: (e.clientY - r.top) * (canvas.height / r.height)
            };
        }

        function clearSignature() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        document.getElementById('confirmCheck').addEventListener('change', function() {
            document.getElementById('submitBtn').disabled = !this.checked;
        });

        document.getElementById('onboardForm').addEventListener('submit', function(e) {
            const blank = document.createElement('canvas');
            blank.width = canvas.width;
            blank.height = canvas.height;
            if (canvas.toDataURL() === blank.toDataURL()) {
                e.preventDefault();
                alert('Please draw your signature before submitting.');
                return;
            }
            document.getElementById('signatureInput').value = canvas.toDataURL();
        });
    </script>

</body>

</html>