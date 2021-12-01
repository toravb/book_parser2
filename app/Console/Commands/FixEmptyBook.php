<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\BookLink;
use Illuminate\Console\Command;

class FixEmptyBook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:empty';

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
        $books = Book::where('count_pages', '=', 0)->get();
        foreach ($books as $book){
            $link = BookLink::where('link', '=', $book->link)->first();
            $link->doParse = 1;
            $link->save();
            echo $book->id." - [OK]\n";
        }
//        dd(count($books));
        return 0;
    }
}
