<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\JobPosition;
class JobPositionController extends Controller
{
    public function index(Request $request)
{
    $query = JobPosition::query();
    if ($request->search) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }
    if ($request->status) {
        $query->where('status', $request->status);
    }
    $jobs = $query->paginate(10);
    return view('job_positions.index', compact('jobs'));
}
    public function create()
    {
        return view('job_positions.create');
    }
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'department' => 'required',
        'vacancies' => 'required|integer',
        'status' => 'required'
    ]);

    JobPosition::create($request->all());

    return redirect()->back()->with('success', 'Job added successfully');
}
}