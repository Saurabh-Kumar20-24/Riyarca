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

        if($authUser->role_id== 1 || $authUser->role_id== 9){
            $employees = User::with('role','manager')->where('id','!=',1)->where('is_active',1);
        }else{
            $employees = User::with('role','manager')->where('assigned_manager',$authUser->id)->where('is_active',1);
        }
        //search
            if ($request->search) {
                $employees->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
                });
            }

        $employees = $employees->get();
        $roles = Role::all();
        return view('attendence.index',compact('employees', 'roles'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
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

    // ── NFC Scan — Called by JS on card tap ────────────────────
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
            // ── First tap = Check In ───────────────────────────
            Attendence::create([
                'user_id'         => $employee->id,
                'attendance_date' => today(),
                'check_in'        => now()->format('H:i:s'),
                'status'          => 'present',
            ]);
            $type = 'check_in';
         } elseif (!$todayLog->check_out) {
            // ── Second tap = Check Out ─────────────────────────
            $checkIn    = Carbon::parse($todayLog->check_in);
            $checkOut   = Carbon::now();
            $totalHours = round($checkIn->diffInMinutes($checkOut) / 60, 2);

            $todayLog->update([
                'check_out'   => $checkOut->format('H:i:s'),
                'total_hours' => $totalHours,
            ]);
            $type = 'check_out';
        } else {
            // ── Already done both ──────────────────────────────
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
