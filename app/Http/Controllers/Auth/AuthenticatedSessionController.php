<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return JsonResponse
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->json([
            'status' => true,
            'user' => $request->user()->only(['id', 'name', 'email']),
        ]);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json(['status' => true]);
    }

    /**
     * Send true if user is logged. Before this method needs to be called auth middleware.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function userLogged(Request $request): JsonResponse
    {
        return response()->json([
            'status' => true,
            'user' => $request->user()->only(['id', 'name', 'email']),
        ]);
    }
}
