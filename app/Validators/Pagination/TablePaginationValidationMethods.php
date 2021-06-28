<?php

namespace App\Validators\Pagination;

use App\Exceptions\TablePaginationValidationException;

class TablePaginationValidationMethods
{
    /**
     * Key - table, value - columns
     *
     * @var array|\string[][]
     */
    protected array $tablesForAdmin = [
        'users' => ['id', 'name', 'email'],
        'games' => ['id', 'token', 'start_at', 'end_at', 'winner_color'],
    ];

    /**
     * Key - table, value - columns
     *
     * @var array|\string[][]
     */
    protected array $tablesForUser = [];

    /**
     * TablePaginationService constructor.
     *
     * @param bool $isAdmin
     * @param string $table
     * @param array $columns
     * @param array|bool $ordering
     * @param array $searchColumns
     */
    public function __construct(
        protected bool $isAdmin,
        protected string $table,
        protected array|bool $ordering,
    )
    {
    }

    /**
     * Check that table available
     *
     * @throws TablePaginationValidationException
     */
    public function isTableAvailable(): void
    {
        $needleTables = $this->isAdmin === true ? $this->tablesForAdmin : $this->tablesForUser;

        if (array_key_exists($this->table, $needleTables) === false) {
            throw (new TablePaginationValidationException("`$this->table` table not available"));
        }
    }

    /**
     * Check if someone can get columns from the table
     *
     * @param array $columns
     * @return void
     * @throws TablePaginationValidationException
     */
    public function areColumnsAvailable(array $columns): void
    {
        $tableColumns = $this->isAdmin === true
            ? $this->tablesForAdmin[$this->table]
            : $this->tablesForUser[$this->table];

        foreach ($columns as $column) {
            if (in_array($column, $tableColumns, true) === false) {
                throw (new TablePaginationValidationException("`$column` column not available"));
            }
        }
    }

    /**
     * Check that ordering parameter is correct
     *
     * @return void
     * @throws TablePaginationValidationException
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

        $tableColumns = $this->isAdmin === true
            ? $this->tablesForAdmin[$this->table]
            : $this->tablesForUser[$this->table];

        if (in_array($this->ordering['column'], $tableColumns, true) === false) {
            throw (new TablePaginationValidationException('You cannot order by this column'));
        }
    }
}
