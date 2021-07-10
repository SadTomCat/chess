<?php

namespace App\Validators\Pagination\Strategies;

use App\Exceptions\TablePaginationValidationException;
use App\Models\User;

class GuestTablePaginationValidationMethods extends AbstractTablePaginationValidationMethods
{
    public function __construct(
        protected User $user,
        protected string $table,
        protected array|bool $ordering,
    )
    {
    }

    public function isTableAvailable(): void
    {
        throw (new TablePaginationValidationException("`$this->table` table not available"));
    }

    protected function getAccessOrderingColumns(): array
    {
        return [];
    }

    protected function getAccessColumns(): array
    {
        return [];
    }
}
