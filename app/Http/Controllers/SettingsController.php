<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    /**
     * @param SettingsRequest $request
     * @return JsonResponse
     */
    public function update(SettingsRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($request->exists('newPassword')) {
            $user->password = Hash::make($request->newPassword);
        }

        $status = $user->update($request->only(['name']));

        return response()->json([
            'status' => $status,
        ]);
    }
}
