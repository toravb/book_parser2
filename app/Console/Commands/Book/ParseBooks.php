<?php

namespace App\Console\Commands\Book;

use App\Http\Controllers\BookParserController;
use App\Jobs\Book\ParseBookJob;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookAnchorsLink;
use App\Models\BookLink;
use App\Models\Genre;
use App\Models\Image;
use App\Models\PageLink;
use App\Models\Publisher;
use App\Models\Series;
use App\Models\Year;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ParseBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:parse-books';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse books list';

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
        while ($link = BookLink::query()->where('doParse', '=', 1)->first()){
            try {
                DB::transaction(function () use ($link){
                    $link->update([
                        'doParse' => 2,
                    ]);
                });
                $params = BookParserController::parseBook($link->link, $link->donor_id);

                $book = Book::query()->where('donor_id', '=', $link->donor_id)->first();
                $year = null;
                if ($params['year']){
                    $year = Year::query()->where('year', '=', $params['year'])->first();
                    if (!$year) {
                        $year = DB::transaction(function () use ($params) {
                            $year = new Year();
                            $year->year = $params['year'];
                            $year->save();
                            return $year;
                        });
                    }
                }
                $series = null;
                if ($params['series']){
                    $series = Series::query()->where('series', '=', $params['series'])->first();
                    if (!$series) {
                        $series = DB::transaction(function () use ($params) {
                            $series = new Series();
                            $series->series = $params['series'];
                            $series->save();
                            return $series;
                        });
                    }
                }
                $genre = null;
                if ($params['genre']){
                    $genre = Genre::query()->where('name', '=', $params['genre'])->first();
                    if (!$genre) {
                        $genre = DB::transaction(function () use ($params) {
                            $genre = new Genre();
                            $genre->name = $params['genre'];
                            $genre->created_at = now();
                            $genre->updated_at = now();
                            $genre->save();
                            return $genre;
                        });
                    }
                }
                $author = null;
                if ($params['author']){
                    $author = [];
                    foreach ($params['author'] as $param) {
                        $s_author = Author::query()->where('author', '=', $param)->first();
                        if (!$s_author) {
                            $s_author = DB::transaction(function () use ($param) {
                                $s_author = new Author();
                                $s_author->author = $param;
                                $s_author->save();
                                return $s_author;
                            });
                        }
                        $author[] = $s_author;
                    }
                }
                $publisher = null;
                if ($params['publisher']){
                    $publisher = [];
                    foreach ($params['publisher'] as $param){
                        $s_publisher = Publisher::query()
                            ->where('publisher', '=', $param)
                            ->first();
                        if (!$s_publisher){
                            $s_publisher = DB::transaction(function () use ($param){
                                $s_publisher = new Publisher();
                                $s_publisher->publisher = $param;
                                $s_publisher->save();
                                return $s_publisher;
                            });
                        }
                        $publisher[] = $s_publisher;
                    }
                }
                if (!$book){
                    $book = DB::transaction(function () use ($params, $series, $year, $link){
                        $book = new Book();
                        $book->title = $params['title'];
                        $book->text = $params['preview_text'];
                        $book->series_id = $series?$series->id:null;
                        $book->year_id = $year?$year->id:null;
                        $book->params = $params['params']?json_encode($params['params']):json_encode([]);
                        $book->donor_id = $link->donor_id;
                        $book->created_at = now();
                        $book->updated_at = now();
                        $book->save();
                        return $book;
                    });
                    if ($params['book_anchors']){
                        $book_anchors = BookAnchorsLink::query()
                            ->where('book_id', '=', $book->id)
                            ->first();
                        if (!$book_anchors) {
                            DB::transaction(function () use ($book, $params) {
                                $anchors_link = new BookAnchorsLink();
                                $anchors_link->book_id = $book->id;
                                $anchors_link->doParse = true;
                                $anchors_link->link = $params['book_anchors'];
                                $anchors_link->save();
                            });
                        }
                    }
                }
                if ($params['book_pages_link']){
                    $page_links = PageLink::query()
                        ->where('book_id', '=', $book->id)
                        ->where('page_num', '=', 1)
                        ->first();
                    if (!$page_links) {
                        DB::transaction(function () use ($book, $params) {
                            $page_link = new PageLink();
                            $page_link->book_id = $book->id;
                            $page_link->page_num = 1;
                            $page_link->doParse = true;
                            $page_link->link = $params['book_pages_link'];
                            $page_link->save();
                        });
                    }
                }
                if ($genre){
                    $book_genre =  DB::table('book_genre')
                        ->where('book_id', '=', $book->id)
                        ->where('genre_id', '=', $genre->id)
                        ->first();
                    if (!$book_genre) {
                        DB::transaction(function () use ($book, $genre) {
                            DB::table('book_genre')->insert([
                                'book_id' => $book->id,
                                'genre_id' => $genre->id,
                            ]);
                        });
                    }
                }
                if ($author){
                    foreach ($author as $item) {
                        $book_author = DB::table('author_to_books')
                            ->where('book_id', '=', $book->id)
                            ->where('author_id', '=', $item->id)
                            ->first();
                        if (!$book_author) {
                            DB::transaction(function () use ($book, $item) {
                                DB::table('author_to_books')->insert([
                                    'book_id' => $book->id,
                                    'author_id' => $item->id,
                                ]);
                            });
                        }
                    }
                }
                if ($publisher){
                    foreach ($publisher as $item){
                        $publisher_to_book = DB::table('publisher_to_books')
                            ->where('publisher_id', '=', $item->id)
                            ->where('book_id', '=', $book->id)
                            ->first();
                        if (!$publisher_to_book){
                            DB::transaction(function () use ($book, $item){
                                DB::table('publisher_to_books')->insert([
                                    'publisher_id' => $item->id,
                                    'book_id' => $book->id,
                                ]);
                            });
                        }
                    }
                }
                if ($params['preview_image']){
                    $preview_image = Image::query()
                        ->where('book_id', '=', $book->id)
                        ->first();
                    if (!$preview_image) {
                        DB::transaction(function () use ($book, $params) {
                            $image_link = new Image();
                            $image_link->book_id = $book->id;
                            $image_link->doParse = true;
                            $image_link->link = $params['preview_image'];
                            $image_link->save();
                        });
                    }
                }
                DB::transaction(function () use ($link){
                    $link->doParse = 0;
                    $link->save();
                });
            }catch (\Exception $exception){
                dd($exception->getMessage());
            }
            echo $link->id.' - [OK]'."\n";
        }
        echo '[COMPLETED]'."\n";
        return 0;
    }
}
