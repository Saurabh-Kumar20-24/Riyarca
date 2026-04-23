<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPolicyAccepted
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            $freshUser = User::find($user->id);

            if (!$freshUser->has_accepted_policies) {
                if (
                    !$request->routeIs('policies.accept') &&
                    !$request->routeIs('policies.acceptAll') &&
                    !$request->routeIs('logout')
                ) {
                    return redirect()->route('policies.accept');
                }
            }
        }

        return $next($request);
    }
}