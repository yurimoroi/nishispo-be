<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCompanyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {

        $user = $request->user();

        if ($user && $user->company_id) {
            app()->singleton('currentCompanyId', function () use ($user) {
                return $user->company_id;
            });
        }

        return $next($request);
    }
}
