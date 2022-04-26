<?php

namespace App\Console\Commands;

use App\Models\Image;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SetBooksPreviewPath extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:set-preview-path';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set public path for books previews';

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
                $path = explode('/', $image->link);
                $i = 0;
                while (isset($path[$i]) && $path[$i] != 'img'){
                    unset($path[$i]);
                    $i++;
                }
                if (count($path) == 0){
                    echo $image->id.' - [404]'."\n";
                    continue;
                }
                $image_path = '/'.implode('/', $path);
                $image_public_path = public_path($image_path);
                if (is_file($image_public_path)){
                    try {
                        DB::transaction(function () use ($image_public_path, $image){
                            $image->update(['public_path' => $image_public_path]);
                            echo $image->id.' - [OK]'."\n";
                        });
                    }catch (\Exception $exception){
                        dd($exception->getMessage());
                    }
                }else{
                    echo $image->id.' - [404]'."\n";
                }
            }
        });
        return 0;
    }
}
