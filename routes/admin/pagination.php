<?php

use App\Http\Controllers\TablePaginationController;
use Illuminate\Support\Facades\Route;

Route::get('/table/{table}/paginated', [TablePaginationController::class, 'tablePagination']);

Route::get('/table/{table}/paginated/search', [TablePaginationController::class, 'searchInTable']);
