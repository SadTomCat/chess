<?php

namespace App\Http\Controllers;

use App\Exceptions\ChessRuleException;
use App\Http\Requests\ChessRulesRequest;
use App\Models\ChessRule;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ChessRulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * Successful {
     *      rules => [
     *          [
     *              name,
     *              content,
     *              slug,
     *          ]
     *      ]
     * }
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'rules' => ChessRule::getFilled(['name', 'content', 'slug'])
        ]);
    }

    /**
     * Display the specified resource.
     *
     * Successful {
     *      exists => true
     *      content => html
     * }
     *
     * Fail {
     *      status => true|false
     *      exists => false
     * }
     *
     * @param string $slug
     * @return JsonResponse
     * @throws ChessRuleException
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $rule = ChessRule::getBySlug($slug);

            if ($rule->content === null) {
                throw new ChessRuleException(
                    'For this rule not exist content',
                    additionalResponseFields: ['status' => true, 'exists' => false]
                );
            }

        } catch (ModelNotFoundException $exception) {
            return response()->json(['exists' => false]);
        }

        return response()->json([
            'exists'  => true,
            'content' => $rule->content,
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
     *      message => 'Message'
     * }
     *
     * @param ChessRulesRequest $request
     * @param string $slug
     * @return JsonResponse
     */
    public function update(ChessRulesRequest $request, string $slug): JsonResponse
    {
        ChessRule::getBySlug($slug)->update(['content' => $request->input('content')]);

        return response()->json(['status' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Successful {
     *      status => true
     * }
     *
     * Fail(this) {
     *      message => 'Message'
     * }
     *
     * @param string $slug
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(string $slug): JsonResponse
    {
        Gate::authorize('anyAction', ChessRule::class);

        ChessRule::getBySlug($slug)->update(['content' => null]);

        return response()->json(['status' => true]);
    }
}
