<?php

namespace App\Console\Commands\Book;

use App\Models\Page;
use App\Models\PageLink;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:fix-pages';

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
        for ($i = 0; $i <= 6; $i++){
            $content = file_get_contents(app_path('Console/Commands/Book').'/fixes/'.'fixed_ids_'.$i.'.txt');
            $array = explode('||', $content);
            foreach ($array as $value){
                $link = PageLink::query()->where('id', '=', $value)->first();
                if ($link) {
                    $page_num = explode('p=', $link->link);
                    $page = Page::query()
                        ->where('book_id', '=', $link->book_id)
                        ->where('page_number', '=', @end($page_num))
                        ->first();
                    DB::transaction(function () use ($page, $link) {
                        if ($page) {
                            $page->delete();
                        }
                        $link->doParse = 1;
                        $link->save();
                    });
                    echo $link->id . ' - [FIXED]' . "\n";
                }
            }
        }
        echo '[COMPLETED]'."\n";
        return 0;
    }
}
