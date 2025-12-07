<?php

namespace App\Modules\Admin\Articles\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\UseLoginIdTrait;
use App\Http\Requests\LoginRequest;
use App\Repositories\UserRepository;
use App\Http\ApiResponse\ApiResponse;

class AdminLoginController extends Controller
{
    public function __invoke(LoginRequest $request, UserRepository $userRepo)
    {
        
        $remember_me = $request->remember_me;
        
        return $userRepo->login($request->login_id, $request->password , $remember_me, true);
    }
}
