<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\User;
use Auth;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with([
            'assignedUser',
            'latestActivity.user'
        ])->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name',    'like', '%'.$request->search.'%')
                  ->orWhere('company','like', '%'.$request->search.'%')
                  ->orWhere('phone',  'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leads = $query->paginate(10)->withQueryString();

        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        $users = User::where('is_active', 1)->where('id', '!=', 1)->get();
        return view('leads.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        Lead::create([
            'name'        => $request->name,
            'company'     => $request->company,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'source'      => $request->source,
            'status'      => $request->status ?? 'new',
            'notes'       => $request->notes,
            'assigned_to' => $request->assigned_to,
            'created_by'  => Auth::id(),
        ]);

        return redirect()->route('leads.index')
                         ->with('success', 'Lead created successfully');
    }

    // Return activities as JSON
    public function activities($id)
    {
        $activities = LeadActivity::with('user')
            ->where('lead_id', $id)
            ->latest()
            ->get()
            ->map(fn($a) => $this->formatActivity($a));

        return response()->json($activities);
    }

    public function storeActivity(Request $request)
    {
        
        if (Auth::user()->role_id !== 11) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only BDA can log activities.'
            ], 403);
        }

        $request->validate([
            'lead_id'      => 'required|exists:leads,id',
            'contact_type' => 'required',
            'duration'     => 'nullable|numeric|min:0',
            'discussion'   => 'nullable|string',
            'followup_date'=> 'nullable|date',
        ]);

        LeadActivity::create([
            'lead_id'      => $request->lead_id,
            'user_id'      => Auth::id(),
            'contact_type' => $request->contact_type,
            'duration'     => $request->duration,
            'discussion'   => $request->discussion,
            'followup_date'=> $request->followup_date,
        ]);

        $activities = LeadActivity::with('user')
            ->where('lead_id', $request->lead_id)
            ->latest()
            ->get()
            ->map(fn($a) => $this->formatActivity($a));

        return response()->json(['success' => true, 'activities' => $activities]);
    }

    private function formatActivity(LeadActivity $a): array
    {
        return [
            'user'         => $a->user->name ?? 'Unknown',
            'contact_type' => $a->contact_type,
            'duration'     => $a->duration,
            'discussion'   => $a->discussion,
            'followup_date'=> $a->followup_date
                ? \Carbon\Carbon::parse($a->followup_date)->format('d M Y')
                : null,
            'created_at'   => $a->created_at->format('d M Y h:i A'),
        ];
    }
}