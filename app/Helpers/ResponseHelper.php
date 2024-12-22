<?php
namespace App\Http\Helpers;

class ResponseHelper
{
    public static function success($data = [], $message= 'success', $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            ], $statusCode);
    }

    public  static function error($message = 'error', $statusCode = 500)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            ], $statusCode);
    }
}
