@extends('layouts.header')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@if(session('success'))
    <div class="alert-success-custom">{{ session('success') }}</div>
@endif

@section('content')
<div class="col-md-12">
    <div class="form-card">
        <h3>✏️ Edit Employee — {{ $employee->name }}</h3>

        <form method="POST" action="{{ route('employee.update', $employee->id) }}">
            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Full Name</label>
                        <input type="text" name="name" class="form-control-custom"
                               value="{{ old('name', $employee->name) }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Phone</label>
                        <input type="text" name="phone" class="form-control-custom"
                               value="{{ old('phone', $employee->phone) }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Email</label>
                        <input type="email" name="email" class="form-control-custom"
                               value="{{ old('email', $employee->email) }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">New Password</label>
                        <input type="password" name="password" class="form-control-custom">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">DOB</label>
                        <input type="text" name="dob" class="form-control-custom flatpickr-date"
                               value="{{ old('dob', $employee->dob) }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Joining Date</label>
                        <input type="text" name="joining_date" class="form-control-custom flatpickr-date"
                               value="{{ old('joining_date', $employee->joining_date) }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Role</label>
                        <select name="role_id" id="roleSelect" class="form-select-custom" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ old('role_id', $employee->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->role_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4" id="managerField">
                    <div class="form-group">
                        <label class="form-label-custom">Assign Manager</label>
                        <select name="assigned_manager" class="form-select-custom">
                            <option value="">-- No Manager --</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}"
                                    {{ old('assigned_manager', $employee->assigned_manager) == $manager->id ? 'selected' : '' }}>
                                    {{ $manager->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Status</label>
                        <select name="is_active" class="form-select-custom">
                            <option value="1" {{ old('is_active', $employee->is_active) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $employee->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="mt-4">
                <a href="{{ route('employee.index') }}" class="btn-back">Cancel</a>
                <button type="submit" class="btn-submit">Update Employee</button>
            </div>

        </form>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
flatpickr(".flatpickr-date", {
    dateFormat: "Y-m-d",
    allowInput: true
});

document.addEventListener('DOMContentLoaded', function () {

    const roleSelect = document.getElementById('roleSelect');
    const managerField = document.getElementById('managerField');

    function toggleManagerField() {
        let selectedRole = roleSelect.value;

        if (selectedRole == 2) {
            managerField.style.display = 'none';
        } else {
            managerField.style.display = 'block';
        }
    }


    toggleManagerField();

    roleSelect.addEventListener('change', toggleManagerField);

});
</script>

@endsection