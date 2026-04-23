<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding Complete – SeoMagics</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f4f6fb;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 48px 40px;
            text-align: center;
            max-width: 480px;
            width: 90%;
        }

        .icon {
            font-size: 64px;
            margin-bottom: 20px;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 12px;
            font-size: 24px;
        }

        p {
            color: #666;
            line-height: 1.7;
            font-size: 15px;
        }

        .divider {
            border: none;
            border-top: 1px solid #f0f0f0;
            margin: 24px 0;
        }

        .btn-login {
            display: inline-block;
            margin-top: 8px;
            padding: 13px 36px;
            background: linear-gradient(90deg, #1E5ED9, #6A2FE0, #D92BBF, #FF6A3D);
            color: #fff !important;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
            transition: background 0.2s;
        }

        .btn-login:hover {
            opacity: 0.9;
        }

        .note {
            margin-top: 20px;
            font-size: 13px;
            color: #aaa;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="icon">🎉</div>
        <h2>Onboarding Complete!</h2>
        <p>Thank you for acknowledging the company policies.<br>Your e-signature has been recorded successfully.</p>
        <hr class="divider">
        <p>You can now log in to your account and get started with your work.</p>
        <a href="{{ route('login') }}" class="btn-login">Go to Login →</a>
        <p class="note">If you face any issues, please contact HR.</p>
    </div>
</body>

</html>