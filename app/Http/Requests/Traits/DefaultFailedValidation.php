<?php

namespace App\Http\Requests\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

trait DefaultFailedValidation
{
    protected string $defaultErrorFieldInResponse = 'message';

    /**
     * @param Validator $validator
     *
     * @return void
     *
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        $response = new JsonResponse([
            'status'  => false,
            $this->defaultErrorFieldInResponse => $validator->errors()->first(),
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
