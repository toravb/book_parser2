<?php

namespace App\Jobs\Audio;

use App\Models\AudioBooksLink;
use App\Models\AudioParsingStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReleaseAudioBooksLinksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $status;
    protected $link;
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
        while ($links = $this->getLinks()){
            $status->increment('max_count', count($links));
            foreach ($links as $link){
                $this->link = $link;
                $link->doParse = 2;
                $link->save();
                ParseAudioBookJob::dispatch($link, $status)->onQueue('audio_parse_books');
            }
        }
        if ($status->max_count > 0){
            $status->update([
                'doParse' => 1,
                'status' => 'Парсим книги',
            ]);
        }
    }

    public function getLinks()
    {
        $links = AudioBooksLink::where('doParse', '=', 1)->limit(100)->get();
        if ($links->count() > 0){
            return $links;
        }
        return false;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function failed()
    {
        $link = $this->getLink();
        if ($link){
            $link->doParse = 1;
            $link->save();
        }
        $status = $this->getStatus();
        ReleaseAudioBooksLinksJob::dispatch($status)->onQueue('audio_default');
    }
}
