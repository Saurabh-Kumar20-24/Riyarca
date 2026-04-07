<?php

namespace App\Http\Controllers;

use App\Models\JobSeeker;
use Illuminate\Http\Request;

class HiringController extends Controller
{
    public function accepted(Request $request)
    {
        $query = JobSeeker::with('position')
            ->where('status', 'accepted');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('applied_date', [$request->from, $request->to]);
        }

        $jobSeekers = $query->orderBy('applied_date', 'desc')->paginate(10);
        // dd($jobSeekers);

        return view('hiring.accepted', compact('jobSeekers'));
    }

    public function rejected(Request $request)
    {
        $query = JobSeeker::with('position')
            ->where('status', 'rejected');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('applied_date', [$request->from, $request->to]);
        }

        $jobSeekers = $query->orderBy('applied_date', 'desc')->paginate(10);

        return view('hiring.rejected', compact('jobSeekers'));
    }
}