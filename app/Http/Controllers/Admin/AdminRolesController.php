<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RolesHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminRolesController extends Controller
{
    /**
     * AdminRolesController constructor.
     * @param RolesHelper $rolesHelper
     */
    public function __construct(private RolesHelper $rolesHelper)
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getAccessRoles(Request $request): JsonResponse
    {
        return response()->json([
            'roles' => $this->rolesHelper->getAvailableRolesByRole($request->user()->role),
        ]);
    }
}
