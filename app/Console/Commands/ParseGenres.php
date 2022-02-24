<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

include_once app_path('Modules/simple_html_dom.php');

class ParseGenres extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:genres';

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
        $books = Book::query()->whereNull(['genre_id'])->get();

        foreach ($books as $book){
            $link = $book->link;
            $response = file_get_contents($link);
            $html = str_get_html($response);
            foreach ($html->find('tr.td_top_color') as $element){
                foreach ($element->find('p') as $text){
                    $p = mb_convert_encoding($text->innertext, "utf-8", "windows-1251");

                    $genre = mb_substr($p, 5);

                    $book_genre = Genre::firstOrCreate([
                        'name' => $genre
                    ]);
                    $book->genre_id = $book_genre->id;
                    $book->save();

                    echo $genre. ' - ['.$book_genre->id.']'."\n";
                    echo $book->id.' - [FIXED]'."\n";
                    break;
                }
                break;
            }
        }

        return 0;
    }
}
