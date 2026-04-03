<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f2f5;
        }
        .card {
            background: #fff;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 380px;
        }
        h2 { font-size: 22px; font-weight: 600; color: #1a1a1a; margin-bottom: 6px; text-align: center; }
        .sub { font-size: 14px; color: #888; text-align: center; margin-bottom: 1.8rem; }
        label { display: block; font-size: 13px; color: #555; margin-bottom: 5px; font-weight: 500; }
 
        input[type=email],
        input[type=password],
        input[type=text] {
            width: 100%;
            padding: 11px 14px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: border .2s;
            color: #333;
            background: #fff;
        }
        input[type=email]:focus,
        input[type=password]:focus,
        input[type=text]:focus { border-color: #0f3460; }
 
        .err { color: red; font-size: 12px; margin-top: 4px; margin-bottom: 10px; }
        .mb { margin-bottom: 1.1rem; }
 
        /* Password wrapper */
        .pw-wrap {
            position: relative;
            margin-bottom: 0;
        }
        .pw-wrap input {
            padding-right: 44px;
            width: 100%;
        }
        .pw-wrap .eye-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            color: #aaa;
            display: flex;
            align-items: center;
        }
        .pw-wrap .eye-btn:hover { color: #0f3460; }
        .eye-icon { width: 18px; height: 18px; display: block; }
 
        /* Forgot password row */
        .forgot-row {
            display: flex;
            justify-content: flex-end;
            margin-top: 6px;
            margin-bottom: 1.1rem;
        }
        .forgot-row a {
            font-size: 12px;
            color: #0f3460;
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-row a:hover { text-decoration: underline; }
 
        .btn {
            width: 100%;
            padding: 12px;
            background: #0f3460;
            color: #fff;
            font-size: 15px;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background .2s;
        }
        .btn:hover { background: #16213e; }
 
        /* Help desk link */
        .helpdesk {
            text-align: center;
            margin-top: 1.1rem;
            font-size: 13px;
            color: #888;
        }
        .helpdesk a {
            color: #0f3460;
            text-decoration: none;
            font-weight: 500;
        }
        .helpdesk a:hover { text-decoration: underline; }
    </style>
</head>
<body>
 
<div class="card">
    <h2>Login to Riyarca</h2>
    <p class="sub">Sign in to your account</p>
 
    <form method="POST" action="{{ route('login') }}">
        @csrf
 
        {{-- Email --}}
        <div class="mb">
            <label for="email">User Id</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="Enter your User Id" required autofocus>
            @error('email')
                <p class="err">{{ $message }}</p>
            @enderror
        </div>
 
        {{-- Password --}}
        <div>
            <label for="password">Password</label>
            <div class="pw-wrap">
                <input type="password" id="password" name="password"
                       placeholder="••••••••" required>
 
                <button type="button" class="eye-btn" id="pwToggle" aria-label="Toggle password">
                    {{-- Closed eye = password hidden (default) --}}
                    <svg id="iconClosed" class="eye-icon" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                        <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                    {{-- Open eye = password visible --}}
                    <svg id="iconOpen" class="eye-icon" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                         style="display:none;">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="err">{{ $message }}</p>
            @enderror
        </div>
 
        {{-- Forgot Password --}}
        <div class="forgot-row">
            <a href="#">Forgot password?</a>
        </div>
 
        <button type="submit" class="btn">Login</button>
 
    </form>
 
    {{-- Help Desk --}}
    <p class="helpdesk">
        Need help? <a href="#">Contact Help Desk</a>
    </p>
</div>
 
<script>
    const pwToggle  = document.getElementById('pwToggle');
    const pwInput   = document.getElementById('password');
    const iconClosed = document.getElementById('iconClosed');
    const iconOpen   = document.getElementById('iconOpen');
 
    pwToggle.addEventListener('click', () => {
        if (pwInput.type === 'password') {
            pwInput.type             = 'text';
            iconClosed.style.display = 'none';
            iconOpen.style.display   = 'block';
        } else {
            pwInput.type             = 'password';
            iconClosed.style.display = 'block';
            iconOpen.style.display   = 'none';
        }
    });
</script>
 
</body>
</html>
