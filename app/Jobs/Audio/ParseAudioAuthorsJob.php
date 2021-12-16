<?php

namespace App\Jobs\Audio;

use App\Http\Controllers\Audio\AudioParserController;
use App\Models\AudioAuthorsLink;
use App\Models\AudioBooksLink;
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

    protected $author;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AudioAuthorsLink $author)
    {
        $this->author = $author;
    }

    /**
     * Execute the job.
     *
     * @param $author
     * @return void
     */
    public function handle()
    {
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
        $author->doParse = 0;
        $author->save();
    }

    /**
     * @return AudioAuthorsLink
     */
    public function getAuthor(): AudioAuthorsLink
    {
        return $this->author;
    }

    public function failed()
    {
        $author = $this->getAuthor();
        $author->doParse = 2;
        $author->save();
    }
}
