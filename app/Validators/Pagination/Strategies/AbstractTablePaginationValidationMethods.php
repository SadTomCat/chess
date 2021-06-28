<?php

namespace App\Validators\Pagination\Strategies;

use App\Exceptions\TablePaginationValidationException;
use App\Validators\Pagination\Interfaces\ITablePaginationValidationMethods;

abstract class AbstractTablePaginationValidationMethods implements ITablePaginationValidationMethods
{
    /**
     * @var bool|array
     */
    protected bool|array $ordering;

    /**
     * @return string[]
     */
    abstract protected function getAccessColumns(): array;

    /**
     * @return string[]
     */
    abstract protected function getAccessOrderingColumns(): array;

    /**
     * @inheritDoc
     */
    public function areColumnsAvailable(array $columns): void
    {
        $accessColumns = $this->getAccessColumns();

        foreach ($columns as $column) {
            if (in_array($column, $accessColumns, true) === false) {
                throw (new TablePaginationValidationException("`$column` column not available"));
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function isCorrectOrdering(): void
    {
        if ($this->ordering === false) {
            return;
        }

        $normalizedOrderingBy = strtoupper($this->ordering['by']);

        if ($this->ordering['by'] !== null && $normalizedOrderingBy !== 'ASC' && $normalizedOrderingBy !== 'DESC') {
            throw (new TablePaginationValidationException('Incorrect ordering'));
        }

        $tableColumns = $this->getAccessOrderingColumns();

        if (in_array($this->ordering['column'], $tableColumns, true) === false) {
            throw (new TablePaginationValidationException('You cannot order by this column'));
        }
    }
}
