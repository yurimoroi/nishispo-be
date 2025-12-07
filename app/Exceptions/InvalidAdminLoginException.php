<?php

namespace App\Exceptions;
use Illuminate\Http\Request;
use App\Http\ApiResponse\ApiResponse;

use Exception;

class InvalidAdminLoginException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function render($request)
     {
        return ApiResponse::error(__("secretariat_access"), 403);
     }
}
