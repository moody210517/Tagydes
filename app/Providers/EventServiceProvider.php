<?php

namespace Tagydes\Providers;

use Tagydes\Events\User\Banned;
use Tagydes\Events\User\LoggedIn;
use Tagydes\Events\User\Registered;
use Tagydes\Listeners\Users\InvalidateSessionsAndTokens;
use Tagydes\Listeners\Login\UpdateLastLoginTimestamp;
use Tagydes\Listeners\PermissionEventsSubscriber;
use Tagydes\Listeners\Registration\SendConfirmationEmail;
use Tagydes\Listeners\Registration\SendSignUpNotification;
use Tagydes\Listeners\RoleEventsSubscriber;
use Tagydes\Listeners\UserEventsSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendConfirmationEmail::class,
            SendSignUpNotification::class,
        ],
        LoggedIn::class => [
            UpdateLastLoginTimestamp::class
        ],
        Banned::class => [
            InvalidateSessionsAndTokens::class
        ]
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        UserEventsSubscriber::class,
        RoleEventsSubscriber::class,
        PermissionEventsSubscriber::class
    ];

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
