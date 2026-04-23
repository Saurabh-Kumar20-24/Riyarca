@php
$company = [
'name' => 'SEO',
'name2' => 'Magics',
'tagline' => 'Build something great with us',
'description' => 'Join a team of curious, driven people. We move fast and care deeply about craft and culture.',
'website' => 'https://seo-magics.com/',
'perks' => [
'Competitive salary & growth',
'5-day work week',
'Health & wellness benefits',
'Learning & development budget',
],
];
@endphp


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply — Careers</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Sora:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #F2F2F2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            display: grid;
            grid-template-columns: 1fr 1.6fr;
            max-width: 980px;
            width: 100%;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 8px 56px rgba(30, 94, 217, .13);
        }

        /* ── Left panel ── */
        .left {
            background: linear-gradient(160deg, #1E5ED9 0%, #6A2FE0 40%, #D92BBF 75%, #FF6A3D 100%);
            padding: 56px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .left::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
        }

        .left::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
        }

        .left::before,
        .left::after {
            pointer-events: none;
        }

        .left a {
            position: relative;
            z-index: 2;
            color: #fff;
            text-decoration: underline;
        }

        .logo {
            font-family: 'Sora', sans-serif;
            font-size: 21px;
            font-weight: 600;
            color: #fff;
            letter-spacing: .3px;
            position: relative;
        }

        .logo span {
            opacity: .75;
            font-weight: 400;
        }

        .left-mid {
            position: relative;
        }



        .tag {
            display: inline-block;
            background: rgba(255, 255, 255, .18);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, .25);
            letter-spacing: .6px;
            text-transform: uppercase;
        }

        .left-mid h1 {
            font-family: 'Sora', sans-serif;
            font-size: 28px;
            font-weight: 600;
            color: #fff;
            line-height: 1.35;
            margin-bottom: 14px;
        }

        .left-mid p {
            font-size: 13.5px;
            color: rgba(255, 255, 255, .65);
            line-height: 1.75;
        }

        .perks {
            margin-top: 36px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .perk {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, .85);
            font-size: 13px;
        }

        .perk-icon {
            width: 26px;
            height: 26px;
            flex-shrink: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .25);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 12px;
        }

        .left-foot {
            font-size: 11px;
            color: rgba(255, 255, 255, .35);
            position: relative;
        }

        /* ── Right panel ── */
        .right {
            background: #fff;
            padding: 52px 48px;
        }

        .right h2 {
            font-family: 'Sora', sans-serif;
            font-size: 22px;
            font-weight: 600;
            color: #1F2A44;
            margin-bottom: 4px;
        }

        .right .sub {
            font-size: 13px;
            color: #aaa;
            margin-bottom: 36px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .full {
            grid-column: 1 / -1;
        }

        .fld label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: #6A2FE0;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .fld input,
        .fld select {
            width: 100%;
            border: 1.5px solid #e4e4f0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: #1F2A44;
            background: #fafafe;
            outline: none;
            transition: border-color .2s, background .2s;
            appearance: none;
        }

        .fld input:focus,
        .fld select:focus {
            border-color: #6A2FE0;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(106, 47, 224, .08);
        }

        .fld input.is-invalid,
        .fld select.is-invalid {
            border-color: #D92BBF;
        }

        .upload-label {
            display: block;
            border: 1.5px dashed #c8b8f5;
            border-radius: 10px;
            padding: 28px;
            text-align: center;
            background: #fafafe;
            cursor: pointer;
            transition: border-color .2s, background .2s;
        }

        .upload-label:hover {
            border-color: #6A2FE0;
            background: #f5f0ff;
        }

        .upload-label input {
            display: none;
        }

        .upload-label .up-icon {
            font-size: 26px;
            margin-bottom: 8px;
            color: #c8b8f5;
        }

        .upload-label strong {
            font-size: 13px;
            color: #555;
            display: block;
        }

        .upload-label small {
            font-size: 12px;
            color: #bbb;
        }

        #file-name {
            font-size: 12px;
            color: #6A2FE0;
            margin-top: 6px;
            font-weight: 500;
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(90deg, #1E5ED9 0%, #6A2FE0 50%, #D92BBF 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: .3px;
            transition: opacity .2s, transform .1s;
            margin-top: 4px;
        }

        .btn:hover {
            opacity: .88;
        }

        .btn:active {
            transform: scale(.99);
        }

        .btn:disabled {
            opacity: .55;
            cursor: not-allowed;
        }

        .notice {
            font-size: 11px;
            color: #ccc;
            text-align: center;
            margin-top: 14px;
        }

        /* alerts */
        .alert {
            padding: 13px 18px;
            border-radius: 10px;
            font-size: 13.5px;
            margin-bottom: 24px;
        }

        .alert-success {
            background: #eef9ee;
            color: #276827;
            border: 1px solid #b3e0b3;
        }

        .alert-error {
            background: #fff0f8;
            color: #a3155a;
            border: 1px solid #f5b8d8;
        }

        @media (max-width: 680px) {
            .card {
                grid-template-columns: 1fr;
            }

            .left {
                padding: 36px 28px;
                min-height: auto;
            }

            .perks {
                display: none;
            }

            .right {
                padding: 36px 24px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="card">

        <div class="left">
            <div class="logo">{{ $company['name'] }}<span> {{ $company['name2'] }}</span></div>
            <div class="left-mid">
                <div class="tag">We're hiring</div>
                <h1>{{ $company['tagline'] }}</h1>
                <p>{{ $company['description'] }}</p>
                <div class="perks">
                    @foreach($company['perks'] as $perk)
                    <div class="perk">
                        <div class="perk-icon">✓</div>{{ $perk }}
                    </div>
                    @endforeach
                </div>
            </div>

            <a href="{{ url($company['website'])}}" target="_blank">seo-magics.com</a>
        </div>

        <div class="right">
            <h2>Apply for a position</h2>
            <p class="sub">Takes about 3 minutes. We read every application.</p>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('hiring.storeApplication') }}" enctype="multipart/form-data" id="applyForm">
                @csrf
                <div class="form-grid">

                    <div class="fld">
                        <label>Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            placeholder="Rahul Sharma"
                            class="{{ $errors->has('name') ? 'is-invalid' : '' }}" required>
                    </div>

                    <div class="fld">
                        <label>Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="rahul@email.com"
                            class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                    </div>

                    <div class="fld">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            placeholder="+91 98765 43210"
                            class="{{ $errors->has('phone') ? 'is-invalid' : '' }}" required>
                    </div>

                    <div class="fld">
                        <label>Position Applying For</label>
                        <select name="job_position_id"
                            class="{{ $errors->has('job_position_id') ? 'is-invalid' : '' }}" required>
                            <option value="">— Select a role —</option>
                            @foreach($positions as $position)
                            <option value="{{ $position->id }}"
                                {{ old('job_position_id') == $position->id ? 'selected' : '' }}>
                                {{ $position->title }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="fld full">
                        <label>Resume</label>
                        <label class="upload-label">
                            <input type="file" name="resume" accept=".pdf,.doc,.docx"
                                onchange="document.getElementById('file-name').textContent = this.files[0]?.name ?? ''">
                            <div class="up-icon">&#128206;</div>
                            <strong>Upload PDF, DOC or DOCX</strong>
                            <small>Max file size: 2 MB</small>
                            <div id="file-name"></div>
                        </label>
                    </div>

                    <div class="full">
                        <button type="submit" class="btn" id="submitBtn">Submit Application</button>
                        <p class="notice">Your information is handled securely and used only for hiring purposes.</p>
                    </div>

                </div>
            </form>
        </div>

    </div>

    <script>
        document.getElementById('applyForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.textContent = 'Submitting…';
        });
    </script>

</body>

</html>