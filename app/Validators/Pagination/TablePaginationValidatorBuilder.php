<?php

namespace App\Validators\Pagination;

use App\Validators\Pagination\Interfaces\ITablePaginationValidator;

final class TablePaginationValidatorBuilder
{
    private TablePaginationValidationMethods $validationMethods;

    private ITablePaginationValidator $validator;

    /**
     * TablePaginationValidatorFactory constructor.
     *
     * @param bool $isAdmin
     * @param string $table
     * @param $columns
     * @param array|bool $ordering
     */
    public function __construct(bool $isAdmin, string $table, $columns, array|bool $ordering)
    {
        $this->validationMethods = new TablePaginationValidationMethods($isAdmin, $table, $ordering);
        $this->validator = new BaseTablePaginationValidationDecorator($this->validationMethods, $columns);
    }

    /**
     * @param bool $isAdmin
     * @param string $table
     * @param $columns
     * @param array|bool $ordering
     *
     * @return TablePaginationValidatorBuilder
     */
    public static function create(bool $isAdmin, string $table, $columns, array|bool $ordering): self
    {
        return new self($isAdmin, $table, $columns, $ordering);
    }

    /**
     * @param $searchColumns
     *
     * @return TablePaginationValidatorBuilder
     */
    public function wrapInSearchValidation($searchColumns): self
    {
        $this->validator = new TablePaginationSearchValidationDecorator(
            $this->validator,
            $this->validationMethods,
            $searchColumns
        );

        return $this;
    }

    public function getValidator(): ITablePaginationValidator
    {
        return $this->validator;
    }
}
