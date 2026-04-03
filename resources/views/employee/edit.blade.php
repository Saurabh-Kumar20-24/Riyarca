@include('layouts.header')

{{-- Flatpickr CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

{{-- Common Shared CSS --}}
<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@if(session('success'))
    <div class="alert-success-custom">{{ session('success') }}</div>
@endif

<div class="col-md-12">
    <div class="form-card">
        <h3>✏️ Edit Employee — {{ $employee->name }}</h3>

        <form method="POST" action="{{ route('employee.update', $employee->id) }}">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Full Name --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Full Name</label>
                        <input type="text" name="name" class="form-control-custom"
                               value="{{ old('name', $employee->name) }}"
                               placeholder="Jack Sparrow" required>
                        @error('name')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Phone --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Phone</label>
                        <input type="text" name="phone" class="form-control-custom"
                               value="{{ old('phone', $employee->phone) }}"
                               placeholder="+91 9999999999">
                        @error('phone')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Email --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Email Address</label>
                        <input type="email" name="email" class="form-control-custom"
                               value="{{ old('email', $employee->email) }}"
                               placeholder="jack@example.com" required>
                        @error('email')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Password --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">New Password</label>
                        <input type="password" name="password" class="form-control-custom"
                               placeholder="Leave blank to keep current password">
                        <div class="hint">Leave blank to keep the existing password.</div>
                        @error('password')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Date of Birth (Flatpickr) --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Date of Birth</label>
                        <input type="text" id="dob" name="dob" class="form-control-custom flatpickr-date"
                               value="{{ old('dob', $employee->dob) }}"
                               placeholder="YYYY-MM-DD" autocomplete="off">
                        @error('dob')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Joining Date (Flatpickr) --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Joining Date</label>
                        <input type="text" id="joining_date" name="joining_date" class="form-control-custom flatpickr-date"
                               value="{{ old('joining_date', $employee->joining_date) }}"
                               placeholder="YYYY-MM-DD" autocomplete="off">
                        @error('joining_date')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Role --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label-custom">Role</label>
                        <select name="role_id" class="form-select-custom" required>
                            <option value="" disabled>-- Select Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ old('role_id', $employee->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->role_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Assign Manager (hide for admin role) --}}
                @if($employee->role_id != 1)
                <div class="col-md-4">
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
                        @error('assigned_manager')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>
                @endif

                {{-- Status --}}
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

            {{-- Form Actions --}}
            <div class="mt-4">
                <a href="{{ route('employee.index') }}" class="btn-back">Cancel</a>
                <button type="submit" class="btn-submit">Update Employee</button>
            </div>

        </form>
    </div>
</div>

{{-- Flatpickr JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr(".flatpickr-date", {
        dateFormat: "Y-m-d",
        allowInput: true
    });
</script>

@include('layouts.footer')