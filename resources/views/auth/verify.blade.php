<!DOCTYPE html>
<html>
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Email Verify</title>
<style>
body { font-family: Arial; background:#f5f5f5; }
.verify-card {
    width:400px;
    margin:80px auto;
    background:#fff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}
.form-control {
    width:100%;
    padding:10px;
    margin-bottom:10px;
    border-radius:8px;
    border:1px solid #ccc;
}

.btn {
    padding:10px;
    width:100%;
    background:#1a3b63;
    color:#fff;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

.hidden { display:none; }

.error { color:red; margin-bottom:10px; }
.success { color:green; margin-bottom:10px; }
</style>
</head>
<body>
<div class="verify-card">
<h3>Email Verification</h3>
<div id="message"></div>
<div style="display:flex; gap:10px;">
    <input type="email" id="email" class="form-control" placeholder="Enter Email">
    <button class="btn" onclick="sendOtp()">Verify</button>
</div>
<div id="otpSection" class="hidden">
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
            window.location.href = "{{ route('forget_password') }}";
        }
    });
}
</script>
</body>
</html>