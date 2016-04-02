<?php

namespace BitsOfLove\MailStats\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;

class EventProvider extends ServiceProvider{

    protected $listen = [
        'BitsOfLove\MailStats\Events\EmailAddedToQueue' => [
            'BitsOfLove\MailStats\Listeners\EmailAddedToQueue\PersistMailStatistic',
        ],
    ];

    /**
     * Bootstrap the application services.
     *
     * @param DispatcherContract $events
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);
    }

}