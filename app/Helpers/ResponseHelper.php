<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function success($data = [], $message = '', int $status_code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'SUCCESS',
            'message' => $message,
            'data' => $data,
        ], $status_code);
    }

    public static function error($errors = [], $message = '', int $status_code = 400): JsonResponse
    {
        return response()->json([
            'status' => 'ERROR',
            'message' => $message,
            'errors' => $errors
        ], $status_code);
    }
}

