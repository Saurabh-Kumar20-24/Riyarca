<?php

namespace App\Http\Controllers;

use App\Models\JobPosition;
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

        $jobSeekers = $query->latest()->paginate(10)->appends($request->all());
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

        $jobSeekers = $query->latest()->paginate(10)->appends($request->all());

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
        $jobSeekers = $query->latest()->paginate(10)->appends($request->all());

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

     public function applyForm()
    {
        $positions = JobPosition::where('status', 'open')->get();

        return view('hiring.apply', compact('positions'));
    }

     public function storeApplication(Request $request)
    {
        $request->validate([
            'job_position_id' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:2048'
        ]);

        $resumePath = null;

        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')
                ->store('resumes', 'public');
        }
        JobSeeker::create([
            'job_position_id' => $request->job_position_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'resume_path' => $resumePath,
            'applied_date' => now(),
            'status' => 'pending',
            'stage' => 'applied'
        ]);

        return redirect()->back()->with('success', 'Application submitted successfully');
    }
}