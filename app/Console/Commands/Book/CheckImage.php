<?php

namespace App\Console\Commands\Book;

use App\Models\Image;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CheckImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:check-image';

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
        Image::query()->chunk(1000, function ($images){
            foreach ($images as $image){
                $uri = explode('/', $image->link);
                $path_part = '';
                for ($i = 5; $i < count($uri); $i++){
                    $path_part .= '/'.$uri[$i];
                }
                if (!Storage::disk('book')->exists($path_part)) {
                    DB::transaction(function () use ($image) {
                        $image->update([
                            'doParse' => 1,
                        ]);
                    });
                }
                echo $image->id.' - [CHECKED]'."\n";
            }
        });
        echo '[COMPLETED]'."\n";
        return 0;
    }
}
