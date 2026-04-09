@extends('layouts.header')

@section('title', 'Reset Password')
@section('page-title', 'Reset Password')

@push('styles')
<style>
.reset-page {
    display: flex;
    justify-content: center;
    padding: 2rem 1rem;
}

.reset-card {
    width: 420px;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.reset-header {
    text-align: center;
    font-size: 20px;
    font-weight: 600;
    padding: 15px;
    background: linear-gradient(135deg,#6a2fe0,#9b5de5);
    color: #fff;
    border-radius: 15px 15px 0 0;
}

.reset-body {
    padding: 20px;
}

.reset-input {
    width: 100%;
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #ddd;
    margin-bottom: 12px;
}

.reset-input:focus {
    border-color: #7c3aed;
    box-shadow: 0 0 0 2px rgba(124,58,237,0.1);
}

.reset-btn {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 10px;
    background: linear-gradient(135deg,#6a2fe0,#9b5de5);
    color: #fff;
    font-weight: bold;
}
</style>
@endpush

@section('content')

<div class="reset-page">
    <div class="reset-card">

        <div class="reset-header">
             Reset Password
        </div>

        <div class="reset-body">

            {{-- Success --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('reset_password.post') }}">
                @csrf

                <!-- Current Password -->
                <input type="password" name="current_password" class="reset-input"
                    placeholder="Enter current password" required>

                <!-- New Password -->
                <input type="password" name="password" class="reset-input"
                    placeholder="New Password" required>

                <!-- Confirm Password -->
                <input type="password" name="password_confirmation" class="reset-input"
                    placeholder="Confirm Password" required>

                <button type="submit" class="reset-btn">
                    Submit
                </button>
            </form>

        </div>
    </div>
</div>

@endsection