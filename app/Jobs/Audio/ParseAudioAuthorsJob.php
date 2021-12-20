<?php

namespace App\Jobs\Audio;

use App\Http\Controllers\Audio\AudioParserController;
use App\Models\AudioAuthorsLink;
use App\Models\AudioBooksLink;
use App\Models\AudioParsingStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParseAudioAuthorsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $g_author;
    protected $g_status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AudioAuthorsLink $author, AudioParsingStatus $status)
    {
        $this->g_author = $author;
        $this->g_status = $status;
    }

    /**
     * Execute the job.
     *
     * @param $author
     * @return void
     */
    public function handle()
    {
        $status = $this->getStatus();
        $author = $this->getAuthor();
        $books = AudioParserController::parseAuthor($author->link);
        foreach ($books as $book){
            try {
                AudioBooksLink::create([
                    'link' => $book
                ]);
            }catch (\Throwable $e){
                if ($e->getCode() != 23000){
//                    ParseAudioAuthorsJob::dispatch($author)->onQueue('audio_parse_authors');
                    $this->fail($e);
                    return;
                }
                continue;
            }
        }
        $status->increment('min_count');
        $author->doParse = 0;
        $author->save();
    }

    /**
     * @return AudioAuthorsLink
     */
    public function getAuthor(): AudioAuthorsLink
    {
        return $this->g_author;
    }

    public function failed()
    {
        $author = $this->getAuthor();
        $author->doParse = 2;
        $author->save();
    }

    public function getStatus()
    {
        return $this->g_status;
    }
}
