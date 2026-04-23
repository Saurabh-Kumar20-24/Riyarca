@extends('layouts.header')

@section('title', 'My Tasks')
@section('page-title', 'My Tasks')

<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@section('content')

@if(session('success'))
<div id="successAlert" class="alert-success-custom">
    {{ session('success') }}
</div>
@endif

<div class="page-header">
    <h2>My Tasks</h2>
</div>

<div class="table-card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>

                    <th>Task</th>
                    <th>Assigned By</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>
            </thead>

            <tbody>
                @forelse($tasks as $task)
                <tr>

                    <td>{{ $task->title }}</td>

                    <td>{{ $task->assignedBy->name }}</td>

                    <td>{{ $task->due_date }}</td>

                    <td>
                        <span class="badge-active">
                            {{ ucfirst(str_replace('_',' ',$task->status)) }}
                        </span>
                    </td>

                    <td>
                        <form method="POST" action="{{ route('task.status',$task->id) }}">
                            @csrf

                            <div class="d-flex gap-2 align-items-center">
                                <div class="custom-select-wrapper">
                                    <select name="status" class="custom-select">
                                        <option value="pending" {{ $task->status=='pending'?'selected':'' }}>
                                            Pending
                                        </option>

                                        <option value="in_progress" {{ $task->status=='in_progress'?'selected':'' }}>
                                            In Progress
                                        </option>

                                        <option value="completed" {{ $task->status=='completed'?'selected':'' }}>
                                            Completed
                                        </option>
                                    </select>
                                </div>

                                <button type="submit" class="btn-search">
                                    Update
                                </button>
                            </div>

                        </form>
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:#aaa; padding:2rem;">
                        No tasks assigned.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    setTimeout(function() {
        let alert = document.getElementById('successAlert');
        if (alert) alert.style.display = 'none';
    }, 3000);
</script>

@endsection