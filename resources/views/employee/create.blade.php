@extends('layouts.header')

@section('title', 'Create_Employee')
@section('page-title', 'Create_Employee')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">
@section('content')



<div class="col-md-12">
    <div class="form-card">
        <h3>Add New Employee</h3>

        <form method="POST" action="{{ route('employee.store') }}">
            @csrf

            <div class="row">
                {{-- Full Name --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Full Name</label>
                        <input type="text" name="name" class="form-control-custom"
                            value="{{ old('name') }}" placeholder="Jack Sparrow" required>
                        @error('name')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Phone --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Phone</label>
                        <input type="text" name="phone" class="form-control-custom"
                            value="{{ old('phone') }}" placeholder="+91 9999999999">
                        @error('phone')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Email Address</label>
                        <input type="email" name="email" class="form-control-custom"
                            value="{{ old('email') }}" placeholder="jack@example.com" required>
                        @error('email')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Password</label>
                        <input type="password" name="password" class="form-control-custom"
                            placeholder="Enter your password..." required>
                        @error('password')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Date of Birth</label>
                        <input type="text" id="dob" name="dob" class="form-control-custom flatpickr-date"
                            value="{{ old('dob') }}" placeholder="YYYY-MM-DD" autocomplete="off">
                        @error('dob')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Joining Date</label>
                        <input type="text" id="joining_date" name="joining_date" class="form-control-custom flatpickr-date"
                            value="{{ old('joining_date') }}" placeholder="YYYY-MM-DD" autocomplete="off">
                        @error('joining_date')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Role</label>
                        <select name="role_id" id="role_id" class="form-select-custom" required>
                            <option value="" disabled selected>-- Select Role --</option>
                            @foreach($roles as $role)
                            @if($role->role_name != 'admin')
                            <option value="{{ $role->id }}"
                                {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->role_name }}
                            </option>
                            @endif
                            @endforeach
                        </select>
                        @error('role_id')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4" id="manager-box">
                    <div class="form-group">
                        <label class="form-label-custom">Assign Manager</label>
                        <select name="assigned_manager" class="form-select-custom">
                            <option value="">-- No Manager --</option>
                            @foreach($managers as $manager)
                            <option value="{{ $manager->id }}"
                                {{ old('assigned_manager') == $manager->id ? 'selected' : '' }}>
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
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <h3>Bank Details</h3>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control-custom" placeholder="HDFC / SBI">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Account Number</label>
                        <input type="text" name="account_number" class="form-control-custom">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">IFSC Code</label>
                        <input type="text" name="ifsc_code" class="form-control-custom"
                            placeholder="HDFC0001234">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">PAN Number</label>
                        <input type="text" name="pan_number" class="form-control-custom">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">PF Number</label>
                        <input type="text" name="pf_number" class="form-control-custom">
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('employee.index') }}" class="btn-back">Cancel</a>
                <button type="submit" class="btn-submit">Add Employee</button>
            </div>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr(".flatpickr-date", {
        dateFormat: "Y-m-d",
        allowInput: true
    });

    document.addEventListener("DOMContentLoaded", function() {

        const roleSelect = document.getElementById("role_id");
        const managerBox = document.getElementById("manager-box");

        function toggleManagerField() {
            const selectedText = roleSelect.options[roleSelect.selectedIndex].text.toLowerCase();

            if (selectedText === "manager") {
                managerBox.style.display = "none";
            } else {
                managerBox.style.display = "block";
            }
        }

        toggleManagerField();

        roleSelect.addEventListener("change", toggleManagerField);
    });
</script>
@endsection