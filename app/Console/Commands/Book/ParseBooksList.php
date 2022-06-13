<?php

namespace App\Console\Commands\Book;

use App\Http\Controllers\BookParserController;
use App\Models\BookLink;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ParseBooksList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:parse-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse books list';

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
        for ($i = 1; $i <= 29; $i++) {
            echo '/=======================/'."\n";
            $links = BookParserController::parseBooksList($i);
            foreach ($links as $link){
                try {
                    $p_link = DB::transaction(function () use ($link){
                        $p_link = BookLink::query()->where('donor_id', $link['donor_id'])->first();
                        if (!$p_link){
                            $p_link = BookLink::create([
                                'link' => $link['link'],
                                'doParse' => true,
                                'donor_id' => $link['donor_id']
                            ]);
                        }
                        return $p_link;
                    });
                    echo $p_link->id.' - [OK]'."\n";
                }catch (Exception $exception){
                    dd($exception->getMessage());
                }
            }
            echo '/=======================/'."\n";
        }
        echo '[COMPLETED]'."\n";
        return 0;
    }
}
