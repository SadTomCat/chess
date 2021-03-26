<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class SettingsController extends Controller
{
    /**
     * @param SettingsRequest $request
     * @return JsonResponse
     */
    public function update(SettingsRequest $request): JsonResponse
    {
        $user = Auth::user();

        if ($request->exists('newPassword')) {
            $user->password = Hash::make($request->newPassword);
        }

        if ($request->exists('name')) {
            $user->name = $request->name;
        }

        $status = $user->update();

        return response()->json([
            'status' => $status,
        ]);
    }
}
