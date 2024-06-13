<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            Auth::logout(); // Logout the user
            return redirect()->route('login')->with('toast', [
                'title' => trans('public.unauthorized_access'),
                'type' => 'error'
            ]);  // Redirect to login with an error message
        }

        return $next($request);
    }
}