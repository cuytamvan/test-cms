<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class UnauthException extends Exception
{
    public function render($request): JsonResponse
    {
        $data = [
            'message' => $this->getMessage(),
        ];

        return response()->json($data, 401);
    }
}
