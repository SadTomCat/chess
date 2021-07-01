<?php

namespace App\Http\Controllers;

use App\Http\Requests\RuleCategoriesRequest;
use App\Models\RuleCategory;
use Exception;
use Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function Symfony\Component\Translation\t;

class RuleCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = RuleCategory::all(['id', 'name']);

        return response(['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(RuleCategoriesRequest $request): JsonResponse
    {
        $id = RuleCategory::create(['name' => $request->name])->id;

        return response()->json([
            'status' => true,
            'id'     => $id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RuleCategoriesRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(RuleCategoriesRequest $request, $id): JsonResponse
    {
        try {
            $status = RuleCategory::findOrFail($id)->update(['name' => $request->name]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Category not exist'
            ]);
        }

        return response()->json(['status' => $status]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy($id): JsonResponse
    {
        Gate::authorize('anyAction', RuleCategory::class);

        try {
            $ruleCategory = RuleCategory::findOrFail($id);

            if ($ruleCategory->rule()->exists() === true) {
                return response()->json([
                    'status'  => false,
                    'message' => "For the $ruleCategory->name category exists an article",
                ]);
            }

            $ruleCategory->delete();

        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status'  => false,
                'message' => 'Rule category not exists',
            ]);
        }

        return response()->json(['status' => true,]);
    }
}
