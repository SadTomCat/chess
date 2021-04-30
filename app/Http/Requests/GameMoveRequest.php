<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class GameMoveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @param $validator
     * @throws ValidationException
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $exists = $this->exists(['move.from.x', 'move.from.y', 'move.to.x', 'move.to.y']);

            if ($exists === false) {
                $response = new JsonResponse([
                    'status' => false,
                    'message' => 'Incorrect data',
                ], 422);

                throw new ValidationException($validator, $response);
            }
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'move' => 'array',
        ];
    }


}
