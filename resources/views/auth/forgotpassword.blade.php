<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #F2F2F2;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .reset-page {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .reset-card {
            width: 400px;
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(31, 42, 68, 0.15);
        }

        .reset-card h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #1F2A44;
        }

        .form-control {
            width: 100%;
            padding: 11px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            outline: none;
            font-size: 14px;
            color: #1F2A44;
            transition: 0.2s;
        }

        .form-control:focus {
            border-color: #6A2FE0;
            box-shadow: 0 0 0 2px rgba(106, 47, 224, 0.15);
        }

        .btn-custom {
            width: 100%;
            padding: 11px;
            background: linear-gradient(135deg, #1E5ED9, #6A2FE0, #D92BBF);
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: 500;
        }

        .btn-custom:hover {
            background: linear-gradient(135deg, #3B5BDB, #A84CE6, #FF7A45);
            transform: translateY(-1px);
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 14px;
        }

        .alert-danger {
            background: #ffe5dc;
            color: #FF6A3D;
        }

        .alert-success {
            background: #e6f9ec;
            color: #22c55e;
        }

        .text-link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="reset-page">
        <div class="reset-card">
            <h3>Forgot Password</h3>
            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            <form action="{{ route('change_password') }}" method="POST">
                @csrf
                <input type="password" name="password" class="form-control" placeholder="New Password" required>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                <button type="submit" class="btn-custom">
                    Submit
                </button>
            </form>
        </div>
    </div>
</body>

</html>