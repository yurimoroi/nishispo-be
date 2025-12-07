<?php

namespace App\Exceptions;

use App\Http\ApiResponse\ApiResponse;
use Exception;

class InvalidCurrentPassword extends Exception
{
    public function report()
    {
        info("Attempted Change Password: Invalid Old Password.");
    }

    public function render($request)
    {
       return ApiResponse::error(__("old_password_not_match"), 401);
    }
}
