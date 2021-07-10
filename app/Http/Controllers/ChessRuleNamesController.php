<?php

namespace App\Http\Controllers;

use App\Exceptions\ChessRuleException;
use Exception;
use Gate;
use App\Models\ChessRule;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ChessRuleNamesRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ChessRuleNamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * Successful {
     *      names => [
     *          id,
     *          name,
     *          slug
     *      ]
     * }
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $namesInfo = $request->boolean('where_content_filled', false) === true
            ? ChessRule::getFilled(['id', 'name', 'slug'])
            : ChessRule::all(['id', 'name', 'slug']);

        return response()->json(['names_info' => $namesInfo]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Successful {
     *      id => int
     *      status => true
     * }
     *
     * Fail(this, request) {
     *      status => false
     *      message => 'Message'
     * }
     *
     * @param ChessRuleNamesRequest $request
     * @return JsonResponse
     */
    public function store(ChessRuleNamesRequest $request): JsonResponse
    {
        $id = ChessRule::create([
            'name' => $request->name,
            'slug' => ChessRule::getSlug($request->name),
        ])->id;

        return response()->json([
            'status' => true,
            'id'     => $id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * Successful {
     *      status => true
     * }
     *
     * Fail(this, request) {
     *      status => false
     *      message => 'Message'
     * }
     *
     * @param ChessRuleNamesRequest $request
     * @param ChessRule $rule
     * @return JsonResponse
     */
    public function update(ChessRuleNamesRequest $request, ChessRule $chessRule): JsonResponse
    {
        $status = $chessRule->update([
            'name' => $request->name,
            'slug' => ChessRule::getSlug($request->name),
        ]);

        return response()->json(['status' => $status]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Successful {
     *      status => true
     * }
     *
     * Fail(this) - ChessRuleException {
     *      status => false
     *      message => 'Message'
     * }
     *
     * @param ChessRule $chessRule
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws ChessRuleException
     */
    public function destroy(ChessRule $chessRule): JsonResponse
    {
        Gate::authorize('anyAction', ChessRule::class);

        $status = $chessRule->deleteName() ?? false;

        return response()->json(['status' => $status]);
    }
}
