<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Traits\DefaultFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminCreateUserRequest extends FormRequest
{
    use DefaultFailedValidation;

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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $roles = array_filter(config('enums.user_roles'), fn($value) => $value !== 'admin');

        return [
            'adminPassword' => 'required|password:web',
            'name' => 'string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => [
                'required',
                Rule::in($roles)
            ],
        ];
    }
}
