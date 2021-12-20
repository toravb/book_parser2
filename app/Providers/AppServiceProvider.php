<?php

namespace App\Providers;

use App\Models\AudioAuthorsLink;
use App\Models\AudioSite;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\Looping;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Queue::looping(function (Looping $event){
            if ($event->queue == 'audio_parse_authors'){
                $status = AudioSite::where('id', '=', 1)->first()->authorStatus()->first();
                if ($status && (!$status->doParse || $status->paused)){
                    return false;
                }
            }
            if ($event->queue == 'audio_parse_books'){
                $status = AudioSite::where('id', '=', 1)->first()->bookStatus()->first();
                if ($status && (!$status->doParse || $status->paused)){
                    return false;
                }
            }
            if ($event->queue == 'audio_parse_images'){
                $status = AudioSite::where('id', '=', 1)->first()->imageStatus()->first();
                if ($status && (!$status->doParse || $status->paused)){
                    return false;
                }
            }
        });
    }
}
