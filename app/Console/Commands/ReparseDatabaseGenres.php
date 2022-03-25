<?php

namespace App\Console\Commands;

use App\Models\AudioBook;
use App\Models\Genre;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReparseDatabaseGenres extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:genres';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reparse database genres for new tables';

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
        $book_genres = DB::table('book_genres')->get();
        foreach ($book_genres as $book_genre){
            $genre = Genre::query()->where('name', '=', $book_genre->name)->first();
            if (!$genre){
                try {
                    DB::transaction(function () use ($book_genre){
                        $genre = new Genre();
                        $genre->fill([
                            'name' => $book_genre->name,
                        ]);
                        $genre->save();
                        echo "OK \n";
                    });
                }catch (\Exception $exception){
                    echo $exception->getMessage()."\n";
                }
            }
        }
        $book_pivots = DB::table('book_book_genre')->get();
        foreach ($book_pivots as $book_pivot){
            $old_genre = $book_genres->where('id', '=', $book_pivot->book_genre_id)->first();
            $new_genre = Genre::query()->where('name', '=', $old_genre->name)->first();
            $pivot = DB::table('book_genre')
                ->where('book_id', '=', $book_pivot->book_id)
                ->where('genre_id', '=', $new_genre->id)
                ->first();
            if (!$pivot){
                try {
                    DB::transaction(function () use ($book_pivot, $new_genre){
                        DB::table('book_genre')->insert([
                            'book_id' => $book_pivot->book_id,
                            'genre_id' => $new_genre->id,
                        ]);
                        echo "OK \n";
                    });
                }catch (\Exception $exception){
                    echo $exception->getMessage()."\n";
                }
            }
        }
        $audio_genres = DB::table('audio_genres')->get();
        foreach ($audio_genres as $audio_genre){
            $genre = Genre::query()->where('name', '=', $audio_genre->name)->first();
            if (!$genre){
                try {
                    DB::transaction(function () use ($audio_genre){
                        $genre = new Genre();
                        $genre->fill([
                            'name' => $audio_genre->name,
                        ]);
                        $genre->save();
                        echo "OK \n";
                    });
                }catch (\Exception $exception){
                    echo $exception->getMessage()."\n";
                }
            }
        }

        return 0;
    }
}
