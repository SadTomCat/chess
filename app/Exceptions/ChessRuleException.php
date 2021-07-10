<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;
use Throwable;

class ChessRuleException extends Exception
{
    /**
     * This fields can override default fields in render method
     * $default = [
     *      status => false,
     *      message => $this->message
     * ]
     *
     * @var array
     */
    protected array $additionalResponseFields = [];

    /**
     * ChessRuleException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param array $additionalResponseFields
     */
    #[Pure] public function __construct(
        string $message = "",
        int $code = 0,
        Throwable $previous = null,
        array $additionalResponseFields = []
    )
    {
        parent::__construct($message, $code, $previous);

        $this->additionalResponseFields = $additionalResponseFields;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        $defaultResponse = [
            'status'  => false,
            'message' => $this->message,
        ];

        return response()->json(array_merge($defaultResponse, $this->additionalResponseFields));
    }
}
