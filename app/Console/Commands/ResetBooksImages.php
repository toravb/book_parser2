<?php

namespace App\Console\Commands;

use App\Models\Image;
use Illuminate\Console\Command;

class ResetBooksImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:reset';

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
        Image::chunk(10000, function ($images){
            foreach ($images as $image){
                if ($image->id < 500000){
                    echo $image->id.' - [SKIP]'."\n";
                    continue;
                }
                $image->doParse = 1;
                $image->save();
                echo $image->id.' - [OK]'."\n";
            }
        });


        return 0;
    }
}
