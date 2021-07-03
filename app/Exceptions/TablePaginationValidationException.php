<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class TablePaginationValidationException extends Exception
{
    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $this->message,
        ], 422);
    }
}
