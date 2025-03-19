<?php

namespace App\Classes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthResponseApi extends Controller
{
    public function sendResponse($result, $message)
    {
        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => $message,
        ], 200);
    }

    public function sendError($error, $errorMessages = [], $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $error,
            'data' => !empty($errorMessages) ? $errorMessages : null,
        ], $code);
    }
}