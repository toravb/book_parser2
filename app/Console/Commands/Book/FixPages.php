<?php

namespace App\Console\Commands\Book;

use App\Models\PageLink;
use Illuminate\Console\Command;

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
                dd($link);
            }
        }
        echo '[COMPLETED]'."\n";
        return 0;
    }
}
