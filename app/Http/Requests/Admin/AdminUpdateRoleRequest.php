<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Traits\DefaultFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateRoleRequest extends FormRequest
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
        $roles = config('enums.user_roles');

        return [
            'admin_password' => 'required|password:web',
            'role'           => [
                'required',
                'string',
                Rule::in($roles),
            ]
        ];
    }
}
