<?php

namespace App\Validators\Pagination;

use App\Exceptions\TablePaginationValidationException;

class TablePaginationWithSearchValidator extends TablePaginationValidator
{
    /**
     * @var array
     */
    protected array $searchColumns;

    /**
     * TablePaginationWithSearchValidator constructor.
     *
     * @param bool $isAdmin
     * @param string $table
     * @param array $columns
     * @param bool|array $ordering
     * @param array $searchColumns
     */
    public function __construct(bool $isAdmin, string $table, array $columns, bool|array $ordering, array $searchColumns)
    {
        parent::__construct($isAdmin, $table, $columns, $ordering);

        $this->searchColumns = $searchColumns;
    }

    /**
     * @throws TablePaginationValidationException
     */
    public function validate(): void
    {
        parent::validate();

        $this->areColumnsAvailable($this->searchColumns);
    }
}
