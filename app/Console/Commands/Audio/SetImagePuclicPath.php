<?php

namespace App\Console\Commands\Audio;

use App\Jobs\Audio\SetAudioPathJob;
use App\Models\AudioAudiobook;
use App\Models\AudioImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SetImagePuclicPath extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audiobooks:set-image-path';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set public path for images';

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
        AudioImage::query()->chunk(1000, function ($images){
            foreach ($images as $image){
                $book = $image->book()->first();
                if ($book){
                    $disk = Storage::disk('audiobook');
                    if ($disk->exists($book->slug.'/cover.jpg')){
                        $path = url('audiobooks/'.$book->slug.'/cover.jpg');
                        try {
                            DB::transaction(function () use ($image, $path){
                                $image->public_path = $path;
                                $image->save();
                            }, 3);
                        }catch (\Exception $exception){
                            dd($exception->getMessage());
                        }
                        echo $book->id.' - [OK]'."\n";
                    }
                }else{
                    echo $book->id.' - [SKIP]'."\n";
                }
            }
        });
        echo '[COMPLETE]';
        return 0;
    }
}
