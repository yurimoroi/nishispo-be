<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Services\UserService;
use Illuminate\Http\Request;

class RequestLoginIdController extends Controller
{
    public function __construct(
        protected UserService $userService
    ){}
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' =>'required|email'
        ], [
            'email.required' => __("email.required")
        ]
    );

        return $this->userService->requestLoginId($request->email);
    }
}
