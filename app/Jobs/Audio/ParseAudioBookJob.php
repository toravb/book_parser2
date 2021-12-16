<?php

namespace App\Jobs\Audio;

use App\Http\Controllers\Audio\AudioParserController;
use App\Models\AudioAuthor;
use App\Models\AudioAuthorsToBook;
use App\Models\AudioBook;
use App\Models\AudioBooksLink;
use App\Models\AudioGenre;
use App\Models\AudioReader;
use App\Models\AudioReadersToBook;
use App\Models\AudioSeries;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParseAudioBookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $link;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AudioBooksLink $link)
    {
        $this->link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = $this->getLink();
        $data = AudioParserController::parse($url->link);
        $book = $url->book()->first();
        $authors = [];
        $readers = [];
        $genre = null;
        $series = null;
        foreach ($data['authors'] as $author) {
            $author_id = AudioAuthor::firstOrCreate(['name' => $author])->id;
            $authors[] = [
                'author_id' => $author_id,
            ];
        }
        foreach ($data['readers'] as $reader) {
            $reader_id = AudioReader::firstOrCreate(['name' => $reader])->id;
            $readers[] = [
                'reader_id' => $reader_id,
            ];
        }
        $genre = AudioGenre::firstOrCreate(['name' => $data['genre']])->id;
        if ($data['series']) {
            $series = AudioSeries::firstOrCreate(['name' => $data['series']])->id;
        }
        if ($book == null) {
            $book = $url->book()->create([
                'title' => $data['title'],
                'description' => $data['description'],
                'params' => json_encode($data['params']),
                'genre_id' => $genre,
                'series_id' => $series,
                'litres' => $data['litres'],
            ]);
        }
        foreach ($authors as $author){
            $author['book_id'] = $book->id;
            try {
                AudioAuthorsToBook::create($author);
            }catch (\Throwable $e){
                if ($e->getCode() != 23000){
                    $this->fail($e);
                    return;
                }
                continue;
            }
        }
        foreach ($readers as $reader){
            $reader['book_id'] = $book->id;
            try {
                AudioReadersToBook::create($reader);
            }catch (\Throwable $e){
                if ($e->getCode() != 23000){
                    $this->fail($e);
                    return;
                }
                continue;
            }
        }
        foreach ($data['images'] as $image){
            try {
                $book->image()->create([
                    'link' => $image,
                ]);
            }catch (\Throwable $e){
                if ($e->getCode() != 23000){
                    $this->fail($e);
                    return;
                }
                continue;
            }
        }
        foreach ($data['audio_links'] as $index => $link){
            try {
                $book->audiobook()->create([
                    'link' => $link['url'],
                    'title' => $link['title'],
                    'index' => $index,
                ]);
            }catch (\Throwable $e){
                if ($e->getCode() != 23000){
                    $this->fail($e);
                    return;
                }
                continue;
            }
        }
        $url->doParse = 0;
        $url->save();
    }

    public function getLink() :AudioBooksLink
    {
        return $this->link;
    }

    public function failed()
    {
        $link = $this->getLink();
        $link->doParse = 2;
        $link->save();
    }
}
