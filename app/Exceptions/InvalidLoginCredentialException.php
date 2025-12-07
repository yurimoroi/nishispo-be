<?php

namespace App\Exceptions;

use App\Http\ApiResponse\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class InvalidLoginCredentialException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function report(Request $request)
    {
        info("Attempted login with invalid credentials.");
    }

    public function render($request)
    {
       return ApiResponse::error(__("invalid_login_credentials"), 401);
    }
}