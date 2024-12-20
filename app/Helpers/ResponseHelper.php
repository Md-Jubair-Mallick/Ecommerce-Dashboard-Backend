<?php
namespace App\Http\Helpers;

class ResponseHelper
{
    public static function success($message= 'success', $data = [], $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            ], $statusCode);
    }

    public  static function error($message = 'error', $data = [], $statusCode = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
            ], $statusCode);
    }
}
