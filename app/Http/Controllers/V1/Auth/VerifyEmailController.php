<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));
       
        if ($user->hasVerifiedEmail()) {
            return redirect(env('FRONT_URL') . '/email/verify/already-success');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect(env('FRONT_URL') . '/email/verify/success');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return ApiResponse::success(['message' => 'Email already verified.']);
        }
    
        $request->user()->sendEmailVerificationNotification();

        return ApiResponse::success(['message' => 'Verification email resent.']);
    }
}
