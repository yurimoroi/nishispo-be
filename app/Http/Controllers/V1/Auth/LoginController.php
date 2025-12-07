<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Repositories\UserRepository;
use App\Traits\UseLoginIdTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use UseLoginIdTrait;

    public function __invoke(LoginRequest $request, UserRepository $userRepo)
    {
        
        $remember_me = $request->remember_me;
        
        return $userRepo->login($request->login_id, $request->password , $remember_me);
    }
}
