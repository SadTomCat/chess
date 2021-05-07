<?php

namespace App\Http\Requests;

use App\Services\TablePaginationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class TablePaginationRequest extends FormRequest
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

            if (TablePaginationService::isCorrectColumns($forAdmin, $this->table, $this->columns) === false) {
                $response = new JsonResponse([
                    'status' => false,
                    'message' => 'This table or columns are not accessed',
                ], 422);

                throw new ValidationException($validator, $response);
            }

            if (TablePaginationService::isCorrectOrdering($forAdmin, $this->table, $this->ordering) === false) {
                $response = new JsonResponse([
                    'status' => false,
                    'message' => 'Incorrect ordering',
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
            'table' => 'required|string',
            'columns' => 'required|array',
            'page' => 'required|integer',
            'perPage' => 'required|integer',
        ];
    }
}
