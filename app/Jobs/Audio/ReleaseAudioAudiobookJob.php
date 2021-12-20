<?php

namespace App\Jobs\Audio;

use App\Models\AudioAudiobook;
use App\Models\AudioParsingStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReleaseAudioAudiobookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $g_status;
    protected $g_link;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AudioParsingStatus $status)
    {
        $this->g_status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $status = $this->getStatus();
        while ($links = $this->getLinks()){
            $status->increment('max_count', count($links));
            foreach ($links as $link){
                $book = $link->book()->first();
                $this->g_link = $link;
                $link->doParse = 2;
                $link->save();
                ParseAudioAudiobookJob::dispatch($link, $book, $status)->onQueue('audio_parse_audio');
            }
        }
        if ($status->max_count > 0){
            $status->update([
                'doParse' => 1,
                'status' => 'Парсим аудио',
            ]);
        }
    }

    public function getStatus()
    {
        return $this->g_status;
    }

    public function getLink()
    {
        return $this->g_link;
    }

    public function getLinks()
    {
        $links = AudioAudiobook::where('doParse', '=', 1)->limit(100)->get();
        if ($links->count() > 0){
            return $links;
        }
        return false;
    }

    public function failed()
    {
        $link = $this->getLink();
        if ($link){
            $link->doParse = 1;
            $link->save();
        }
        $status = $this->getStatus();
        ReleaseAudioAudiobookJob::dispatch($status)->onQueue('audio_default');
    }
}
