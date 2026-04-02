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
        select {
            width: 100%;
            padding: 11px 14px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: border .2s;
            color: #333;
            margin-bottom: 1.1rem;
            background: #fff;
        }
        input[type=email]:focus,
        input[type=password]:focus,
        select:focus { border-color: #0f3460; }
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
    </style>
</head>
<body>

    <div class="card">
        <h2>Login to Riyarca</h2>
        <p class="sub">Sign in to your account</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="you@example.com" required autofocus>
            @error('email')
        <p style="color:red; font-size:12px; margin-top:-10px; margin-bottom:10px;">{{ $message }}</p>
    @enderror

            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="••••••••" required>
             @error('password')
        <p style="color:red; font-size:12px; margin-top:-10px; margin-bottom:10px;">{{ $message }}</p>
    @enderror

            <label for="role">Select Role</label>
            <select id="role" name="role" required>
                <option value="" disabled selected>-- Select Role --</option>
                <option value="admin"   {{ old('role') == 'admin'   ? 'selected' : '' }}>Admin</option>
                <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                <option value="user"    {{ old('role') == 'user'    ? 'selected' : '' }}>User</option>
            </select>

             @error('role')
        <p style="color:red; font-size:12px; margin-top:-10px; margin-bottom:10px;">{{ $message }}</p>
    @enderror

            <button type="submit" class="btn">Login</button>

        </form>
    </div>

</body>
</html>