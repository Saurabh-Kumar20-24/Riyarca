<?php

namespace App\Http\Controllers;

use App\Models\LeaveBalance;
use App\Models\LeaveLog;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX — Admin sees all; employee sees own + summaries
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $today = now()->toDateString();

        // Employees currently on leave (approved, date range covers today)
        $onLeave = LeaveRequest::with('user')
            ->where('status', 'approved')
            ->whereDate('from_date', '<=', $today)
            ->whereDate('to_date', '>=', $today)
            ->get();

        // All pending requests
        $pending = LeaveRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Upcoming approved leaves
        $upcoming = LeaveRequest::with('user')
            ->where('status', 'approved')
            ->whereDate('from_date', '>', $today)
            ->orderBy('from_date')
            ->get();

        // Filterable / paginated full list
        $query = LeaveRequest::with('user', 'reviewer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $allLeaves = $query->latest()->paginate(10)->withQueryString();

        return view('leave.index', compact('onLeave', 'pending', 'upcoming', 'allLeaves'));
    }

   

    public function myLeaves()
    {
        $userId = Auth::id();
        $year   = now()->year;

        $myLeaves = LeaveRequest::where('user_id', $userId)
            ->latest()
            ->paginate(10);

        $leaveTypes = ['casual', 'sick', 'earned', 'optional', 'emergency', 'unpaid'];

        $balances = collect($leaveTypes)->mapWithKeys(function ($type) use ($userId, $year) {
            return [$type => LeaveBalance::getOrCreate($userId, $type, $year)];
        });

        return view('leave.my_leaves', compact('myLeaves', 'balances'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|in:casual,sick,earned,optional,emergency,unpaid,half_day',
            'from_date'  => 'required|date',
            'to_date'    => 'required|date|after_or_equal:from_date',
            'reason'     => 'required|string|max:1000',
        ]);

        $days = $this->calculateDays($request->from_date, $request->to_date, $request->duration);

        $error = $this->validateLeaveRules($request, $days);
        if ($error) {
            return back()->with('error', $error)->withInput();
        }

        if ($request->leave_type !== 'unpaid') {
            $balance = LeaveBalance::getOrCreate(Auth::id(), $request->leave_type, now()->year);

            if ($balance->remaining < $days) {
                return back()
                    ->with('error', "Insufficient leave balance. You have {$balance->remaining} day(s) remaining for " . ucfirst($request->leave_type) . " leave.")
                    ->withInput();
            }
        }

        $document = null;
        if ($request->hasFile('document')) {
            $document = $request->file('document')->store('leave_docs', 'public');
        }

        DB::transaction(function () use ($request, $days, $document) {

            $leave = LeaveRequest::create([
                'user_id'       => Auth::id(),
                'leave_type'    => $request->leave_type,
                'from_date'     => $request->from_date,
                'to_date'       => $request->to_date,
                'total_days'    => $days,
                'duration'      => $request->duration ?? 'full',
                'half_day_type' => $request->half_day_type,
                'reason'        => $request->reason,
                'handover'      => $request->handover,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'document'      => $document,
                'status'        => 'pending',
            ]);

            if ($request->leave_type !== 'unpaid') {
                LeaveBalance::getOrCreate(Auth::id(), $request->leave_type)
                    ->increment('pending', $days);
            }

            LeaveLog::create([
                'leave_request_id' => $leave->id,
                'user_id'          => Auth::id(),
                'actor_id'         => Auth::id(),
                'action'           => 'applied',
                'leave_type'       => $leave->leave_type,
                'days'             => $days,
            ]);
        });

        return back()->with('success', 'Leave request submitted successfully.');
    }

  

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'note'   => 'nullable|string|max:500',
        ]);

        $leave = LeaveRequest::with('user')->findOrFail($id);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'This leave request has already been reviewed.');
        }

        DB::transaction(function () use ($request, $leave) {

            $leave->status      = $request->status;
            $leave->reviewed_by = Auth::id();
            $leave->reviewed_at = now();
            $leave->save();

            if ($leave->leave_type !== 'unpaid') {

                $balance = LeaveBalance::getOrCreate(
                    $leave->user_id,
                    $leave->leave_type,
                    now()->year
                );

                $balance->decrement('pending', $leave->total_days);

                if ($request->status === 'approved') {
                    $balance->decrement('remaining', $leave->total_days);
                    $balance->increment('used', $leave->total_days);
                }
            }

            LeaveLog::create([
                'leave_request_id' => $leave->id,
                'user_id'          => $leave->user_id,
                'actor_id'         => Auth::id(),
                'action'           => $request->status,   // 'approved' or 'rejected'
                'leave_type'       => $leave->leave_type,
                'days'             => $leave->total_days,
                'note'             => $request->note,
            ]);
        });

        $label = $request->status === 'approved' ? 'approved' : 'rejected';

        return back()->with('success', "Leave request {$label} successfully.");
    }

  

    public function cancel($id)
    {
        $leave = LeaveRequest::where('user_id', Auth::id())->findOrFail($id);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Only pending requests can be cancelled.');
        }

        DB::transaction(function () use ($leave) {

            $leave->status = 'cancelled';
            $leave->save();

            if ($leave->leave_type !== 'unpaid') {
                LeaveBalance::getOrCreate(
                    $leave->user_id,
                    $leave->leave_type,
                    now()->year
                )
                    ->decrement('pending', $leave->total_days);
            }

            LeaveLog::create([
                'leave_request_id' => $leave->id,
                'user_id'          => $leave->user_id,
                'actor_id'         => Auth::id(),
                'action'           => 'cancelled',
                'leave_type'       => $leave->leave_type,
                'days'             => $leave->total_days,
            ]);
        });

        return back()->with('success', 'Leave request cancelled.');
    }

   
    private function calculateDays(string $from, string $to, ?string $duration): float
    {
        if ($duration === 'half') {
            return 0.5;
        }

        return (float) (Carbon::parse($from)->diffInDays(Carbon::parse($to)) + 1);
    }

   
    private function validateLeaveRules(Request $request, float $days): ?string
    {
        $type = $request->leave_type;
        $from = Carbon::parse($request->from_date);
        $noticeDays = now()->startOfDay()->diffInDays($from->startOfDay(), false);

        if ($type === 'casual') {
            if ($days > 2) {
                return 'Casual leave cannot exceed 2 days.';
            }
            if ($noticeDays < 1) {
                return 'Casual leave requires at least 1 day advance notice.';
            }
        }

        if ($type === 'sick') {
            if ($days > 2 && ! $request->hasFile('document')) {
                return 'A medical document is required for sick leave exceeding 2 days.';
            }
        }

        if ($type === 'earned') {
            if ($noticeDays < 3) {
                return 'Earned leave requires at least 3 days advance notice.';
            }
        }

        if ($type === 'half_day') {
            $request->merge(['duration' => 'half']);
        }

        return null;
    }
}
