<?php

namespace App\Jobs\Audio;

use App\Http\Controllers\Audio\AudioParserController;
use App\Models\AudioAudiobook;
use App\Models\AudioAuthor;
use App\Models\AuthorsToAudioBook;
use App\Models\AudioBook;
use App\Models\AudioBooksLink;
use App\Models\AudioGenre;
use App\Models\AudioParsingStatus;
use App\Models\AudioReader;
use App\Models\AudioReadersToBook;
use App\Models\AudioSeries;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParseAudioBookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $g_link;
    protected $g_status;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AudioBooksLink $link, AudioParsingStatus $status)
    {
        $this->g_link = $link;
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
        }else{
            $book->update([
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
                AuthorsToAudioBook::create($author);
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
                if (!$link['title']){
                    $link['title'] = 'untitled-'.$index;
                }else{
                    $link['title'] = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
                        return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
                    }, $link['title']);
                }
                $audio = $book->audiobook()->where(['index' => $index])->first();
                if ($audio == null){
                    $book->audiobook()->create([
                        'link' => str_replace('\\', '', $link['url']),
                        'title' => $link['title'],
                        'index' => $index,
                    ]);
                }else{
                    $audio->update([
                        'link' => str_replace('\\', '', $link['url']),
                        'title' => $link['title'],
                    ]);
                }
            }catch (\Throwable $e){
                if ($e->getCode() != 23000){
                    $this->fail($e);
                    return;
                }
                continue;
            }
        }
        $status->increment('min_count');
        $url->doParse = 0;
        $url->save();
    }

    public function getLink() :AudioBooksLink
    {
        return $this->g_link;
    }

    public function failed()
    {
        $link = $this->getLink();
        $link->doParse = 2;
        $link->save();
    }

    public function getStatus()
    {
        return $this->g_status;
    }
}
