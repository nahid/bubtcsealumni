<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isVerified()) {
            return redirect()->route('dashboard')->with('error', 'Your account is not verified yet.');
        }

        if ($request->user()?->isBlocked()) {
            abort(403, 'Your account has been blocked.');
        }

        return $next($request);
    }
}
