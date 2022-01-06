<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TypesProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Api\Interfaces\Types', 'App\Api\Services\TypesGenerator');
    }
}
