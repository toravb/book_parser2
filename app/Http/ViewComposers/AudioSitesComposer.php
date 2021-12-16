<?php


namespace App\Http\ViewComposers;

use App\Models\AudioSite;
use Illuminate\Support\Facades\DB;
use  Illuminate\View\View;


class AudioSitesComposer
{
    public $audio_sites = null;
    public $sites = null;

    /**
     * Create a document composer.
     *
     * @return void
     */
    public function __construct()
    {
        $this->audio_sites = AudioSite::all();
        $this->sites = DB::table('sites')->get();
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

        $view->with('audio_sites', $this->audio_sites)->with('sites', $this->sites);
    }

}
