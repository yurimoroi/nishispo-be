<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Requests\ChangePasswordRequest;
use App\Modules\User\Services\UserService;

class ChangePasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ChangePasswordRequest $request, UserService $userService)
    {
        return $userService->changePassword();
    }
}
