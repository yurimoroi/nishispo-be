<?php

namespace App\Exceptions;

use Exception;
use App\Http\ApiResponse\ApiResponse;

class InvalidProviderException extends Exception
{
    public function render($request)
    {
       return ApiResponse::error('Invalid social providers', 500);
    }
}
