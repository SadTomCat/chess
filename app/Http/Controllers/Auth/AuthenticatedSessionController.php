<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response([
            'status' => true,
            'user' => $request->user()->only(['id', 'name', 'email']),
        ]);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function destroy(Request $request): \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response(['status' => true]);
    }

    /**
     * Send true if user is logged. Before this method needs to be called auth middleware.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function userLogged(Request $request)
    {
        return response([
            'status' => true,
            'user' => $request->user()->only(['id', 'name', 'email']),
        ]);
    }
}
