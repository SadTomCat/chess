<?php

namespace App\Validators\Pagination\Interfaces;

use App\Exceptions\TablePaginationValidationException;

interface ITablePaginationValidator
{
    /**
     * @throws TablePaginationValidationException
     */
    public function validate(): void;
}
