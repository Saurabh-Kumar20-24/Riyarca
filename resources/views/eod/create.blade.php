@extends('layouts.header')

@section('title', 'EOD Reports')
@section('page-title', 'EOD Reports')



@section('content')
<div class="main-content">

    {{-- ── Page Header ─────────────────────────────────────────── --}}
    <div class="page-header">
        <h2><i class="bi bi-clipboard-check"></i> Submit EOD Report</h2>
        <a href="{{ route('eod.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    {{-- ── Already Submitted Warning ───────────────────────────── --}}
    @if($alreadySubmitted)
        <div class="alert-danger-custom">
            <i class="bi bi-exclamation-circle-fill"></i>
            You have already submitted your EOD report for today.
        </div>
    @else

    {{-- ── Success / Error Messages ────────────────────────────── --}}
    @if(session('success'))
        <div class="alert-success-custom">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-danger-custom">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- ── Employee Info Card ───────────────────────────────────── --}}
    <div class="emp-info-card">
        <div>
            <div class="emp-info-label">Employee</div>
            <div class="emp-info-value">{{ $authUser->name }}</div>
        </div>
        <div>
            <div class="emp-info-label">Date</div>
            <div class="emp-info-value">{{ today()->format('d M Y') }}</div>
        </div>
        <div>
            <div class="emp-info-label">Day</div>
            <div class="emp-info-value">{{ today()->format('l') }}</div>
        </div>
    </div>

    {{-- ── Form Card ────────────────────────────────────────────── --}}
    <div class="form-card">
        <h3>
            <i class="bi bi-list-check"></i>
            Today's Tasks
        </h3>

        <form action="{{ route('eod.store') }}" method="POST">
            @csrf

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert-danger-custom">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── Task Fields ──────────────────────────────────── --}}
            <div id="task-list">
                <div class="task-row">
                    <div class="task-number">1</div>
                    <input
                        type="text"
                        name="tasks_completed[]"
                        class="form-input task-input {{ $errors->has('tasks_completed.0') ? 'input-error' : '' }}"
                        placeholder="Describe task #1..."
                        required
                    />
                </div>
            </div>

            {{-- ── Add Task Button ──────────────────────────────── --}}
            <button type="button" class="btn-add-task" onclick="addTask()">
                <i class="bi bi-plus-circle"></i> Add Another Task
            </button>

            {{-- ── Submit ───────────────────────────────────────── --}}
            <div class="form-footer">
                <div class="form-actions">
                    <a href="{{ route('eod.index') }}" class="btn-back">
                        <i class="bi bi-x"></i> Cancel
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-send-fill"></i> Submit EOD
                    </button>
                </div>
            </div>

        </form>
    </div>

    @endif

</div>

<script>
    let taskCount = 1;

    function addTask() {
        taskCount++;
        const container = document.getElementById('task-list');

        const row = document.createElement('div');
        row.className = 'task-row';

        row.innerHTML = `
            <div class="task-number">${taskCount}</div>
            <input
                type="text"
                name="tasks_completed[]"
                class="form-input task-input"
                placeholder="Describe task #${taskCount}..."
                required
            />
            <button type="button" class="btn-remove-task" onclick="removeTask(this)">
                <i class="bi bi-trash3"></i>
            </button>
        `;

        container.appendChild(row);
    }

    function removeTask(btn) {
        if (document.querySelectorAll('.task-row').length > 1) {
            btn.closest('.task-row').remove();

            // Re-number remaining tasks
            document.querySelectorAll('.task-row').forEach((row, i) => {
                row.querySelector('.task-number').textContent = i + 1;
            });

            taskCount = document.querySelectorAll('.task-row').length;
        }
    }
</script>

@endsection