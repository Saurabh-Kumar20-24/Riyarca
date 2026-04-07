@include('layouts.header')
<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

<div class="page-header">
    <h2>Add Role</h2>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="alert-success">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
    </div>
@endif

{{-- Form Card --}}
<div class="form-card">
    <form method="POST" action="{{ route('role.store') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">Role Name</label>
            <input type="text"
                   name="role_name"
                   class="form-input {{ $errors->has('role_name') ? 'input-error' : '' }}"
                   placeholder="e.g. manager, hr, developer"
                   value="{{ old('role_name') }}">
            @error('role_name')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-add">
                <i class="bi bi-plus-circle"></i> Add Role
            </button>
        </div>
    </form>
</div>

<style>
.form-card {
    background: var(--primary);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 12px;
    padding: 28px;
    max-width: 480px;
}
.form-group {
    margin-bottom: 20px;
}
.form-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: rgba(255,255,255,.35);
    margin-bottom: 8px;
}
.form-input {
    width: 100%;
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
    color: #fff;
    outline: none;
    transition: border-color .2s;
    box-sizing: border-box;
}
.form-input:focus {
    border-color: var(--accent);
    background: rgba(255,255,255,.07);
}
.form-input::placeholder { color: rgba(255,255,255,.2); }
.input-error  { border-color: #ef4444 !important; }
.error-msg    { font-size: 12px; color: #ef4444; margin-top: 5px; display: block; }
.form-actions { display: flex; justify-content: flex-end; }
.alert-success {
    background: rgba(16,185,129,.15);
    border: 1px solid rgba(16,185,129,.3);
    color: #10b981;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
    max-width: 480px;
}
</style>

@include('layouts.footer')