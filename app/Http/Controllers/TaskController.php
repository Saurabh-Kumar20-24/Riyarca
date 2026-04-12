<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function create()
    {
        $employees = User::where('assigned_manager', Auth::id())->get();
        return view('tasks.create', compact('employees'));
    }

    public function store(Request $request)
    {
        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_by' => Auth::id(),
            'assigned_to' => $request->assigned_to,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'status' => 'pending'
        ]);

        return back()->with('success','Task assigned successfully');
    }

    public function myTasks()
    {
        $tasks = Task::where('assigned_to', Auth::id())
            ->with('assignedBy')
            ->latest()
            ->get();

        return view('tasks.my_tasks', compact('tasks'));
    }

    public function track()
    {
        $tasks = Task::where('assigned_by', Auth::id())
            ->with('assignedTo')
            ->latest()
            ->get();

        return view('tasks.track', compact('tasks'));
    }

    public function updateStatus(Request $request,$id)
    {
        $task = Task::findOrFail($id);

        $task->status = $request->status;
        $task->save();

        return back()->with('success','Status updated');
    }
}