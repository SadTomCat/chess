<?php

namespace App\Providers;

use App\Events\GameEndEvent;
use App\Events\GameMoveEvent;
use App\Events\GameStartEvent;
use App\Events\JoinToSearchGameEvent;
use App\Listeners\GameEndListener;
use App\Listeners\UpdateMoveTimeEndListener;
use App\Listeners\JoinToSearchGameListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class            => [
            SendEmailVerificationNotification::class,
        ],
        JoinToSearchGameEvent::class => [
            JoinToSearchGameListener::class,
        ],
        GameStartEvent::class        => [
            UpdateMoveTimeEndListener::class,
        ],
        GameMoveEvent::class         => [
            UpdateMoveTimeEndListener::class,
        ],
        GameEndEvent::class          => [
            GameEndListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
