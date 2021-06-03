<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ScopeAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $scopeName = 'admin')
    {
        if (!$request->user()->tokenCan($scopeName))
            abort(403, 'Unauthorized');

        return $next($request);
    }
}
