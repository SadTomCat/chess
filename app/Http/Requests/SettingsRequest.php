<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SettingsRequest extends FormRequest
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
            if (!$this->anyFilled(['newPassword', 'name'])) {
                $response = new JsonResponse([
                    'status' => false,
                    'errors' => [
                        'fields' => 'You are not change'
                    ]
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
            'name' => 'string|max:255',
            'password' => 'required|password:web',
            'newPassword' => 'string|min:8|same:newPasswordConfirmation',
        ];
    }

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
            'status' => false,
            'errors' => $validator->errors(),
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
