<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\BookAnchor;
use App\Models\Chapter;
use App\Models\Genre;
use App\Models\Page;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MoveChaptersOfBook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:move-chapters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move chapters of book to new table';

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
        $id = 3406013;
        $anchor = BookAnchor::query()->where('id', '=', $id)->first();
        Book::query()->select('id')->where('id', '>=', $anchor->book_id)->orderBy('id')->chunk(1000, function ($books) use ($id){
            foreach ($books as $book){
                $old_chapters = BookAnchor::query()->where('book_id', '=', $book->id)->orderBy('page_num')->get();
                if ($old_chapters->count()){
                    foreach ($old_chapters as $old_chapter){
                        if ($old_chapter->id <= $id){
                            echo $old_chapter->id . " - [SKIP]\n";
                            continue;
                        }
                        $page = Page::query()
                            ->where('book_id', '=', $book->id)
                            ->where('page_number', '=', $old_chapter->page_num)
                            ->first();
                        if (!$page){
                            echo $old_chapter->id . " - [EMPTY]\n";
                            continue;
                        }
                        try {
                            DB::transaction(function () use ($old_chapter, $page){
                                $chapter = new Chapter();
                                $chapter->fill([
                                    'title' => $old_chapter->name,
                                    'book_id' => $old_chapter->book_id,
                                    'page_id' => $page->id,
                                ]);
                                $chapter->save();
                                echo $old_chapter->id . " - [OK]\n";
                            });
                        }catch (\Exception $exception){
                            dd($exception->getMessage());
                        }
                    }
                }
                echo 'BOOK -'. $book->id . " - [OK]\n";
            }
        });
        echo '[END]';
        return 0;
    }
}
