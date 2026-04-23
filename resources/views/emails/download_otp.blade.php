<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 480px; margin: auto; background: #fff; border-radius: 8px; padding: 32px; }
        .otp { font-size: 36px; font-weight: bold; letter-spacing: 8px; color: #2563eb;
               text-align: center; padding: 20px; background: #eff6ff;
               border-radius: 8px; margin: 24px 0; }
        .footer { font-size: 12px; color: #999; margin-top: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hello, {{ $managerName }}</h2>
        <p>You requested to download employee data. Use the OTP below to proceed:</p>
        <div class="otp">{{ $otp }}</div>
        <p>This OTP is valid for <strong>5 minutes</strong> and can only be used once.</p>
        <p>If you did not request this, please ignore this email.</p>
        <div class="footer">This is an automated message. Do not reply.</div>
    </div>
</body>
</html>