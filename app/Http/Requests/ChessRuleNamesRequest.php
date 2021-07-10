<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\DefaultFailedValidation;
use App\Models\ChessRule;
use Illuminate\Foundation\Http\FormRequest;

class ChessRuleNamesRequest extends FormRequest
{
    use DefaultFailedValidation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'name' => 'required|string|min:3|max:32'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'category name',
        ];
    }
}
