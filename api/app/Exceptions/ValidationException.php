<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ValidationException extends Exception
{
    public function render($request): JsonResponse
    {
        $data = [
            'message' => $this->getMessage(),
        ];

        return response()->json($data, 400);
    }
}
