<?php

namespace App\Console\Commands\Book;

use App\Http\Controllers\BookParserController;
use App\Jobs\Book\ParseImageJob;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookAnchorsLink;
use App\Models\Genre;
use App\Models\Image;
use App\Models\PageLink;
use App\Models\Publisher;
use App\Models\Series;
use App\Models\Year;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ParseImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:parse-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse images';

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
        while ($image = Image::query()->where('doParse', '=', 1)->first()){
            DB::transaction(function () use ($image){
                $image->update([
                    'doParse' => 2,
                ]);
            });
            ParseImageJob::dispatch($image)->onQueue('bookImage');
            echo $image->id.' - [DISPATCHED]'."\n";
        }
        echo '[COMPLETED]'."\n";

        return 0;
    }
}
