<?php

namespace App\Http\Middleware;

use App\Enums\UserContributorStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\ApiResponse\ApiResponse;

class ContributorTrainingAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return ApiResponse::error('No authenticated user found.', 401);
        }

        if ($user->contributor_status == UserContributorStatus::notApplied()->value) 
        {
            return ApiResponse::error('Unauthorized. Training not in progress.', 403);
        }

        return $next($request);
    }
}
