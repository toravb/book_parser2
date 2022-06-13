<?php

namespace App\Console\Commands\Book;

use App\Http\Controllers\BookParserController;
use App\Models\Author;
use App\Models\Genre;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ParseAuthors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:parse-authors';

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
        for ($i = 1; $i <= 29; $i++){
            $authors = BookParserController::parseAuthors($i);
            foreach ($authors as $author){
                $s_author = Author::query()->where('author', '=', $author)->first();
                if (!$s_author) {
                    try {
                        $s_author = DB::transaction(function () use ($author) {
                            $s_author = new Author();
                            $s_author->author = $author;
                            $s_author->save();
                            return $s_author;
                        });
                    }catch (\Exception $exception){
                        dd($exception->getMessage());
                    }
                }
                echo $s_author->id .' - [OK]'."\n";
            }
        }

        return 0;
    }
}
