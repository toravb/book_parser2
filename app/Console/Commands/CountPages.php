<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\PageLink;
use Illuminate\Console\Command;

class CountPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:count';

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
        Book::chunk(100, function ($books){
            foreach ($books as $book){
                $pages_count = PageLink::where('book_id', '=', $book->id)->count();
                $pages = $book->pages()->count();
                $book->count_pages = $pages_count;
                $book->save();
                if ($pages != $pages_count){
                    echo "$book->id - $pages != $pages_count\n";
                }else{
                    echo "$book->id - [OK]\n";
                }
            }
        });

        return 0;
    }
}
