<?php

namespace App\Http\Controllers;

use App\Events\JoinToSearchGameEvent;
use Illuminate\Support\Facades\Auth;

class SubscribedOnChannelController extends Controller
{
    public function subscribed($channel)
    {
        if ($channel === 'search-game') {
            event(new JoinToSearchGameEvent(Auth::user()));
        }

        return response([
            'status' => true,
        ]);
    }
}
