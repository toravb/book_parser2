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
use App\Models\Genre;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ParseAudioBookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $g_link;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AudioBooksLink $link)
    {
        $this->g_link = $link;
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
        foreach ($data['authors'] as $c_author) {
            $author = AudioAuthor::query()->where('author', '=', $c_author)->first();
            if ($author == null){
                $author = AudioAuthor::create(['author' => $c_author]);
            }
            $author_id = $author->id;
            $authors[] = [
                'author_id' => $author_id,
            ];
        }
        foreach ($data['readers'] as $c_reader) {
            $reader = AudioReader::query()->where('name', '=', $c_reader)->first();
            if ($reader == null){
                $reader = AudioReader::create(['name' => $c_reader]);
            }
            $reader_id = $reader->id;
            $readers[] = [
                'reader_id' => $reader_id,
            ];
        }
        $genre = Genre::firstOrCreate(['name' => $data['genre']]);
        $genre = $genre->id;
        if ($data['series']) {
            $series = AudioSeries::query()->where('name', '=', $data['series'])->first();
            if ($series == null){
                $series = AudioSeries::create(['name' => $data['series']]);
            }
            $series = $series->id;
        }
        if ($book == null) {
            $book = new AudioBook();
            $book->title = $data['title'];
            $book->description = $data['description'];
            $book->params = json_encode($data['params']);
            $book->genre_id = $genre;
            $book->series_id = $series;
            $book->litres = $data['litres'];
            $book->link_id = $url->id;
            $book->save();
        }else{
            $book->title = $data['title'];
            $book->description = $data['description'];
            $book->params = json_encode($data['params']);
            $book->genre_id = $genre;
            $book->series_id = $series;
            $book->litres = $data['litres'];
            $book->save();
        }
        if (isset($data['params']['Поджанры'])){
            foreach ($data['params']['Поджанры'] as $sub_genre){
                $genre = Genre::query()->where('name', '=', $sub_genre)->first();
                if (!$genre){
                    $genre = new Genre();
                    $genre->fill([
                        'name' => $sub_genre,
                    ]);
                    $genre->save();
                }
                $audio_pivot = DB::table('audio_book_genre')
                    ->where('audio_book_id', '=', $book->id)
                    ->where('genre_id', '=', $genre->id)
                    ->first();
                if (!$audio_pivot){
                    DB::table('audio_book_genre')->insert([
                        'audio_book_id' => $book->id,
                        'genre_id' => $genre->id,
                    ]);
                }
            }
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
                $audio = $book->audiobook()->where('index', '=', $index)->first();
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
}
