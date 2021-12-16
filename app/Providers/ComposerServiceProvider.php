<?php

namespace App\Providers;

use App\Http\ViewComposers\AudioSitesComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
//        view()->composer(
//            'app',
//            'App\Http\ViewComposers\DocumentComposer'
//        );
        view()->composer(['components.sidebar'], AudioSitesComposer::class);
    }
}
