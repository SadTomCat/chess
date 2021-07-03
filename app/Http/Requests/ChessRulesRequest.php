<?php

namespace App\Http\Requests;

use App\Models\ChessRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ChessRulesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('anyAction', ChessRule::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content'  => 'required|string|max:65535|min:30',
            'slug' => 'required|exists:chess_rules,slug',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->slug === null && $this->route('chess_rule') !== null) {
            $this->merge(['slug' => $this->route('chess_rule')]);
        }
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
        $response = new JsonResponse(['status' => false, 'message' => $validator->errors()->first()], 422);

        throw new ValidationException($validator, $response);
    }
}
