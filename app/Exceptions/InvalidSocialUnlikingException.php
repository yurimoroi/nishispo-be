<?php

namespace App\Exceptions;

use Exception;
use App\Http\ApiResponse\ApiResponse;

class InvalidSocialUnlikingException extends Exception
{
    public function render($request)
    {
       return ApiResponse::error(__('social_invalid_linking'), 500);
    }
}
