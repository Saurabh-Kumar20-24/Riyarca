<?php

namespace App\Http\Controllers;

use App\Models\EmployeeNotification;
use App\Models\LeaveBalance;
use App\Models\LeaveLog;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeaveController extends Controller
{


    public function index(Request $request)
    {
        $today = now()->toDateString();

        $onLeave = LeaveRequest::with('user')
            ->where('status', 'approved')
            ->whereDate('from_date', '<=', $today)
            ->whereDate('to_date', '>=', $today)
            ->get();

        $pending = LeaveRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $upcoming = LeaveRequest::with('user')
            ->where('status', 'approved')
            ->whereDate('from_date', '>', $today)
            ->orderBy('from_date')
            ->get();

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

      
        $type = $request->leave_type;
        if ($type === 'half_day') {
            $type = 'casual';
            $request->merge(['duration' => 'half']); 
        }

        $days = $this->calculateDays($request->from_date, $request->to_date, $request->duration);

        $error = $this->validateLeaveRules($request, $days);
        if ($error) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $error
                ]);
            }

            return back()->with('error', $error)->withInput();
        }

        if ($type !== 'unpaid') {
            $balance = LeaveBalance::getOrCreate(Auth::id(), $type, now()->year);

            if ($balance->remaining < $days) {
                $msg = "Insufficient leave balance. You have {$balance->remaining} day(s) remaining for " . ucfirst($type) . " leave.";
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $msg
                    ]);
                }
                return back()->with('error', $msg)->withInput();
            }
        }

        $document = null;
        if ($request->hasFile('document')) {
            $document = $request->file('document')->store('leave_docs', 'public');
        }

        DB::transaction(function () use ($request, $days, $document, $type) {

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

            $employee = Auth::user();
            $from     = \Carbon\Carbon::parse($request->from_date)->format('d M');
            $to       = \Carbon\Carbon::parse($request->to_date)->format('d M');

            EmployeeNotification::broadcast(
                type: 'leave',
                title: '🗓️ Leave Applied',
                message: "{$employee->name} has applied for " . ucfirst($request->leave_type) . " leave from {$from} to {$to} ({$days} day/s).",
                triggeredBy: $employee->id,
                meta: [
                    'from'       => $request->from_date,
                    'to'         => $request->to_date,
                    'leave_type' => $request->leave_type,
                    'days'       => $days,
                ],
            );

            if ($type !== 'unpaid') {
                LeaveBalance::getOrCreate(Auth::id(), $type)
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

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Leave request submitted successfully.'
            ]);
        }

        return back()->with('success', 'Leave request submitted successfully.');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'leave_type' => 'required|in:casual,sick,earned,optional,emergency,unpaid,half_day',
    //         'from_date'  => 'required|date',
    //         'to_date'    => 'required|date|after_or_equal:from_date',
    //         'reason'     => 'required|string|max:1000',
    //     ]);

    //     $type = $request->leave_type;
    //     if ($type === 'half_day') {
    //         $type = 'casual';
    //     }

    //     $days = $this->calculateDays($request->from_date, $request->to_date, $request->duration);

    //     $error = $this->validateLeaveRules($request, $days);
    //     if ($error) {
    //         if ($request->ajax()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => $error
    //             ]);
    //         }

    //         return back()->with('error', $error)->withInput();
    //     }

    //     if ($request->leave_type !== 'unpaid') {
    //         // $balance = LeaveBalance::getOrCreate(Auth::id(), $request->leave_type, now()->year);
    //         $balance = LeaveBalance::getOrCreate(Auth::id(), $type, now()->year);

    //         if ($balance->remaining < $days) {
    //             $msg = "Insufficient leave balance. You have {$balance->remaining} day(s) remaining for " . ucfirst($request->leave_type) . " leave.";
    //             if ($request->ajax()) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => $msg
    //                 ]);
    //             }
    //             return back()->with('error', $msg)->withInput();
    //         }
    //     }

    //     $document = null;
    //     if ($request->hasFile('document')) {
    //         $document = $request->file('document')->store('leave_docs', 'public');
    //     }

    //     DB::transaction(function () use ($request, $days, $document) {

    //         $leave = LeaveRequest::create([
    //             'user_id'       => Auth::id(),
    //             'leave_type'    => $request->leave_type,
    //             'from_date'     => $request->from_date,
    //             'to_date'       => $request->to_date,
    //             'total_days'    => $days,
    //             'duration'      => $request->duration ?? 'full',
    //             'half_day_type' => $request->half_day_type,
    //             'reason'        => $request->reason,
    //             'handover'      => $request->handover,
    //             'contact_email' => $request->contact_email,
    //             'contact_phone' => $request->contact_phone,
    //             'document'      => $document,
    //             'status'        => 'pending',
    //         ]);


    //         // fire leave notification
    //         $employee = Auth::user();
    //         $from     = \Carbon\Carbon::parse($request->from_date)->format('d M');
    //         $to       = \Carbon\Carbon::parse($request->to_date)->format('d M');

    //         EmployeeNotification::broadcast(
    //             type: 'leave',
    //             title: '🗓️ Leave Applied',
    //             message: "{$employee->name} has applied for " . ucfirst($request->leave_type) . " leave from {$from} to {$to} ({$days} day/s).",
    //             triggeredBy: $employee->id,
    //             meta: [
    //                 'from'       => $request->from_date,
    //                 'to'         => $request->to_date,
    //                 'leave_type' => $request->leave_type,
    //                 'days'       => $days,
    //             ],
    //         );

    //         if ($request->leave_type !== 'unpaid') {
    //             LeaveBalance::getOrCreate(Auth::id(), $request->leave_type)
    //                 ->increment('pending', $days);
    //         }

    //         LeaveLog::create([
    //             'leave_request_id' => $leave->id,
    //             'user_id'          => Auth::id(),
    //             'actor_id'         => Auth::id(),
    //             'action'           => 'applied',
    //             'leave_type'       => $leave->leave_type,
    //             'days'             => $days,
    //         ]);
    //     });

    //     if ($request->ajax()) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Leave request submitted successfully.'
    //         ]);
    //     }

    //     return back()->with('success', 'Leave request submitted successfully.');
    // }



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
                'action'           => $request->status,   
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


    public function exportCsv(Request $request): StreamedResponse
    {
        $query = LeaveRequest::with('user', 'reviewer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaves = $query->latest()->get();

        $filename = 'leave_requests_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($leaves) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                '#',
                'Employee',
                'Leave Type',
                'From',
                'To',
                'Days',
                'Duration',
                'Status',
                'Reason',
                'Contact Email',
                'Contact Phone',
                'Reviewed By',
                'Reviewed At',
            ]);

            foreach ($leaves as $i => $leave) {
                fputcsv($handle, [
                    $i + 1,
                    $leave->user->name ?? '-',
                    ucfirst($leave->leave_type),
                    $leave->from_date->format('d M Y'),
                    $leave->to_date->format('d M Y'),
                    $leave->total_days,
                    ucfirst($leave->duration ?? 'Full'),
                    ucfirst($leave->status),
                    $leave->reason,
                    $leave->contact_email ?? '-',
                    $leave->contact_phone ?? '-',
                    $leave->reviewer->name ?? '-',
                    $leave->reviewed_at ? $leave->reviewed_at->format('d M Y H:i') : '-',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $query = LeaveRequest::with('user', 'reviewer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaves   = $query->latest()->get();
        $status   = $request->status ?? 'all';
        $exported = now()->format('d M Y, H:i');

        $html = view('leave.export_pdf', compact('leaves', 'status', 'exported'))->render();

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'landscape');

        $filename = 'leave_requests_' . now()->format('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }
}
