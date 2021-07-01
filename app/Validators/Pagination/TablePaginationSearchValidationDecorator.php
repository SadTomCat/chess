<?php

namespace App\Validators\Pagination;

use App\Exceptions\TablePaginationValidationException;
use App\Validators\Pagination\Interfaces\ITablePaginationValidationMethods;
use App\Validators\Pagination\Interfaces\ITablePaginationValidator;

class TablePaginationSearchValidationDecorator implements ITablePaginationValidator
{
    /**
     * TablePaginationSearchValidation constructor.
     *
     * @param ITablePaginationValidator $validator
     * @param ITablePaginationValidationMethods $validationMethods
     * @param array $searchColumns
     */
    public function __construct(
        private ITablePaginationValidator $validator,
        private ITablePaginationValidationMethods $validationMethods,
        private array $searchColumns,
    )
    {
    }

    /**
     * @throws TablePaginationValidationException
     */
    public function validate(): void
    {
        $this->validator->validate();
        $this->validationMethods->areColumnsAvailable($this->searchColumns);
    }
}
