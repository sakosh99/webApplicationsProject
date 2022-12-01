<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

trait ApiResponser
{

    protected function successResponse($data = null, $message = null, $code = 200, $token = null)
    {

        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'model' => $data,
            'statusCode' => $code
        ], $code, $token ? [
            'Authorization' => $token
        ] : []);
    }

    protected function errorResponse($data = null, $message = null, $code)
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'model' => $data,
            'statusCode' => $code
        ], $code);
    }
}
