<?php
namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UserRegistered' => [
            'App\Listeners\CreateContactGroup',
            'App\Listeners\CreateFreePlan',
            'App\Listeners\CreateSetting',
            'App\Listeners\CreateUserToken',
            'App\Listeners\SendEmailConfirmation',
            'App\Listeners\SendAdminNoti',
            'App\Listeners\SendUserSms'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}