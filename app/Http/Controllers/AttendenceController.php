<?php

namespace App\Http\Controllers;

use App\Models\Attendence;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendenceController extends Controller
{

    public function index(Request $request)
    {
        $authUser = Auth::user();
        $authUser->load('role');

        $query = User::with('role', 'manager', 'attendence');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $employees = $query->paginate(10)->withQueryString();

        $roles = Role::all();

        return view('attendence.index', compact('employees', 'roles'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            // 'user_id'         => 'required|string',
            'employee_id'     => 'required|string|exists:users,employee_id',
            'attendance_date' => 'required|date',
            'check_in'        => 'required',
            'check_out'       => 'nullable|after:check_in',
        ]);

        $employee = User::where('employee_id', $request->employee_id)
            ->where('is_active', 1)
            ->first();

        if (!$employee) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'No employee found with ID: ' . $request->user_id,
            ], 404);
        }

        $existing = Attendence::where('user_id', $employee->id)
            ->whereDate('attendance_date', $request->attendance_date)
            ->first();

        if ($existing) {
            return response()->json([
                'status'  => 'duplicate',
                'message' => 'Attendance for ' . $employee->name . ' on ' . $request->attendance_date . ' already exists.',
            ], 409);
        }

        $checkInTime = Carbon::parse($request->check_in);
        $lateThreshold = Carbon::parse('10:05:00');
        $lateStatus    = $checkInTime->gt($lateThreshold) ? 'Late' : 'On Time';

        if ($request->check_out) {
            $checkOut     = Carbon::parse($request->check_out);
            $lunchStart   = Carbon::parse('13:00:00');
            $eveningCutoff = Carbon::parse('17:45:00');
            $fourHours    = 4 * 60;

            if ($checkInTime->lte(Carbon::parse('10:05:00'))) {
                if ($checkOut->gte($eveningCutoff)) {
                    $status = 'present';
                } elseif ($checkOut->gte($lunchStart)) {
                    $status = 'half_day';
                } else {
                    $status = 'absent';
                }
            } elseif ($checkInTime->gte($lunchStart) && $checkInTime->lte(Carbon::parse('13:30:00'))) {
                if ($checkOut->gte($eveningCutoff)) {
                    $status = 'half_day';
                } else {
                    $status = 'absent';
                }
            } else {
                $totalMinutes = $checkInTime->diffInMinutes($checkOut);
                $status = $totalMinutes >= ($fourHours) ? 'half_day' : 'absent';
            }
        } else {
            $status = 'pending'; 
        }

        Attendence::create([
            'user_id'         => $employee->id,
            'employee_id'     => $employee->employee_id,
            'attendance_date' => $request->attendance_date,
            'check_in'        => $request->check_in,
            'check_out'       => $request->check_out,
            'late_status'     => $lateStatus,
            'status'          => $status,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Attendance for ' . $employee->name . ' added successfully.',
        ], 201);
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }

    public function ShowAttendence(Request $request)
    {

        $id = $request->emp_id;
        //  dd($empId);
        $employees = User::findOrFail($id);
        $attendances = Attendence::where('user_id', $id)
            ->orderBy('attendance_date', 'desc')
            ->paginate(15);

        // dd($attendances);

        return view('attendence.ShowAttendence', compact('employees', 'attendances'));
    }

    // NFC Write Page 
    public function nfcWrite()
    {
        if (Auth::user()->role_id != 1) {
            abort(403, 'Unauthorized');
        }

        $employees = User::where('is_active', 1)
            ->where('id', '!=', 1)
            ->whereNotNull('employee_id')
            ->get();

        return view('attendence.nfc-write', compact('employees'));
    }

    // NFC Scanner Page
    public function nfcScanner()
    {
        return view('attendence.nfc-scanner');
    }

    // ── NFC Scan — Called by JS on card tap 
    public function nfcScan(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
        ]);

        // Find employee by employee_id
        $employee = User::where('employee_id', $request->employee_id)
            ->where('is_active', 1)
            ->first();

        // Not found
        if (!$employee) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'No employee found with this card.',
            ], 404);
        }

        // Check today's record
        $todayLog = Attendence::where('user_id', $employee->id)
            ->whereDate('attendance_date', today())
            ->first();

        if (!$todayLog) {
            // ── First tap = Check In
            Attendence::create([
                'user_id'         => $employee->id,
                'attendance_date' => today(),
                'check_in'        => now()->format('H:i:s'),
                'status'          => 'present',
            ]);
            $type = 'check_in';
        } elseif (!$todayLog->check_out) {
            // ── Second tap = Check Out 
            $checkIn    = Carbon::parse($todayLog->check_in);
            $checkOut   = Carbon::now();
            $totalHours = round($checkIn->diffInMinutes($checkOut) / 60, 2);

            $todayLog->update([
                'check_out'   => $checkOut->format('H:i:s'),
                'total_hours' => $totalHours,
            ]);
            $type = 'check_out';
        } else {
            // ── Already done both
            return response()->json([
                'status'  => 'already_done',
                'message' => $employee->name . ' has already completed attendance today.',
            ]);
        }

        return response()->json([
            'status'   => 'success',
            'type'     => $type,
            'employee' => [
                'name'        => $employee->name,
                'employee_id' => $employee->employee_id,
                'role'        => $employee->role->role_name ?? '-',
            ],
            'time' => now()->format('h:i A'),
            'date' => now()->format('d M Y'),
        ]);
    }
}
