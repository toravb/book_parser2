<?php

namespace App\Console\Commands\Book;

use App\Http\Controllers\BookParserController;
use App\Jobs\Book\ParsePageJob;
use App\Models\Image;
use App\Models\Page;
use App\Models\PageLink;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ParsePages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:parse-pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse pages';

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
        while ($page_link = PageLink::query()->where('doParse', '=', 1)->first()){
            DB::transaction(function () use ($page_link){
                $page_link->update([
                    'doParse' => 2,
                ]);
            });
            ParsePageJob::dispatch($page_link)->onQueue('bookPage');
            echo $page_link->id.' - [DISPATCHED]'."\n";
        }
        echo '[COMPLETED]'."\n";
        return 0;
    }
}
