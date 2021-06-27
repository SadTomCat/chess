<?php

namespace App\Validators\Pagination;

use App\Exceptions\TablePaginationValidationException;
use App\Validators\Pagination\Interfaces\ITablePaginationValidator;

class TablePaginationSearchValidationDecorator implements ITablePaginationValidator
{
    /**
     * TablePaginationSearchValidation constructor.
     *
     * @param ITablePaginationValidator $validator
     * @param TablePaginationValidationMethods $validationMethods
     * @param array $searchColumns
     */
    public function __construct(private ITablePaginationValidator $validator,
                                private TablePaginationValidationMethods $validationMethods,
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
