<?php

namespace App\Http\Controllers;

use App\Http\Requests\TablePaginationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TablePaginationController extends Controller
{
    /**
     * @param TablePaginationRequest $request
     * @return JsonResponse
     */
    public function tablePagination(TablePaginationRequest $request): JsonResponse
    {
        $columns = $request->columns;
        $orderBy = $request->ordering['by'] ?? 'ASC';
        $orderingColumn = $request->ordering['column'] ?? 'id';

        if (in_array('id', $columns, true) === false) {
            $columns[] = 'id';
        }

        $paginated = Db::table($request->table)
                       ->orderBy($orderingColumn, $orderBy)
                       ->paginate($request->perPage, $columns, page: $request->page);

        return response()->json([
            'status' => true,
            'total' => $paginated->total(),
            'items' => $paginated->items(),
            'last_page' => $paginated->lastPage(),
            'current_page' => $paginated->currentPage(),
        ]);
    }
}
