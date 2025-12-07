<?php

namespace App\Http\ApiResponse;


class ApiResponse
{
    /**
     * Success response format.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data = null, $message = 'Success', $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Error response format.
     *
     * @param  string  $message
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($message = 'Error', $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }
}