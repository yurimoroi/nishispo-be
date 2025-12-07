<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Modules\User\Requests\PasswordResetRequest;
use App\Modules\User\Services\UserService;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function __construct(
        protected UserService $userService
    ){}

    public function checkCredentials(ResetPasswordRequest $request)
    {
        $data = $request->validated();
        return $this->userService->resetPasswordRequest($data);
    }

    public function resetPassword(PasswordResetRequest $request)
    {
        $data = $request->validated();
        return $this->userService->resetPassword($data);
    }

}
