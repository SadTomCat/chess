<?php

namespace App\Services;

use App\Exceptions\TablePaginationValidationException;

class TablePaginationService
{
    /**
     * Key - table, value - columns
     *
     * @var array|\string[][]
     */
    private static array $tablesForAdmin = [
        'users' => ['id', 'name', 'email'],
        'games' => ['id', 'token', 'start_at', 'end_at', 'winner_color'],
    ];

    /**
     * Key - table, value - columns
     *
     * @var array|\string[][]
     */
    private static array $tablesForUser = [];

    /**
     * Check that table available
     *
     * @throws TablePaginationValidationException
     */
    public static function isTableAvailable(bool $isAdmin, string $table): void
    {
        $needleTables = $isAdmin === true ? static::$tablesForAdmin : static::$tablesForUser;

        if (array_key_exists($table, $needleTables) === false) {
            throw (new TablePaginationValidationException("`$table` table not available"));
        }
    }

    /**
     * Check if someone can get columns from the table
     *
     * @param bool $isAdmin
     * @param string $table
     * @param array $columns
     * @return void
     * @throws TablePaginationValidationException
     */
    public static function areColumnsAvailable(bool $isAdmin, string $table, array $columns): void
    {
        $tableColumns = $isAdmin === true ? static::$tablesForAdmin[$table] : static::$tablesForUser[$table];

        foreach ($columns as $column) {
            if (in_array($column, $tableColumns, true) === false) {
                throw (new TablePaginationValidationException("`$column` column not available"));
            }
        }
    }

    /**
     * Check that ordering parameter is correct
     *
     * @param bool $isAdmin
     * @param string $table
     * @param array|bool $ordering
     * @return void
     * @throws TablePaginationValidationException
     */
    public static function isCorrectOrdering(bool $isAdmin, string $table, array|bool $ordering): void
    {
        if ($ordering === false) {
            return;
        }

        if ($ordering['by'] !== null && strtoupper($ordering['by']) !== 'ASC'
            && strtoupper($ordering['by']) !== 'DESC') {
            throw (new TablePaginationValidationException('Incorrect ordering'));
        }

        $tableColumns = $isAdmin === true ? static::$tablesForAdmin[$table] : static::$tablesForUser[$table];

        if (in_array($ordering['column'], $tableColumns, true) === false) {
            throw (new TablePaginationValidationException('You cannot order by this column'));
        }
    }
}
