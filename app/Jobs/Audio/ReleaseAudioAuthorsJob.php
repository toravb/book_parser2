<?php

namespace App\Jobs\Audio;

use App\Models\AudioAuthorsLink;
use App\Models\AudioParsingStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReleaseAudioAuthorsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $status;
    protected $author;
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
//        $authors = AudioAuthorsLink::where('doParse', '=', 1)->get();
        $status = $this->getStatus();

        while ($authors = $this->getAuthors()) {
            $status->increment('max_count', count($authors));
            foreach ($authors as $author) {
                $this->author = $author;
                $author->doParse = 2;
                $author->save();
                ParseAudioAuthorsJob::dispatch($author, $status)->onQueue('audio_parse_authors');
            }
        }
        if ($status->max_count > 0){
            $status->update([
                'doParse' => 1,
                'status' => 'Парсим авторов',
            ]);
        }
    }

    public function getAuthors()
    {
        $authors = AudioAuthorsLink::where('doParse', '=', 1)->limit(100)->get();
        if ($authors->count() > 0){
            return $authors;
        }
        return false;
    }

    public function failed()
    {
        $author = $this->getAuthor();
        if ($author){
            $author->doParse = 1;
            $author->save();
        }
        $status = $this->getStatus();
        ReleaseAudioAuthorsJob::dispatch($status)->onQueue('audio_default');
    }

    public function getStatus()
    {
        return $this->status;
    }
    public function getAuthor()
    {
        return $this->author;
    }

}
