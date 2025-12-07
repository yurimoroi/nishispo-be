<?php

namespace App\Exceptions;

use App\Http\ApiResponse\ApiResponse;
use Exception;

class UnableToCreateUserException extends Exception
{
    public function render($request)
    {
       return ApiResponse::error('Unable to create user.Please try again.', 500);
    }
}
