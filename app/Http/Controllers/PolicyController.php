<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\EmployeePolicyAcknowledgement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = Policy::with('role')->latest()->get();
        $roles    = Role::all();
        return view('policies.index', compact('policies', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('policies.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'role_id' => 'required|exists:roles,id',
            'version' => 'required|string|max:20',
        ]);

        Policy::create([
            'title'      => $request->title,
            'content'    => $request->content,
            'role_id'    => $request->role_id,
            'version'    => $request->version,
            'is_active'  => $request->has('is_active') ? 1 : 0,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('policies.index')
            ->with('success', 'Policy created successfully.');
    }

    public function edit($id)
    {
        $policy = Policy::findOrFail($id);
        $roles  = Role::all();
        return view('policies.edit', compact('policy', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'role_id' => 'required|exists:roles,id',
            'version' => 'required|string|max:20',
        ]);

        $policy = Policy::findOrFail($id);
        $policy->update([
            'title'     => $request->title,
            'content'   => $request->content,
            'role_id'   => $request->role_id,
            'version'   => $request->version,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('policies.index')
            ->with('success', 'Policy updated successfully.');
    }

    public function toggleStatus($id)
    {
        $policy            = Policy::findOrFail($id);
        $policy->is_active = !$policy->is_active;
        $policy->save();

        return back()->with('success', 'Policy status updated.');
    }

    public function acceptPage()
    {
        $user     = Auth::user();
        $policies = Policy::where('role_id', $user->role_id)
            ->where('is_active', 1)
            ->get();

        return view('policies.accept', compact('policies'));
    }

    public function acceptAll(Request $request)
    {
        $user     = Auth::user();

        $policies = Policy::where('role_id', $user->role_id)
            ->where('is_active', 1)
            ->get();

        foreach ($policies as $policy) {
            EmployeePolicyAcknowledgement::updateOrCreate(
                ['user_id' => $user->id, 'policy_id' => $policy->id],
                [
                    'signature'       => 'accepted',
                    'ip_address'      => $request->ip(),
                    'acknowledged_at' => now(),
                ]
            );
        }

        User::where('id', $user->id)->update([
            'has_accepted_policies' => 1,
        ]);

        // Refresh session
        $freshUser = User::find($user->id);
        auth()->login($freshUser);

        return redirect()->route('dashboard')->with('success', 'Policies accepted. Welcome!');
    }
}
