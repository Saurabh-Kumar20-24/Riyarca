@extends('layouts.header')

@section('title', 'Add Policy')
@section('page-title', 'Add Policy')

<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@section('content')

<div class="page-header">
    <h2>Add New Policy</h2>
    <div class="header-actions">
        <a href="{{ route('policies.index') }}" class="btn-cancel-modal"
            style="padding:8px 20px; border-radius:6px; text-decoration:none;">
            ← Back
        </a>
    </div>
</div>

<div class="table-card" style="padding: 28px;">
    @if($errors->any())
    <div class="alert-error-custom" style="margin-bottom:16px;">
        <ul style="margin:0; padding-left:18px;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('policies.store') }}" id="policyForm">
        @csrf

        <div class="row">


            <div class="form-group col-md-4" style="margin-bottom:20px;">
                <label style="font-weight:600; display:block; margin-bottom:6px;">
                    Policy Title <span style="color:red;">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}"
                    class="search-input" style="width:100%;"
                    placeholder="Enter policy title">
            </div>

            <div class="col-md-4 form-group" style="margin-bottom:20px;">
                <label style="font-weight:600; display:block; margin-bottom:6px;">
                    Assign to Role <span style="color:red;">*</span>
                </label>
                <select name="role_id" class="filter-select" style="width:100%;">
                    <option value="">-- Select Role --</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->role_name }}
                    </option>
                    @endforeach
                </select>
            </div>


            <div class="col-md-4 form-group" style="margin-bottom:20px;">
                <label style="font-weight:600; display:block; margin-bottom:6px;">
                    Version <span style="color:red;">*</span>
                </label>
                <input type="text" name="version" value="{{ old('version', '1.0') }}"
                    class="search-input" style="width:200px;"
                    placeholder="e.g. 1.0">
            </div>
        </div>
        <div class="form-group" style="margin-bottom:20px;">
            <label style="font-weight:600; display:block; margin-bottom:10px;">
                Policy Points <span style="color:red;">*</span>
                <span style="font-size:12px; font-weight:400; color:#888;">
                    — Add each policy rule as a separate point
                </span>
            </label>

            <div id="pointsContainer">
                <div class="point-row" style="display:flex; align-items:flex-start; gap:10px; margin-bottom:12px;">
                    <span style="margin-top:10px; color:#2c3e50; font-weight:700; min-width:24px;">1.</span>
                    <textarea name="points[]" rows="1" placeholder="Enter policy point..."
                        style="flex:1; padding: 14px;  border:1px solid #d1d5db;
                                     border-radius:8px; font-size:14px; line-height:1.6;
                                     resize:vertical; font-family:inherit;">{{ old('points.0') }}</textarea>
                    <button type="button" onclick="removePoint(this)"
                        style="margin-top:6px; background:#fee2e2; border:none; color:#dc2626;
                                   border-radius:6px; padding:6px 10px; cursor:pointer; font-size:16px;">
                        ✕
                    </button>
                </div>
            </div>

            <button type="button" onclick="addPoint()"
                style="margin-top:4px; background:#f0f4ff; border:1px dashed #93c5fd;
                           color:#2c3e50; border-radius:8px; padding:9px 20px;
                           cursor:pointer; font-size:14px; font-weight:600;">
                + Add Another Point
            </button>
        </div>

        <input type="hidden" name="content" id="contentInput">

        <div class="form-group" style="margin-bottom:24px;">
            <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                <input type="checkbox" name="is_active" value="1"
                    {{ old('is_active', '1') ? 'checked' : '' }}
                    style="width:16px; height:16px;">
                <span style="font-weight:600;">Set as Active</span>
            </label>
        </div>

        <div style="display:flex; gap:12px;">
            <button type="submit" class="btn-add" onclick="prepareContent()">Save Policy</button>
            <a href="{{ route('policies.index') }}" class="btn-cancel-modal"
                style="padding:8px 20px; border-radius:6px; text-decoration:none;">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    let pointCount = 1;

    function addPoint() {
        pointCount++;
        const container = document.getElementById('pointsContainer');
        const div = document.createElement('div');
        div.className = 'point-row';
        div.style = 'display:flex; align-items:flex-start; gap:10px; margin-bottom:12px;';
        div.innerHTML = `
            <span style="margin-top:10px; color:#2c3e50; font-weight:700; min-width:24px;">${pointCount}.</span>
            <textarea name="points[]" rows="2" placeholder="Enter policy point..."
                      style="flex:1; padding:10px 14px; border:1px solid #d1d5db;
                             border-radius:8px; font-size:14px; line-height:1.6;
                             resize:vertical; font-family:inherit;"></textarea>
            <button type="button" onclick="removePoint(this)"
                    style="margin-top:6px; background:#fee2e2; border:none; color:#dc2626;
                           border-radius:6px; padding:6px 10px; cursor:pointer; font-size:16px;">
                ✕
            </button>
        `;
        container.appendChild(div);
        renumberPoints();
    }

    function removePoint(btn) {
        const rows = document.querySelectorAll('.point-row');
        if (rows.length === 1) {
            alert('At least one policy point is required.');
            return;
        }
        btn.closest('.point-row').remove();
        renumberPoints();
    }

    function renumberPoints() {
        const rows = document.querySelectorAll('.point-row');
        rows.forEach((row, i) => {
            row.querySelector('span').textContent = (i + 1) + '.';
        });
        pointCount = rows.length;
    }

    function prepareContent() {
        const textareas = document.querySelectorAll('textarea[name="points[]"]');
        const points = [];
        textareas.forEach(ta => {
            const val = ta.value.trim();
            if (val) points.push(val);
        });
        document.getElementById('contentInput').value = JSON.stringify(points);
    }

    document.getElementById('policyForm').addEventListener('submit', function(e) {
        const textareas = document.querySelectorAll('textarea[name="points[]"]');
        const points = [];
        textareas.forEach(ta => {
            const val = ta.value.trim();
            if (val) points.push(val);
        });

        if (points.length === 0) {
            e.preventDefault();
            alert('Please add at least one policy point.');
            return;
        }

        document.getElementById('contentInput').value = JSON.stringify(points);
    });
</script>

@endsection