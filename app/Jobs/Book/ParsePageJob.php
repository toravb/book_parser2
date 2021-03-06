<?php

namespace App\Jobs\Book;

use App\Http\Controllers\BookParserController;
use App\Models\Image;
use App\Models\Page;
use App\Models\PageLink;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ParsePageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var PageLink
     */
    private PageLink $pageLink;

    /**
     * Create a new job instance.
     *
     * @param PageLink $pageLink
     */
    public function __construct(PageLink $pageLink)
    {
        $this->pageLink = $pageLink;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $page_link = $this->pageLink;
        $page_number = $page_link->page_num;
        if (!$page_number){
            $page_num = explode('p=', $page_link->link);
            $page_number = @end($page_num);
        }
        $data = BookParserController::parsePage($page_link->link, $page_link->book_id, $page_number);
        if ($data){
            $page = Page::query()
                ->where('book_id', '=', $page_link->book_id)
                ->where('page_number', '=', $page_number)
                ->first();
            if (!$page){
                $page = new Page();
                $page->book_id = $page_link->book_id;
                $page->content = $data['content'];
                $page->page_number = $page_number;
                $page->link = $page_link->link;
                $page->save();
            }
            if ($data['nav']){
                foreach ($data['nav'] as $nav){
                    $link = PageLink::query()
                        ->where('book_id', '=', $page_link->book_id)
                        ->where('page_num', '=', $nav['page_num'])
                        ->first();
                    if (!$link){
                        try {
                            $link = new PageLink();
                            $link->link = $nav['url'];
                            $link->page_num = $nav['page_num'];
                            $link->book_id = $page_link->book_id;
                            $link->doParse = 1;
                            $link->save();
                        }catch (\Exception $exception){
                            if ($exception->getCode() != 23000) {
                                throw $exception;
                            }
                        }
                    }
                }
            }
            if ($data['images']){
                foreach ($data['images'] as $p_image){
                    $image = Image::query()
                        ->where('book_id', '=', $page->book_id)
                        ->where('page_id', '=', $page->id)
                        ->first();
                    if (!$image){
                        try {
                            $image = new Image();
                            $image->link = $p_image['url'];
                            $image->page_id = $page->id;
                            $image->book_id = $page->book_id;
                            $image->doParse = 1;
                            $image->save();
                        }catch (\Exception $exception) {
                            if ($exception->getCode() != 23000) {
                                throw $exception;
                            }
                        }
                    }
                }
            }
        }
        $page_link->doParse = 0;
        $page_link->save();
    }
}
