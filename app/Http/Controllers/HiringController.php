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

    public function newApplication(Request $request){

         $query = JobSeeker::with('position')
        ->where('status', 'pending'); // latest pending applications

        // search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // date filter
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('applied_date', [$request->from, $request->to]);
        }

        // latest first
        $jobSeekers = $query->orderBy('applied_date', 'desc')->paginate(10);

        return view('hiring.newApplication', compact('jobSeekers'));
    }

    public function updateStatus(Request $request, $id)
    {
        $job = JobSeeker::findOrFail($id);
        $job->status = $request->status;
        $job->save();

        return back()->with('success', 'Status updated successfully');
    }

    public function updateStage(Request $request, $id)
    {
        $request->validate([
            'stage' => 'required|in:applied,screening,interview,decision'
        ]);

        $job = JobSeeker::findOrFail($id);
        $job->stage = $request->stage;
        $job->save();

        return back()->with('success', 'Stage updated successfully');
    }
}