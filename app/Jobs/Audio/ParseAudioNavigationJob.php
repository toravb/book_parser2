<?php

namespace App\Jobs\Audio;

use App\Http\Controllers\Audio\AudioParserController;
use App\Models\AudioLetter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParseAudioNavigationJob implements ShouldQueue
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
        $links = AudioParserController::parseLetters();
        foreach ($links as $link){
            try {
                AudioLetter::create([
                    'link' => $link,
                ]);
            }catch (\Throwable $e){
                if ($e->getCode() != 23000){
                    $this->fail($e);
                }
                continue;
            }
        }
        ParseAudioAuthorsLinksJob::dispatch()->onQueue('audio_default');
    }
}
