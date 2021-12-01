<?php

namespace App\Console\Commands;

use App\Http\Controllers\ParserController;
use App\Models\Book;
use App\Models\Image;
use Illuminate\Console\Command;

class FixImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:fix';

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
        Book::chunk(100, function ($books){
            foreach ($books as $book){
                if ($book->id < 107556){
                    echo $book->id.' - [SKIP]'."\n";
                    continue;
                }
                $page = $book->pages()->orderBy('page_number')->get();
                if (!$page->isEmpty()) {
                    $data = ParserController::startParsing($page[0]->link, 'page')['data']['imgs'];
                    if (!empty($data)) {
                        try {
                            Image::create(['link' => $data[0]['link'], 'doParse' => true]);
                        } catch (\Exception $e) {
                            echo $book->id . ' - [EXIST]' . "\n";
                        }

                    }

                }
                if ($page->isEmpty()){
                    echo $book->id.' - [EMPTY]'."\n";
                }
                echo $book->id.' - [OK]'."\n";
            }
        });

        return 0;
    }
}
