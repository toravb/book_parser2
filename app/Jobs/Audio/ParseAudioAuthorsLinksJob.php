<?php

namespace App\Jobs\Audio;

use App\Http\Controllers\Audio\AudioParserController;
use App\Models\AudioAuthorsLink;
use App\Models\AudioLetter;
use App\Models\AudioParsingStatus;
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

    protected $status;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AudioParsingStatus $status)
    {
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $status = $this->getStatus();
        $status->update([
            'status' => 'Собираем авторов',
            'created_at' => now(),
        ]);
        $links = AudioLetter::where('doParse', '=', 1)->get();
        foreach ($links as $link){

            $link->doParse = 2;
            $link->save();
            $authors = AudioParserController::parseAuthors($link->link);
            if ($authors == false){
//                ParseAudioAuthorsLinksJob::dispatch($status)->onQueue('audio_default');
//                $e = new \Exception($link->link, 404);
//                $this->fail($e);
                continue;
            }
            $status->update([
                'min_count' => 0,
                'max_count' => count($authors),
            ]);
            foreach ($authors as $author){
                $status->increment('min_count');
                try {
                    AudioAuthorsLink::create([
                        'link' => $author
                    ]);
                }catch (\Throwable  $e){
                    if ($e->getCode() != 23000){
                        ParseAudioAuthorsLinksJob::dispatch($status)->onQueue('audio_default');
                        $this->fail($e);
                        return;
                    }
                    continue;
                }
            }
            $link->doParse = 0;
            $link->save();
        }
        $status->last_parsing = now();
        $status->doParse = 0;
        $status->save();
    }

    public function failed()
    {
        $status = $this->getStatus();
        $status->doParse = false;
        $status->save();
    }

    public function getStatus()
    {
        return $this->status;
    }
}
