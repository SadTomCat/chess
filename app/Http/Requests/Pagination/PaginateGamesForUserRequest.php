<?php

namespace App\Http\Requests\Pagination;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class PaginateGamesForUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $userFromUrlParameter = $this->user;

        return $this->user()->can('seeGames', [User::class, $userFromUrlParameter]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page' => 'required|integer',
        ];
    }
}
