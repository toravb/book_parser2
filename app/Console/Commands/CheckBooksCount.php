<?php

namespace App\Console\Commands;

use App\Http\Controllers\ParserController;
use App\Models\BookLink;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckBooksCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:books';

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
        $count = 0;
        $array = [];
        for ($j = 1; $j <= 29; $j++) {
            $links = ParserController::startParsing(ParserController::$books_uri.$j, 'links')['data'];

            foreach ($links as $link) {
                if (isset($array[$link['link']])){
                    $array[$link['link']] = $array[$link['link']] + 1;
                }else{
                    $array[$link['link']] = 1;
                }

                try {
                    BookLink::create($link);
                }catch (\Exception $e){
                    if ($e->getCode() != 23000){
                        dd($e);
                    }
                }
                echo $link['link']." - [OK]\n";
            }
        }
        foreach ($array as $key => $val){
            if ($val > 1){
                $count += $val;
                echo $key." - [$val]\n";
            }
        }
        echo '--------------------------------------------------------------'."\n";
        echo $count;
        return 0;
    }
}
