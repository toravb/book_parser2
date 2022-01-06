<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\BookAnchor;
use Illuminate\Console\Command;

include_once app_path('Modules/simple_html_dom.php');

class ParseChaptersOfBook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:chapters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse chapters for book';

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

        $books = Book::query()->select(['id', 'donor_id'])->where('id', '>=', 56379)->orderBy('id')->get();
        foreach ($books as $book) {
            $link = 'http://loveread.ec/contents.php?id='.$book->donor_id;
            $response = file_get_contents($link);
            $html = str_get_html($response);
            $book_anchors = [];
            foreach ($html->find('ul#oglav_link') as $ul){
                $anchor_index = 0;
                foreach ($ul->find('li') as $chapter){
                    foreach ($chapter->find('a') as $el){
                        $sub = explode('&p=', $el->href);

                        if (isset($sub[1])) {
                            $ar = explode('#', $sub[1]);
                            if (isset($ar[1])) {
                                $anchor = [
                                    'book_id' => $book->id,
                                    'page_num' => $ar[0],
                                    'anchor' => $ar[1],
                                    'name' => $el->plaintext,
                                    'anchor_index' => $anchor_index,
                                ];
                                if ($anchor['book_id'] == 13341 && $anchor['anchor'] == 'gl_10') {
                                    $anchor['name'] = '&#1026;';
                                }
                                $book_anchors[] = $anchor;
                                $anchor_index++;
                            }
                        }
                    }
                }
            }
            foreach ($book_anchors as $anchor){
                try {
                    $ch = new BookAnchor();
                    $ch->book_id = $anchor['book_id'];
                    $ch->page_num = $anchor['page_num'];
                    $ch->anchor = $anchor['anchor'];
                    $ch->name = $anchor['name'];
                    $ch->anchor_index = $anchor['anchor_index'];
                    $ch->save();
                }catch (\Exception $e){
                    if ($e->getCode() != 23000){
                        dd($e);
                    }else{
                        continue;
                    }
                }
            }
            echo $book->id.' - [OK]'."\n";
        }

        return 0;
    }
}
