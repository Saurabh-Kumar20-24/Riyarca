@extends('layouts.header')

@section('title', 'Add Role')
@section('page-title', 'Add Role')

@section('content')

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
<div class="form-card" style="max-width: 480px;">

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

@endsection