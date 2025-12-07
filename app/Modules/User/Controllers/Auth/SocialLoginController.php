<?php

namespace App\Modules\User\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialLoginRequest;
use App\Repositories\UserRepository;

class SocialLoginController extends Controller
{
    public function __invoke(SocialLoginRequest $request, UserRepository $userRepo)
    {
        return $userRepo->socialLogin($request->provider, $request->provider_id);
    }
}
