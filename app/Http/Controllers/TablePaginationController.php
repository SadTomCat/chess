<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pagination\SearchInTableRequest;
use App\Http\Requests\Pagination\TablePaginationRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TablePaginationController extends Controller
{
    /**
     * @param TablePaginationRequest $request
     * @param string $table
     * @return JsonResponse
     */
    public function tablePagination(TablePaginationRequest $request, string $table): JsonResponse
    {
        $columns = $request->columns;
        $orderBy = $request->ordering['by'] ?? 'ASC';
        $orderingColumn = $request->ordering['column'] ?? 'id';

        if (in_array('id', $columns, true) === false) {
            $columns[] = 'id';
        }

        $paginated = Db::table($table)
                       ->orderBy($orderingColumn, $orderBy)
                       ->paginate($request->perPage, $columns, page: $request->page);

        return $this->getSuccessfulResponse($paginated);
    }

    /**
     * $table - was validated in SearchInTableRequest
     *
     * @param SearchInTableRequest $request
     * @param string $table
     * @return JsonResponse
     */
    public function searchInTable(SearchInTableRequest $request, string $table): JsonResponse
    {
        $whereConditions = [];
        $columns = $request->columns;
        $orderBy = $request->ordering['by'] ?? 'ASC';
        $orderingColumn = $request->ordering['column'] ?? 'id';

        if (in_array('id', $columns, true) === false) {
            $columns[] = 'id';
        }

        foreach ($request->searchColumns as $searchColumn) {
            $whereConditions[] = [$searchColumn, 'LIKE', "%$request->needle%"];
        }

        if (count($whereConditions) === 0) {
            return response()->json([
                'status' => true,
                'items'  => [],
            ]);
        }

        $paginated = DB::table($table)
                       ->select($columns)
                       ->where(function ($query) use ($whereConditions) {
                           foreach ($whereConditions as $searchCondition) {
                               $query->orWhere(...$searchCondition);
                           }
                       })
                       ->orderBy($orderingColumn, $orderBy)
                       ->paginate($request->perPage, $columns, page: $request->page);

        return $this->getSuccessfulResponse($paginated);
    }

    /**
     * Fields [
     *      status,
     *      total - total items,
     *      items,
     *      last_page,
     *      current_page,
     * ]
     *
     * @param LengthAwarePaginator $paginated
     * @return JsonResponse
     */
    private function getSuccessfulResponse(LengthAwarePaginator $paginated): JsonResponse
    {
        return response()->json([
            'status'       => true,
            'total'        => $paginated->total(),
            'items'        => $paginated->items(),
            'last_page'    => $paginated->lastPage(),
            'current_page' => $paginated->currentPage(),
        ]);
    }
}
