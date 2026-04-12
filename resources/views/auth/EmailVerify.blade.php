<!DOCTYPE html>
<html>
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Email Verify</title>

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
    body {
        font-family: Arial;
        background: #F2F2F2;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .verify-card {
        width: 400px;
        background: #fff;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(31, 42, 68, 0.15);
    }

    /* Heading */
    .verify-card h3 {
        text-align: center;
        margin-bottom: 20px;
        color: #1F2A44;
    }
    #message {
        text-align: center;
        margin-bottom: 10px;
        font-size: 14px;
    }
    .input-row {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }
    .form-control {
        width: 100%;
        padding: 10px;
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

    .btn {
        padding: 10px;
        min-width: 100px;
        background: linear-gradient(135deg, #1E5ED9, #6A2FE0, #D92BBF);
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn:hover {
        background: linear-gradient(135deg, #3B5BDB, #A84CE6, #FF7A45);
        transform: translateY(-1px);
    }
    #otpSection {
        margin-top: 10px;
    }
    .hidden { display: none; }
    .error {
        color: #FF6A3D;
        margin-bottom: 10px;
    }
    .success {
        color: #22c55e;
        margin-bottom: 10px;
    }
    </style>
    <style>
    .input-row {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }
    .input-row .form-control {
        flex: 1;
        min-width: 0;
    }
    .input-row .btn {
        width: 120px;
        white-space: nowrap;
    }
    .otp-row {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }
    .otp-row .form-control {
        flex: 1;
    }
    .otp-row .btn {
        width: 120px;
    }
</style>
</head>
<body>

<div class="verify-card">
    <h3>Email Verification</h3>
    <div id="message"></div>
    <div class="input-row">
    <input type="email" id="email" class="form-control" placeholder="Enter Email">
    <button class="btn" onclick="sendOtp()">Verify</button>
</div>
<div id="otpSection" class="hidden">
    <div class="otp-row">
        <input type="text" id="otp" class="form-control" placeholder="Enter OTP">
        <button class="btn" onclick="verifyOtp()">Submit OTP</button>
    </div>
</div>
<script>
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
function sendOtp() {
    let email = document.getElementById('email').value;
    fetch("{{ route('send.otp') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token
        },
        body: JSON.stringify({ email: email })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('message').innerHTML = data.message;
        if(data.status === true) {
            document.getElementById('otpSection').classList.remove('hidden');
        }
    });
}
function verifyOtp() {
    let email = document.getElementById('email').value;
    let otp = document.getElementById('otp').value;

    fetch("{{ route('verify.otp') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token
        },
        body: JSON.stringify({ email: email, otp: otp })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('message').innerHTML = data.message;

        if(data.status === true) {
            window.location.href = "{{ route('forgot_password') }}";
        }
    });
}
</script>

</body>
</html>