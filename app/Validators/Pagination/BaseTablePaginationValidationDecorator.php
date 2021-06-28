<?php

namespace App\Validators\Pagination;

use App\Validators\Pagination\Interfaces\ITablePaginationValidationMethods;
use App\Validators\Pagination\Interfaces\ITablePaginationValidator;

class BaseTablePaginationValidationDecorator implements ITablePaginationValidator
{
    /**
     * BaseTablePaginationValidationDecorator constructor.
     *
     * @param ITablePaginationValidationMethods $validationMethods
     * @param array $columns
     */
    public function __construct(private ITablePaginationValidationMethods $validationMethods,
                                private array $columns
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function validate(): void
    {
        $this->validationMethods->isTableAvailable();
        $this->validationMethods->areColumnsAvailable($this->columns);
        $this->validationMethods->isCorrectOrdering();
    }
}
