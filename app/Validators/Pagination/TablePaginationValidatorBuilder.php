<?php

namespace App\Validators\Pagination;

use App\Exceptions\TablePaginationValidationException;
use App\Validators\Pagination\Interfaces\ITablePaginationValidationMethods;
use App\Validators\Pagination\Interfaces\ITablePaginationValidator;
use App\Validators\Pagination\Strategies\AbstractTablePaginationValidationMethods;
use App\Validators\Pagination\Strategies\AdminTablePaginationValidationMethods;
use App\Validators\Pagination\Strategies\GuestTablePaginationValidationMethods;
use Auth;

final class TablePaginationValidatorBuilder
{
    private ITablePaginationValidationMethods $validationMethods;

    private ITablePaginationValidator $validator;

    /**
     * TablePaginationValidatorFactory constructor.
     *
     * @param bool $forAdmin
     * @param string $table
     * @param $columns
     * @param array|bool $ordering
     *
     * @throws TablePaginationValidationException
     */
    public function __construct(bool $forAdmin, string $table, $columns, array|bool $ordering)
    {
        $this->validationMethods = $this->buildValidationMethods($forAdmin, $table, $ordering);
        $this->validator = new BaseTablePaginationValidationDecorator($this->validationMethods, $columns);
    }

    /**
     * @param bool $forAdmin
     * @param string $table
     * @param $columns
     * @param array|bool $ordering
     *
     * @return TablePaginationValidatorBuilder
     * @throws TablePaginationValidationException
     */
    public static function create(bool $forAdmin, string $table, $columns, array|bool $ordering): self
    {
        return new self($forAdmin, $table, $columns, $ordering);
    }

    /**
     * @param $searchColumns
     *
     * @return TablePaginationValidatorBuilder
     */
    public function wrapInSearchValidation($searchColumns): self
    {
        $this->validator = new TablePaginationSearchValidationDecorator($this->validator,
                                                                        $this->validationMethods,
                                                                        $searchColumns
        );

        return $this;
    }

    /**
     * @return ITablePaginationValidator
     */
    public function getValidator(): ITablePaginationValidator
    {
        return $this->validator;
    }

    /**
     * @param bool $forAdmin
     * @param string $table
     * @param array|bool $ordering
     *
     * @return AbstractTablePaginationValidationMethods
     * @throws TablePaginationValidationException
     */
    private function buildValidationMethods(bool $forAdmin, string $table, array|bool $ordering): ITablePaginationValidationMethods
    {
        $user = Auth::user();

        if ($forAdmin === true) {
            if ($user === null) {
                throw new TablePaginationValidationException('Unauthorized');
            }

            return new AdminTablePaginationValidationMethods($user, $table, $ordering);
        }

        return new GuestTablePaginationValidationMethods($user, $table, $ordering);
    }
}
