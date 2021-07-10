<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\DefaultFailedValidation;
use App\Models\ChessRule;
use Illuminate\Foundation\Http\FormRequest;

class ChessRulesRequest extends FormRequest
{
    use DefaultFailedValidation;

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
            'content' => 'required|string|max:65535|min:30',
            'slug'    => 'required|exists:chess_rules,slug',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->slug === null && $this->route('chess_rule') !== null) {
            $this->merge(['slug' => $this->route('chess_rule')]);
        }
    }
}
