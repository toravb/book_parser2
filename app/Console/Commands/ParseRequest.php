<?php

namespace App\Console\Commands;

use App\Http\Controllers\ParserController;
use App\Models\Author;
use App\Models\AuthorToBook;
use App\Models\Book;
use App\Models\Image;
use App\Models\PageLink;
use App\Models\Publisher;
use App\Models\PublisherToBook;
use App\Models\Series;
use App\Models\Year;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ParseRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:request';

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
        $link = "http://loveread.ec/view_global.php?id=24062";
        $data = ParserController::startParsing($link, 'book')['data'];

        $book = $data['database'];
        $search = $data['search'];
        $book['year_id'] = ($search['year'] != null) ? Year::firstOrCreate(['year' => $search['year']])->id : null;
        $book['series_id'] = ($search['series'] != null) ? Series::firstOrCreate(['series' => $search['series']])->id : null;
//                $book['link'] = $link->link;
        $book['params'] = json_encode($data['params']);
        $donor_id = explode('id=', $link)[1];
        $created_book = DB::table('books')->where('donor_id', '=', $donor_id)->first();

        if ($created_book == null){
            $book['link'] = $link;
            $book['donor_id'] = $donor_id;
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
        }
        try {
            Image::create($data['image']);
        }catch (\Exception $e){

        }

        if ($data['pages'] == 0){
            DB::table('books')->where('donor_id', '=', $donor_id)->update(['active' => false]);
        } else {
            foreach ($data['pages'] as $page_link){
                try {
                    PageLink::create($page_link);
                }catch (\Exception $e){
                    continue;
                }
            }
        }
        return 0;
    }
}
