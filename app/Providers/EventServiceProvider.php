<?php

namespace App\Providers;

use App\Api\Events\NewNotificationEvent;
use App\Api\Listeners\NewNotificationListener;
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
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            \SocialiteProviders\Odnoklassniki\OdnoklassnikiExtendSocialite::class.'@handle',
            \SocialiteProviders\Yandex\YandexExtendSocialite::class.'@handle',
            \SocialiteProviders\VKontakte\VKontakteExtendSocialite::class.'@handle',
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewNotificationEvent::class => [
            NewNotificationListener::class,
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
