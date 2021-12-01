<?php

namespace App\Console\Commands;

use App\Http\Controllers\ParserController;
use App\Models\Book;
use App\Models\Image;
use Illuminate\Console\Command;

class FixBooksImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:images';

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
        $book_to_parse = [];
        Book::chunk(1000, function ($books) use (&$book_to_parse){
            foreach ($books as $book){
                $img = $book->image()->first();
                if ($img == null){
                    $book_to_parse[] = [
                        'link' => $book->link,
                        'id' => $book->id
                    ];
                }
                echo $book->id." - [OK]\n";
            }
        });
        foreach ($book_to_parse as $book){
            $data = ParserController::startParsing($book['link'], 'book')['data'];
            $img = Image::where(['link' => $data['image']['link']])->first();
            if ($img != null){
                if ($img->book_id == null){
                    $img->book_id = $book['id'];
                    $img->save();
                }else {
                    $img = Image::create([
                        'link' => $img->link,
                        'book_id' => $book['id'],
                        'doParse' => 3,
                    ]);
                }
            }else{
                $img = Image::create([
                    'link' => $data['image']['link'],
                    'book_id' => $book['id'],
                    'doParse' => 3,
                ]);
            }
            echo $book['id']." - [PARSED]\n";
        }
        return 0;
    }
}
