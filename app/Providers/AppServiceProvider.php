<?php

namespace App\Providers;

use App\Api\Services\TypesGenerator;
use App\Models\AudioSite;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Queue\Events\Looping;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */


    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        Schema::defaultStringLength(191);

        $typesGenerator = new TypesGenerator();
        Relation::enforceMorphMap($typesGenerator->getViewsTypes());
        Relation::enforceMorphMap($typesGenerator->getReviewTypes());

//        Queue::looping(function (Looping $event) {
//            if ($event->queue == 'audio_parse_authors') {
//                $status = AudioSite::where('id', '=', 1)->first()->authorStatus()->first();
//                if ($status && (!$status->doParse || $status->paused)) {
//                    return false;
//                }
//            }
//            if ($event->queue == 'audio_parse_books') {
//                $status = AudioSite::where('id', '=', 1)->first()->bookStatus()->first();
//                if ($status && (!$status->doParse || $status->paused)) {
//                    return false;
//                }
//            }
//            if ($event->queue == 'audio_parse_images') {
//                $status = AudioSite::where('id', '=', 1)->first()->imageStatus()->first();
//                if ($status && (!$status->doParse || $status->paused)) {
//                    return false;
//                }
//            }
//            if ($event->queue == 'audio_parse_audio') {
//                $status = AudioSite::where('id', '=', 1)->first()->audioBookStatus()->first();
//                if ($status && (!$status->doParse || $status->paused)) {
//                    return false;
//                }
//            }
//        });

        $this->app->bind(Client::class, function () {
            return ClientBuilder::create()
                ->setHosts(config('services.search.hosts'))
                ->build();
        });

        $this->app->bind('App\Api\Interfaces\SearchRepositoryInterface', 'App\Api\Repositories\ElasticsearchRepository');
    }
}
