<?php

namespace App\Console\Commands\Book;

use App\Http\Controllers\BookParserController;
use App\Models\Genre;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ParseGenres extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:parse-genres';

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
        $genres = BookParserController::parseGenres();

        foreach ($genres as $genre){
            $s_genre = Genre::query()->where('name', '=', $genre)->first();
            if (!$s_genre) {
                try {
                    $s_genre = DB::transaction(function () use ($genre) {
                        $s_genre = new Genre();
                        $s_genre->name = $genre;
                        $s_genre->created_at = now();
                        $s_genre->updated_at = now();
                        $s_genre->save();
                        return $s_genre;
                    });
                }catch (\Exception $exception){
                    dd($exception->getMessage());
                }
            }
            echo $s_genre->id .' - [OK]'."\n";
        }

        return 0;
    }
}
