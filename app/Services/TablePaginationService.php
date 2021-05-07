<?php

namespace App\Services;

class TablePaginationService
{
    /**
     * Key - table, value - columns
     *
     * @var array|\string[][]
     */
    private static array $tableForAdmin = [
        'users' => ['id', 'name', 'email'],
        'games' => ['id', 'token', 'start_at', 'end_at', 'winner_color'],
    ];

    private static array $tableForUser = [];

    /**
     * Check if someone can get columns from the table and can get data from the table
     *
     * @param bool $isAdmin
     * @param string $table
     * @param array $columns
     * @return bool
     */
    public static function isCorrectColumns(bool $isAdmin, string $table, array $columns): bool
    {
        if (array_key_exists($table, static::$tableForAdmin) === false) {
            return false;
        }

        $tableColumns = $isAdmin === true ? static::$tableForAdmin[$table] : static::$tableForUser[$table];

        foreach ($columns as $column) {
            if (in_array($column, $tableColumns, true) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check that ordering parameter is correct
     *
     * @param bool $isAdmin
     * @param string $table
     * @param array|bool $ordering
     * @return bool
     */
    public static function isCorrectOrdering(bool $isAdmin, string $table, array|bool $ordering): bool
    {
        if ($ordering === false) {
            return true;
        }

        if ($ordering['by'] !== null && strtoupper($ordering['by']) !== 'ASC'
            && strtoupper($ordering['by']) !== 'DESC') {
            return false;
        }

        $tableColumns = $isAdmin === true ? static::$tableForAdmin[$table] : static::$tableForUser[$table];

        return in_array($ordering['column'], $tableColumns, true);
    }
}
