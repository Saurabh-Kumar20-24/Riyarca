@extends('layouts.header')

@section('title', 'Track Tasks')
@section('page-title', 'Track Tasks')

<link rel="stylesheet" href="{{ asset('assets/css/tableForm.css') }}">

@section('content')

@if(session('success'))
    <div id="successAlert" class="alert-success-custom">
        {{ session('success') }}
    </div>
@endif

<div class="page-header">
    <h2>Track Tasks</h2>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Task</th>
                <th>Employee</th>
                <th>Priority</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>

                <td>{{ $task->assignedTo->name }}</td>

                <td>
                    <span class="badge-inactive">
                        {{ ucfirst($task->priority) }}
                    </span>
                </td>

                <td>{{ $task->due_date }}</td>

                <td>
                    @if($task->status == 'completed')
                        <span class="badge-active">Completed</span>
                    @elseif($task->status == 'in_progress')
                        <span class="badge-inactive">In Progress</span>
                    @else
                        <span class="badge-inactive">Pending</span>
                    @endif
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="5" style="text-align:center; color:#aaa; padding:2rem;">
                    No tasks found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
setTimeout(function () {
    let alert = document.getElementById('successAlert');
    if (alert) alert.style.display = 'none';
}, 3000);
</script>

@endsection