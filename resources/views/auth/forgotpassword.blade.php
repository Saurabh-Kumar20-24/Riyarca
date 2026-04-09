<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f5f5f5;
}

.reset-page {
    display: flex;
    justify-content: center;
    padding: 60px 15px;
}

.reset-card {
    width: 400px;
    background: #fff;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.reset-card h3 {
    text-align: center;
    margin-bottom: 20px;
}

.form-control {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

.btn-custom {
    width: 100%;
    padding: 10px;
    background: #28a745;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.btn-custom:hover {
    background: #218838;
}

.alert {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
}

.alert-success {
    background: #d4edda;
    color: #155724;
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

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Success Message --}}
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