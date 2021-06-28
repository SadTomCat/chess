<?php

namespace App\Validators\Pagination\Interfaces;

use App\Exceptions\TablePaginationValidationException;

interface ITablePaginationValidationMethods
{
    /**
     * Check that table available
     *
     * @throws TablePaginationValidationException
     */
    public function isTableAvailable(): void;

    /**
     * Check if someone can get columns from the table
     *
     * @param array $columns
     *
     * @throws TablePaginationValidationException
     */
    public function areColumnsAvailable(array $columns): void;

    /**
     * Check that ordering parameter is correct
     *
     * @return void
     * @throws TablePaginationValidationException
     */
    public function isCorrectOrdering(): void;
}
