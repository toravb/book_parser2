<?php

namespace App\Jobs\Audio;

use App\Http\Controllers\Audio\AudioParserController;
use App\Models\AudioAuthorsLink;
use App\Models\AudioLetter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParseAudioAuthorsLinksJob implements ShouldQueue
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
        $links = AudioLetter::where('doParse', '=', 1)->get();
        foreach ($links as $link){

            $link->doParse = 2;
            $link->save();
            $authors = AudioParserController::parseAuthors($link->link);
            if ($authors == false){
                ParseAudioAuthorsLinksJob::dispatch()->onQueue('audio_default');
                $e = new \Exception($link->link, 404);
                $this->fail($e);
            }
            foreach ($authors as $author){
                try {
                    AudioAuthorsLink::create([
                        'link' => $author
                    ]);
                }catch (\Throwable  $e){
                    if ($e->getCode() != 23000){
                        ParseAudioAuthorsLinksJob::dispatch()->onQueue('audio_default');
                        $this->fail($e);
                    }
                    continue;
                }
            }
            $link->doParse = 0;
            $link->save();
        }
        ReleaseAudioAuthorsJob::dispatch()->onQueue('audio_default');
    }
}
