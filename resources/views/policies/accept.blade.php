<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Policies – SeoMagics</title>
    <style>
        body {
            background: #f4f6fb;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 30px 16px;
        }

        .wrap {
            max-width: 720px;
            margin: 0 auto;
        }

        .top-card {
            background: #2c3e50;
            color: #fff;
            border-radius: 12px 12px 0 0;
            padding: 28px 32px;
        }

        .top-card h2 {
            margin: 0;
            font-size: 22px;
        }

        .top-card p {
            margin: 6px 0 0;
            opacity: .8;
            font-size: 14px;
        }

        .body-card {
            background: #fff;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 32px;
        }

        .policy-item {
            margin-bottom: 28px;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 24px;
        }

        .policy-item:last-of-type {
            border-bottom: none;
        }

        .policy-title {
            font-weight: 700;
            font-size: 15px;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .policy-box {
            background: #f9f9f9;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            max-height: 180px;
            overflow-y: auto;
            font-size: 14px;
            line-height: 1.8;
            color: #444;
            margin-bottom: 12px;
        }

        .policy-check {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .policy-check input {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .policy-check span {
            font-size: 14px;
            color: #555;
        }

        .btn-accept {
            background: #2c3e50;
            color: #fff;
            border: none;
            padding: 13px 0;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            width: 100%;
            margin-top: 8px;
        }

        .btn-accept:hover {
            background: #1a252f;
        }

        .btn-accept:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .progress-bar-wrap {
            background: #e5e7eb;
            border-radius: 99px;
            height: 6px;
            margin-bottom: 24px;
            overflow: hidden;
        }

        .progress-bar-fill {
            background: #2c3e50;
            height: 100%;
            border-radius: 99px;
            transition: width .3s;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="top-card">
            <h2>📋 Company Policies</h2>
            <p>Welcome, {{ auth()->user()->name }}! Please read and accept all policies to continue.</p>
        </div>
        <div class="body-card">

            @if($policies->isEmpty())
            <div style="text-align:center; color:#888; padding:40px 0;">
                No policies assigned for your role yet.
                <br><br>
                <form method="POST" action="{{ route('policies.acceptAll') }}">
                    @csrf
                    <button type="submit" class="btn-accept" style="width:auto; padding:12px 32px;">
                        Continue to Dashboard →
                    </button>
                </form>
            </div>
            @else
            <div class="progress-bar-wrap">
                <div class="progress-bar-fill" id="progressBar" style="width:0%"></div>
            </div>
            <p style="font-size:13px; color:#888; margin-top:-16px; margin-bottom:20px;">
                <span id="acceptedCount">0</span> of {{ $policies->count() }} policies accepted
            </p>

            <form method="POST" action="{{ route('policies.acceptAll') }}" id="acceptForm">
                @csrf

                @foreach($policies as $policy)
                <div class="policy-item">
                    <div class="policy-title">
                        📄 {{ $policy->title }}
                        <span style="font-size:12px; color:#888; font-weight:400;">
                            (v{{ $policy->version }})
                        </span>
                    </div>
                    <div class="policy-box">
                        @php
                        $points = json_decode($policy->content, true);
                        @endphp
                        @if(is_array($points))
                        <ol style="padding-left:20px; margin:0;">
                            @foreach($points as $point)
                            <li style="margin-bottom:8px; line-height:1.7;">{{ $point }}</li>
                            @endforeach
                        </ol>
                        @else
                        {{ $policy->content }}
                        @endif
                    </div>
                    <label class="policy-check">
                        <input type="checkbox" class="policy-checkbox" onchange="updateProgress()">
                        <span>I have read and agree to this policy</span>
                    </label>
                </div>
                @endforeach

                <button type="submit" class="btn-accept" id="acceptBtn" disabled>
                    ✅ Accept All & Continue to Dashboard
                </button>
            </form>
            @endif
        </div>
    </div>

    <script>
        const total = {
            {
                $policies - > count()
            }
        };
        const bar = document.getElementById('progressBar');
        const countEl = document.getElementById('acceptedCount');
        const acceptBtn = document.getElementById('acceptBtn');

        function updateProgress() {
            const checked = document.querySelectorAll('.policy-checkbox:checked').length;
            const pct = total > 0 ? Math.round((checked / total) * 100) : 100;
            bar.style.width = pct + '%';
            countEl.textContent = checked;
            acceptBtn.disabled = checked < total;
        }
    </script>
</body>

</html>