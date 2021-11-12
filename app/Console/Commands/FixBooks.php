<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use function Couchbase\defaultDecoder;

class FixBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:fix';

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
        $book = Book::where('fixed', '=', false)->first();
        if ($book) {
            $books = Book::where('link', '=', $book->link)->get();

            if ($books->count() > 1) {
                $fixed_book = $books[0];
                $book_id = $fixed_book->id;
                for ($i = 1; $i < $books->count(); $i++) {
                    $pages = $books[$i]->pages;
                    foreach ($pages as $page) {
                        $page->book_id = $book_id;
                        $page->save();
                        echo "$book_id - $page->page_number [FIXED]\n";
                    }
                    echo $books[$i]->id . " - [DELETED]\n";
                    $books[$i]->delete();
                }
                $fixed_book->fixed = true;
                $fixed_book->save();
                echo "$book->id - [FIXED]\n";
                $this->handle();
            } else {
                $book->fixed = true;
                $book->save();
                echo "$book->id - [OK]\n";
                $this->handle();
            }
        }
        return 0;
    }
}
