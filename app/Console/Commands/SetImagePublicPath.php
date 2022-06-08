<?php

namespace App\Console\Commands;

use App\Jobs\Audio\SetAudioPathJob;
use App\Models\AudioAudiobook;
use App\Models\AudioImage;
use App\Models\Image;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SetImagePublicPath extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:set-image-path';

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
        Image::query()->whereNull('public_path')->chunk(1000, function ($images){
            foreach ($images as $image){
                if ($image->link){
                    $name = explode('/', $image->link);
                    $disk = Storage::disk('book');
                    $f_name = '';
                    for ($i = 5; $i < count($name); $i++){
                        $f_name .= '/'.$name[$i];
                    }
                    if ($disk->exists($f_name)){
                        $path = url('img/photo_books'.$f_name);
                        try {
                            DB::transaction(function () use ($image, $path){
                                $image->public_path = $path;
                                $image->save();
                            }, 3);
                        }catch (\Exception $exception){
                            dd($exception->getMessage());
                        }
                        echo $image->id.' - [OK]'."\n";
                    }else{
                        echo $image->link."\n";
                    }
                }
            }
        });
        echo '[COMPLETE]';
        return 0;
    }
}
