<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $today = now()->toDateString();

        // Currently on leave
        $onLeave = LeaveRequest::with('user.role')
            ->where('status', 'approved')
            ->whereDate('from_date', '<=', $today)
            ->whereDate('to_date',   '>=', $today)
            ->get();

        // Pending requests
        $pending = LeaveRequest::with('user.role')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Upcoming approved leaves
        $upcoming = LeaveRequest::with('user.role')
            ->where('status', 'approved')
            ->whereDate('from_date', '>', $today)
            ->orderBy('from_date', 'asc')
            ->get();

        // All requests with filter
        $query = LeaveRequest::with('user.role');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('from_date', [$request->from, $request->to]);
        }

         $allLeaves = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('leave.index', compact('onLeave', 'pending', 'upcoming', 'allLeaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
