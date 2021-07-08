<?php

namespace App\Http\Requests\Admin;

use App\Helpers\RolesHelper;
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
        $roles = app(RolesHelper::class)->getAvailableRolesByRole('admin');

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
