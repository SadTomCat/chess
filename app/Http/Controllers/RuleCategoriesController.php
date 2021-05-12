<?php

namespace App\Http\Controllers;

use App\Http\Requests\RuleCategoriesRequest;
use App\Models\RuleCategory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        try {
            $id = RuleCategory::create(['name' => $request->name])->id;
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ]);
        }

        return response()->json([
            'status' => true,
            'id' => $id,
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
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Category not exist'
            ]);
        }

        return response()->json([
            'status' => $status,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        // TODO: add gate
        $status = RuleCategory::destroy($id);

        return response()->json([
            'status' => (bool)$status,
        ]);
    }
}
