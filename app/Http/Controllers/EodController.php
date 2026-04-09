<?php

namespace App\Http\Controllers;

use App\Models\EOD;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $authUser = Auth::user();

        // Get employees based on role
        if ($authUser->role_id == 1 || $authUser->role_id == 9) {
            $employees = User::with('role')
                             ->where('id', '!=', 1)
                             ->where('is_active', 1);
        } else {
            $employees = User::with('role')
                             ->where('assigned_manager', $authUser->id)
                             ->where('is_active', 1);
        }

        $employees = $employees->get();

        // Attach LAST EOD to each employee
        $employees->each(function ($employee) {
            $employee->lastEod = EOD::where('user_id', $employee->id)
                                    ->orderBy('report_date', 'desc')
                                    ->first();

            // Attach all EOD to each employee
            $employee->allEods = EOD::where('user_id', $employee->id)
                                ->orderBy('report_date', 'desc')
                                ->get();
        });
        

        return view('eod.index', compact('employees'));
    }

    // Previous — show all EODs of one employee
    public function previousEods(Request $request)
    {

        $id = $request->emp_id;
        $employee = User::findOrFail($id);

        $query = EOD::where('user_id', $id);

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('report_date', [$request->from, $request->to]);
        }


        //for the filter
         $eods = $query->orderBy('report_date', 'desc')->paginate(10);

        return view('eod.previousEod', compact('employee', 'eods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         // Only logged in user can create EOD
        $authUser = Auth::user();

        // Check if already submitted today
        $alreadySubmitted = EOD::where('user_id', $authUser->id)
                            ->whereDate('report_date', today())
                            ->exists();

        return view('eod.create', compact('authUser', 'alreadySubmitted'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $authUser = Auth::user();

        //  Validate 
        $request->validate([
            'tasks_completed'   => 'required|array|min:1',
            'tasks_completed.*' => 'required|string|max:500',
        ]);

        // Block if already submitted today 
        $alreadySubmitted = EOD::where('user_id', $authUser->id)
                            ->whereDate('report_date', today())
                            ->exists();

        if ($alreadySubmitted) {
            return redirect()->back()->with('error', 'You have already submitted your EOD report for today.');
        }

        // Store EOD 
        EOD::create([
            'user_id'         => $authUser->id,
            'report_date'     => today(),
            'tasks_completed' => $request->tasks_completed,
        ]);

        return redirect()->route('eod.index')->with('success', 'EOD report submitted successfully.');
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
