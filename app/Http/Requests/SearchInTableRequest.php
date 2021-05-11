<?php

namespace App\Http\Requests;

use App\Exceptions\TablePaginationValidationException;
use App\Services\TablePaginationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SearchInTableRequest extends FormRequest
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
            $forAdmin = preg_match('/\/admin((?![^ ])|\/)/', $this->path()) === 1;

            $this->ordering = is_array($this->ordering) === true ? $this->ordering : false;

            try {
                TablePaginationService::isTableAvailable($forAdmin, $this->table);
                TablePaginationService::areColumnsAvailable($forAdmin, $this->table, $this->columns);
                TablePaginationService::areColumnsAvailable($forAdmin, $this->table, $this->searchColumns);
                TablePaginationService::isCorrectOrdering($forAdmin, $this->table, $this->ordering);

            } catch (TablePaginationValidationException $e) {
                $response = new JsonResponse([
                    'status' => false,
                    'message' => $e->getMessage(),
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
            'columns' => 'required|array',
            'searchColumns' => 'required|array',
            'page' => 'required|integer',
            'perPage' => 'required|integer',
            'needle' => 'required|string|min:3'
        ];
    }
}