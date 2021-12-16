<?php


namespace App\Http\ViewComposers;

use App\Models\AudioSite;
use  Illuminate\View\View;


class AudioSitesComposer
{
    public $audio_sites = null;

    /**
     * Create a document composer.
     *
     * @return void
     */
    public function __construct()
    {
        $this->audio_sites = AudioSite::all();
    }


    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
//        dd($this->routeToDocument);

        $view->with('audio_sites', $this->audio_sites);
    }

}
