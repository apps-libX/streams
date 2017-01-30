<?php

namespace RAD\Streams\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use RAD\Streams\Models\User;

class StreamsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::guest()) {
            $user = User::find(Auth::id());

            return $user->hasPermission(config('streams.user.admin_permission', 'browse_admin')) ? $next($request) : redirect('/');
        }

        return redirect(route('streams.login'));
    }
}
