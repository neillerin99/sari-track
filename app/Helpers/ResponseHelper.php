<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function success($data = [], $message = '', int $status_code = 200, bool $pagination = false): JsonResponse
    {
        $res = [
            'status' => 'SUCCESS',
            'message' => $message,
        ];
        if ($pagination) {
            $res_data = $data->toArray();
        } else {
            $res_data['data'] = $data;
        }
        return response()->json(array_merge($res, $res_data), $status_code);
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

