@extends('layouts.header')

@section('content')

<div class="page-header">
    <h2>Assign Task</h2>
</div>

@if(session('success'))
<div class="alert-success">
    {{ session('success') }}
</div>
@endif

<div class="form-card">
<form method="POST" action="{{ route('task.store') }}">
@csrf

<div class="form-group">
    <label class="form-label-custom">Task Title</label>
    <input type="text"
           name="title"
           class="form-control-custom"
           placeholder="Enter task title"
           required>
</div>

<div class="form-group">
    <label class="form-label-custom">Description</label>
    <textarea name="description"
              class="form-control-custom"
              rows="3"
              placeholder="Enter task description"></textarea>
</div>

<div class="form-group">
    <label class="form-label-custom">Assign To</label>
    <select name="assigned_to"
            class="form-select-custom"
            required>
        <option value="">Select Employee</option>
        @foreach($employees as $emp)
        <option value="{{ $emp->id }}">
            {{ $emp->name }}
        </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label class="form-label-custom">Priority</label>
    <select name="priority"
            class="form-select-custom">
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
    </select>
</div>

<div class="form-group">
    <label class="form-label-custom">Due Date</label>
    <input type="date"
           name="due_date"
           class="form-control-custom">
</div>

<div class="form-actions">
    <button type="submit" class="btn-submit">
        Assign Task
    </button>
</div>

</form>
</div>

@endsection