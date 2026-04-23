@extends('layouts.header')

@section('title', 'Edit Policy')
@section('page-title', 'Edit Policy')

<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@section('content')

<div class="page-header">
    <h2>Edit Policy</h2>
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

    <form method="POST" action="{{ route('policies.update', $policy->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group" style="margin-bottom:20px;">
            <label style="font-weight:600; display:block; margin-bottom:6px;">
                Policy Title <span style="color:red;">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title', $policy->title) }}"
                class="search-input" style="width:100%;"
                placeholder="Enter policy title">
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="font-weight:600; display:block; margin-bottom:6px;">
                Assign to Role <span style="color:red;">*</span>
            </label>
            <select name="role_id" class="filter-select" style="width:100%;">
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}"
                    {{ old('role_id', $policy->role_id) == $role->id ? 'selected' : '' }}>
                    {{ $role->role_name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="font-weight:600; display:block; margin-bottom:6px;">
                Version <span style="color:red;">*</span>
            </label>
            <input type="text" name="version" value="{{ old('version', $policy->version) }}"
                class="search-input" style="width:200px;"
                placeholder="e.g. 1.0">
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="font-weight:600; display:block; margin-bottom:10px;">
                Policy Points <span style="color:red;">*</span>
                <span style="font-size:12px; font-weight:400; color:#888;">
                    — Add each policy rule as a separate point
                </span>
            </label>

            <div id="pointsContainer">
                @php
                $points = json_decode($policy->content, true);
                if (!is_array($points)) {
                $points = [$policy->content]; // fallback if old plain text
                }
                @endphp

                @foreach($points as $i => $point)
                <div class="point-row" style="display:flex; align-items:flex-start; gap:10px; margin-bottom:12px;">
                    <span style="margin-top:10px; color:#2c3e50; font-weight:700; min-width:24px;">{{ $i + 1 }}.</span>
                    <textarea name="points[]" rows="2" placeholder="Enter policy point..."
                        style="flex:1; padding:10px 14px; border:1px solid #d1d5db;
                             border-radius:8px; font-size:14px; line-height:1.6;
                             resize:vertical; font-family:inherit;">{{ $point }}</textarea>
                    <button type="button" onclick="removePoint(this)"
                        style="margin-top:6px; background:#fee2e2; border:none; color:#dc2626;
                           border-radius:6px; padding:6px 10px; cursor:pointer; font-size:16px;">
                        ✕
                    </button>
                </div>
                @endforeach
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
                    {{ old('is_active', $policy->is_active) ? 'checked' : '' }}
                    style="width:16px; height:16px;">
                <span style="font-weight:600;">Set as Active</span>
            </label>
        </div>

        <div style="display:flex; gap:12px;">
            <button type="submit" class="btn-add">Update Policy</button>
            <a href="{{ route('policies.index') }}" class="btn-cancel-modal"
                style="padding:8px 20px; border-radius:6px; text-decoration:none;">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection