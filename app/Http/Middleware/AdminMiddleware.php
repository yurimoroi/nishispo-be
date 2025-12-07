<?php

namespace App\Http\Middleware;

use App\Http\ApiResponse\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return ApiResponse::error('No authenticated user found.',401);
        }
        
        if (!$user->secretariat_flg) {
            return ApiResponse::error('Unauthorized. Only secretariat members can access this resource.',403);
        }

        return $next($request);
    }
}
