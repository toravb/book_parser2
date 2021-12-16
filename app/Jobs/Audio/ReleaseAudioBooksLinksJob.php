<?php

namespace App\Jobs\Audio;

use App\Models\AudioBooksLink;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReleaseAudioBooksLinksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        while ($links = $this->getLinks()){
            foreach ($links as $link){
                $link->doParse = 2;
                $link->save();
                ParseAudioBookJob::dispatch($link)->onQueue('audio_parse_books');
            }
        }
    }

    public function getLinks()
    {
        $links = AudioBooksLink::where('doParse', '=', 1)->limit(1000)->get();
        if ($links->count() > 0){
            return $links;
        }
        return false;
    }
}
