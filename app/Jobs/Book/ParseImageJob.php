<?php

namespace App\Jobs\Book;

use App\Http\Controllers\BookParserController;
use App\Models\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ParseImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Image
     */
    private Image $image;

    /**
     * Create a new job instance.
     *
     * @param Image $image
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $image = $this->image;
        $uri = explode('/', $image->link);
        $path_part = '';
        for ($i = 5; $i < count($uri); $i++){
            $path_part .= '/'.$uri[$i];
        }
        $status = BookParserController::parseImage($image->link, $path_part);
        if ($status && Storage::disk('book')->exists($path_part)){
            $public_path = url('img/photo_books'.$path_part);
            DB::transaction(function () use ($image, $public_path){
                $image->public_path = $public_path;
                $image->doParse = 0;
                $image->save();
            });
        }
    }
}
