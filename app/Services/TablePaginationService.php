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

    /**
     * Check if the admin can get columns from the table and can get data from the table
     *
     * @param string $tableName
     * @param array $columns
     * @return bool
     */
    public static function canPaginateByAdmin(string $tableName, array $columns): bool
    {
        if (array_key_exists($tableName, static::$tableForAdmin) === false) {
            return false;
        }

        $tableColumns = static::$tableForAdmin[$tableName];

        foreach ($columns as $column) {
            if (in_array($column, $tableColumns, true) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $tableName
     * @param array $columns
     * @return bool
     */
    public static function canPaginateByUser(string $tableName, array $columns): bool
    {
        return false;
    }
}
