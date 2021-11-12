<?php

namespace App\Console\Commands;

use App\Http\Controllers\ParserController;
use App\Jobs\ParsePageJob;
use App\Models\Page;
use App\Models\PageLink;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ParsePage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:page';

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
//        if (DB::table('sites')->where('id', '=', 1)->first()->doParsePages) {
            $link = PageLink::where('doParse', '=', 1)->first();
            if (!$link) {
                DB::table('sites')->where('id', '=', 1)->update(['doParsePages' => false]);
            } else {
                $link->update(['doParse' => 2]);
                $data = ParserController::startParsing($link->link, 'page')['data'];
//                dd($data);
                $page_num = explode('p=', $link->link)[1];

                $created_page = DB::table('pages')->select('id')
                    ->where('book_id', '=', $link->book_id)
                    ->where('page_number', '=', $page_num)
                    ->first();


                if ($created_page == null){
                    $fields = [
                        'link' => $link->link,
                        'book_id' => $link->book_id,
                        'content' => $data['content'],
                        'page_number' => $page_num
                    ];
                    $created_page = Page::create($fields);
                    $created_page->images()->createMany($data['imgs']);
                }
//                $created_page = Page::firstOrCreate(
//                    ['link' => $link->link, 'book_id' => $link->book_id],
//                    ['content' => $data['content'], 'page_number' => $page_num]
//                );
//                if ($page_num == 1) {
//                    unset($data['imgs'][0]);
//                }
//                if ($created_page->wasRecentlyCreated) {
//                    $created_page->images()->createMany($data['imgs']);
//                }

                $link->update(['doParse' => 0]);
                DB::table('parsing_status')->where('parse_type', '=', 2)->increment('Progress');
            }
//        }
        return 0;
    }
}
