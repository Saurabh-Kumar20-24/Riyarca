<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(90deg, #1E5ED9, #6A2FE0, #D92BBF, #FF6A3D);
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            color: #fff;
            margin: 0;
            font-size: 24px;
        }

        .body {
            padding: 30px;
            color: #333;
        }

        .body p {
            line-height: 1.7;
        }

        .btn {
            display: inline-block;
            margin: 20px 0;
            padding: 14px 32px;
            background: linear-gradient(90deg, #1E5ED9, #6A2FE0, #D92BBF, #FF6A3D);
            color: #fff !important;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            background: #f4f4f4;
            text-align: center;
            padding: 16px;
            font-size: 12px;
            color: #999;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to SeoMagics</h1>
        </div>
        <div class="body">
            <p>Dear <strong>{{ $employee->name }}</strong>,</p>
            <p>We are excited to have you on board! Before you get started, please complete your onboarding by reading and acknowledging our company policies.</p>
            <p>Please click the button below to review and e-sign your acknowledgement:</p>
            <p style="text-align:center;">
                <a href="{{ $onboardingUrl }}" class="btn">Complete Onboarding</a>
            </p>
            <p>This link will expire in <strong>7 days</strong>. If you have any issues, please contact HR.</p>
            <p>Regards,<br><strong>SeoMagics HR Team</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} SeoMagics. All rights reserved.
        </div>
    </div>
</body>

</html>