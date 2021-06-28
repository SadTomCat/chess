<?php

namespace App\Validators\Pagination;

use App\Exceptions\TablePaginationValidationException;
use App\Validators\Pagination\Interfaces\ITablePaginationValidator;

class BaseTablePaginationValidationDecorator implements ITablePaginationValidator
{
    /**
     * BaseTablePaginationValidationDecorator constructor.
     *
     * @param TablePaginationValidationMethods $validationMethods
     * @param array $columns
     */
    public function __construct(private TablePaginationValidationMethods $validationMethods,
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
