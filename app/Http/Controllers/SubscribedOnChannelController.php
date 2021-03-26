<?php

namespace App\Http\Controllers;

use App\Events\JoinToSearchGameEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SubscribedOnChannelController extends Controller
{
    /**
     * @param string $channel
     * @return JsonResponse
     */
    public function subscribed(string $channel): JsonResponse
    {
        if ($channel === 'search-game') {
            event(new JoinToSearchGameEvent(Auth::user()));
        }

        return response()->json(['status' => true]);
    }
}
