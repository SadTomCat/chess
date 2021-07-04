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
        $status = $this->code >= 400 && $this->code <= 500 ? $this->code : 422;

        return response()->json([
            'status' => false,
            'message' => $this->message,
        ], $status);
    }
}
