<?php

namespace App\Console\Commands\Audio;

use App\Http\Controllers\Audio\AudioParserController;
use App\Models\AudioAudiobook;
use App\Models\AudioAuthor;
use App\Models\AuthorsToAudioBook;
use App\Models\AudioBooksLink;
use App\Models\AudioGenre;
use App\Models\AudioReader;
use App\Models\AudioReadersToBook;
use App\Models\AudioSeries;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Console\Command;

class ParseAudioBook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audio:parse-book';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $urls = AudioBooksLink::query()->orderBy('id')->get();
        foreach ($urls as $url) {
            $data = AudioParserController::parse($url->link);
            $book = $url->book()->first();
            echo $book->id . ' - [START]'."\n";
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
            } else {
                $book->update([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'params' => json_encode($data['params']),
                    'genre_id' => $genre,
                    'series_id' => $series,
                    'litres' => $data['litres'],
                ]);
            }
            foreach ($authors as $author) {
                $author['book_id'] = $book->id;
                try {
                    AuthorsToAudioBook::create($author);
                } catch (\Throwable $e) {
                    if ($e->getCode() != 23000) {
                        dd($e, $book->id);
                    }
                    continue;
                }
            }
            foreach ($readers as $reader) {
                $reader['book_id'] = $book->id;
                try {
                    AudioReadersToBook::create($reader);
                } catch (\Throwable $e) {
                    if ($e->getCode() != 23000) {
                        dd($e, $book->id);
                    }
                    continue;
                }
            }
            foreach ($data['images'] as $image) {
                try {
                    $book->image()->create([
                        'link' => $image,
                    ]);
                } catch (\Throwable $e) {
                    if ($e->getCode() != 23000) {
                        dd($e, $book->id);
                    }
                    continue;
                }
            }
            foreach ($data['audio_links'] as $index => $link) {
                try {
                    if (!$link['title']) {
                        $link['title'] = 'untitled-'.$index;
                    }
                    $audio = $book->audiobook()->where(['index' => $index])->first();
                    if ($audio == null) {
                        $book->audiobook()->create([
                            'link' => str_replace('\\', '', $link['url']),
                            'title' => $link['title'],
                            'index' => $index,
                        ]);
                    } else {
                        $audio->update([
                            'link' => str_replace('\\', '', $link['url']),
                            'title' => $link['title'],
                        ]);
                    }
                    echo $book->id . ' - ' . $index . ' - [OK]'."\n";
                } catch (\Throwable $e) {
                    if ($e->getCode() != 23000) {
                        dd($e, $book->id);
                    }
                    continue;
                }
            }

            echo $book->id . ' - [END]' . "\n";
        }
        return 0;
    }
}
