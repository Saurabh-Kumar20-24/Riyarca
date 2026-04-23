
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (
            !$user->role ||
            !in_array(strtolower($user->role->role_name), array_map('strtolower', $roles))
        ) {
            abort(403, 'Unauthorized. You do not have access to this page.');
        }

        return $next($request);
    }
}
