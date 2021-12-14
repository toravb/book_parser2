<?php

namespace App\Jobs\Audio;

use App\Models\AudioAuthorsLink;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReleaseAudioAuthorsJob implements ShouldQueue
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
        AudioAuthorsLink::where('doParse', '=', 1)->chunk(1000, function ($authors){
            foreach ($authors as $author){
                $author->doParse = 2;
                $author->save();
                ParseAudioAuthorsJob::dispatch($author)->onQueue('audio_parse_authors');
            }
        });
    }
}
