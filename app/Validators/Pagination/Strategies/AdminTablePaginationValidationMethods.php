<?php

namespace App\Validators\Pagination\Strategies;

use App\Exceptions\TablePaginationValidationException;
use App\Models\User;

class AdminTablePaginationValidationMethods extends AbstractTablePaginationValidationMethods
{
    /**
     * Key - table, value - is role and key for access columns
     *
     * @var array|\string[][][]
     */
    protected array $accessForRoles = [
        'users' => ['admin'     => ['id', 'name', 'email', 'blocked', 'blocked_at', 'role'],
                    'support'   => ['id', 'name', 'email', 'blocked', 'blocked_at', 'role'],
                    'moderator' => ['id', 'name', 'email', 'blocked', 'blocked_at', 'role'],
        ],
        'games' => ['admin'     => ['id', 'token', 'start_at', 'end_at', 'winner_color'],
                    'support'   => ['id', 'token', 'start_at', 'end_at', 'winner_color'],
                    'moderator' => ['id', 'token', 'start_at', 'end_at', 'winner_color'],
        ]
    ];

    /**
     * @var bool
     */
    private bool $wasTableValidation = false;

    public function __construct(
        protected User $user,
        protected string $table,
        protected array|bool $ordering,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function isTableAvailable(): void
    {
        $needleTable = $this->accessForRoles[$this->table] ?? null;

        if ($needleTable === null) {
            throw (new TablePaginationValidationException("`$this->table` table not available"));
        }

        $isAvailableForRole = $needleTable[$this->user->role] ?? null;

        if ($isAvailableForRole === null) {
            $message = "`$this->table` table not available for {$this->user->role}";
            throw (new TablePaginationValidationException($message, 403));
        }

        $this->wasTableValidation = true;
    }

    /**
     * @inheritDoc
     *
     *
     * @throws TablePaginationValidationException
     */
    protected function getAccessColumns(): array
    {
        if ($this->wasTableValidation === false) {
            $this->isTableAvailable();
        }

        return $this->accessForRoles[$this->table][$this->user->role];
    }

    /**
     * @inheritDoc
     *
     * @throws TablePaginationValidationException
     */
    protected function getAccessOrderingColumns(): array
    {
        if ($this->wasTableValidation === false) {
            $this->isTableAvailable();
        }

        return $this->accessForRoles[$this->table][$this->user->role];
    }
}
