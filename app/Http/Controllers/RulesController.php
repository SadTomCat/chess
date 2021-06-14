<?php

namespace App\Http\Controllers;

use App\Http\Requests\RulesRequest;
use App\Models\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Exception;

class RulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * Successful {
     *      rules => [
     *          [
     *              category,
     *              content,
     *          ]
     *      ]
     * }
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'rules' => Rule::all(['rule_category as category', 'content'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Successful {
     *      status => true
     * }
     *
     * Fail(request) {
     *      status => false
     *      message => 'Message'
     * }
     *
     * @param RulesRequest $request
     * @return JsonResponse
     */
    public function store(RulesRequest $request): JsonResponse
    {
        Rule::create([
            'rule_category' => $request->input('category'),
            'content' => $request->input('content'),
        ]);

        return response()->json([
            'status' => true
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
     *      exists => false
     * }
     *
     * @param string $category
     * @return JsonResponse
     */
    public function show(string $category): JsonResponse
    {
        try {
            $rule = Rule::where('rule_category', $category)->firstOrFail();

        } catch (Exception $exception) {
            return response()->json([
                'exists' => false
            ]);
        }

        return response()->json([
            'exists' => true,
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
     *      status => false
     *      message => 'Message'
     * }
     *
     * @param RulesRequest $request
     * @param $category
     * @return JsonResponse
     */
    public function update(RulesRequest $request, string $category): JsonResponse
    {
        try {
            Rule::getByRuleCategory($category)->update(['content' => $request->input('content')]);

        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Rule not exist'
            ]);
        }

        return response()->json([
            'status' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * Successful {
     *      status => true
     * }
     *
     * Fail(this) {
     *      status => false
     *      message => 'Message'
     * }
     *
     * @param string $category
     * @return JsonResponse
     */
    public function destroy(string $category): JsonResponse
    {
        try {
            Rule::getByRuleCategory($category)->delete();

        } catch (ModelNotFoundException) {
            return response()->json([
                'status' => false,
                'message' => 'Rule not exists',
            ]);
        }

        return response()->json([
            'status' => true
        ]);
    }
}
