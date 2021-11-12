<?php

namespace App\Http\Controllers;

use App\Jobs\ParseBookJob;
use App\Jobs\ParseImageJob;
use App\Jobs\ParsePageJob;
use App\Models\Author;
use App\Models\AuthorToBook;
use App\Models\Book;
use App\Models\BookLink;
use App\Models\Image;
use App\Models\Page;
use App\Models\PageLink;
use App\Models\Publisher;
use App\Models\PublisherToBook;
use App\Models\Series;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Sodium\increment;

class ParserController extends Controller
{

    protected static $books_uri = 'http://loveread.ec/letter_nav.php?let=';

//    $parsing_statuses = [
//        0 => 'links',
//        1 => 'books',
//        2 => 'pages',
//        3 => 'images',
//    ];

    public static function parseLinks()
    {
        DB::table('sites')->where('site', '=', 'loveread.ec')->update(['doParseLinks'=>false]);
        $links = self::startParsing(self::$books_uri, 'links')['data'];
        DB::table('parsing_status')->where('parse_type', '=', 'links')->update(['Count' => count($links)]);
        $i = 1;
        foreach ($links as $link){
            BookLink::firstOrCreate($link);
            DB::table('parsing_status')->where('parse_type', '=', 'links')->update(['Progress' => $i]);
            $i++;
        }
    }

    public static function parseBooks()
    {
        if (DB::table('sites')->where('site', '=', 'loveread.ec')->first()->doParseBooks) {
            $link = BookLink::where('doParse', true)->first();
            if (!$link) {
                DB::table('sites')->where('site', '=', 'loveread.ec')->update(['doParseBooks' => false]);
            } else {
                $data = self::startParsing($link->link, 'book')['data'];

                $book = $data['database'];
                $search = $data['search'];
                $book['year_id'] = ($search['year'] != null) ? Year::firstOrCreate(['year' => $search['year']])->id : null;
                $book['series_id'] = ($search['series'] != null) ? Series::firstOrCreate(['series' => $search['series']])->id : null;
//                $book['link'] = $link->link;
                $book['params'] = json_encode($data['params']);
                $created_book = DB::table('books')->where('link', '=', $link->link)->first();

                if ($created_book->isEmpty()){
                    $book['link'] = $link->link;
                    $created_book = Book::create($book);

                    $author_to_books = [];
                    $publisher_to_books = [];
                    if (count($search['authors']) > 0) {
                        foreach ($search['authors'] as $author) {
                            $author_to_books[] = ['author_id' => Author::firstOrCreate(['author' => $author])->id, 'book_id' => $created_book->id];
                        }
                    }
                    if (count($search['publishers']) > 0) {
                        foreach ($search['publishers'] as $publisher) {
                            $publisher_to_books[] = ['publisher_id' => Publisher::firstOrCreate(['publisher' => $publisher])->id, 'book_id' => $created_book->id];
                        }
                    }
                    if (count($author_to_books) > 0) {
                        foreach ($author_to_books as $insert) {
                            AuthorToBook::firstOrCreate($insert);
                        }
                    }
                    if (count($publisher_to_books) > 0) {
                        foreach ($publisher_to_books as $insert) {
                            PublisherToBook::firstOrCreate($insert);
                        }
                    }

                    $created_book->image()->create($data['image']);
                    if ($data['pages'] == 0){
                        $created_book->active = false;
                        $created_book->save();
                    } else {
                        $created_book->pageLinks()->createMany($data['pages']);
                    }
                }

//                $created_book = Book::firstOrCreate(['link' => $link->link], $book);
//                if ($created_book->wasRecentlyCreated) {

//                $author_to_books = [];
//                $publisher_to_books = [];
//                if (count($search['authors']) > 0) {
//                    foreach ($search['authors'] as $author) {
//                        $author_to_books[] = ['author_id' => Author::firstOrCreate(['author' => $author])->id, 'book_id' => $created_book->id];
//                    }
//                }
//                if (count($search['publishers']) > 0) {
//                    foreach ($search['publishers'] as $publisher) {
//                        $publisher_to_books[] = ['publisher_id' => Publisher::firstOrCreate(['publisher' => $publisher])->id, 'book_id' => $created_book->id];
//                    }
//                }
//                if (count($author_to_books) > 0) {
//                    foreach ($author_to_books as $insert) {
//                        AuthorToBook::firstOrCreate($insert);
//                    }
//                }
//                if (count($publisher_to_books) > 0) {
//                    foreach ($publisher_to_books as $insert) {
//                        PublisherToBook::firstOrCreate($insert);
//                    }
//                }
//
//                $created_book->image()->create($data['image']);
//                if ($data['pages'] == 0){
//                    $created_book->active = false;
//                    $created_book->save();
//                } else {
//                    $created_book->pageLinks()->createMany($data['pages']);
//                }
//                }


                $link->update(['doParse' => false]);
                DB::table('parsing_status')->where('parse_type', '=', 'books')->increment('Progress');

                ParseBookJob::dispatch()->onQueue('doParseBooks');
            }
        }
    }

    public static function parsePages()
    {
        if (DB::table('sites')->where('id', '=', 1)->first()->doParsePages) {
            $link = PageLink::where('doParse', '=', 1)->first();
            if (!$link) {
                DB::table('sites')->where('id', '=', 1)->update(['doParsePages' => false]);
            } else {
                $link->update(['doParse' => 2]);
                $data = ParserController::startParsing($link->link, 'page')['data'];
//                dd($data);
                $page_num = explode('p=', $link->link)[1];

                $created_page = DB::table('pages')->select('id')
                    ->where('book_id', '=', $link->book_id)
                    ->where('page_number', '=', $page_num)
                    ->first();


                if ($created_page == null){
                    $fields = [
                        'link' => $link->link,
                        'book_id' => $link->book_id,
                        'content' => $data['content'],
                        'page_number' => $page_num
                    ];
                    $created_page = Page::create($fields);
                    $created_page->images()->createMany($data['imgs']);
                }
//                $created_page = Page::firstOrCreate(
//                    ['link' => $link->link, 'book_id' => $link->book_id],
//                    ['content' => $data['content'], 'page_number' => $page_num]
//                );
//                if ($page_num == 1) {
//                    unset($data['imgs'][0]);
//                }
//                if ($created_page->wasRecentlyCreated) {
//                    $created_page->images()->createMany($data['imgs']);
//                }

                $link->update(['doParse' => 0]);
                DB::table('parsing_status')->where('parse_type', '=', 2)->increment('Progress');

                ParsePageJob::dispatch()->onQueue('doParsePages');
            }
        }
    }

    public static function parseImage()
    {
        if (DB::table('sites')->where('site', '=', 'loveread.ec')->first()->doParseImages) {
            $link = Image::where('doParse', true)->first();
            if (!$link) {
                DB::table('sites')->where('site', '=', 'loveread.ec')->update(['doParseImages' => false]);
            } else {
                $data = self::startParsing($link->link, 'image')['data'];

                $link->update(['doParse' => false]);
                DB::table('parsing_status')->where('parse_type', '=', 'images')->increment('Progress');
                ParseImageJob::dispatch()->onQueue('doParseImages');
            }
        }
    }

    public static function  startParsing($url, $type)
    {
        set_time_limit(60*60*24);

//        $proxy_ip = self::getProxy();
        $proxy_ip = 0;
        $command = escapeshellcmd("python3 ".app_path('Parser/Controllers'). "/parse.py ". $url .
            " " . $proxy_ip . " " . $type);
        $output = shell_exec($command);
        $data = json_decode($output, true);

        if (isset($data['Message']) && $data['Message'] == 'proxy'){
            DB::table('proxies')->where('proxy', '=', $proxy_ip)->update(['blocked' => 1, 'update_time' => now()]);
        }

        return [
            'data' => $data,
            'ip' => $proxy_ip,
        ];
    }


    private static function getProxy(){

        return DB::table('proxies')->where('blocked', '!=', '1')->inRandomOrder()->limit(1)->first('proxy')->proxy;
    }
}
