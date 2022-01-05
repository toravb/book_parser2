<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;

class DatabaseRemoveGenreId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:genre';

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
        $books = Book::query()->whereNotNull('genre_id')->get();
        foreach ($books as $book){
            try {
                $genre = $book->genre()->first();
                $book_genre = \DB::table('book_book_genres')
                    ->where('book_id', '=', $book->id)
                    ->where('book_genres_id', '=', $genre->id)
                    ->first();
                if ($book_genre == null){
                    $book_genre = \DB::table('book_book_genres')->insert([
                        'book_id' => $book->id,
                        'book_genres_id' => $genre->id,
                        'type' => 0
                    ]);
                }
                $book->genre_id = null;
                $book->save();
                echo "$book->id - [FIXED]\n";
            }catch (\Exception $e){
                dd($e);
            }
        }
        return 0;
    }
}
