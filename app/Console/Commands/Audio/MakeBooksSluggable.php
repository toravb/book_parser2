<?php

namespace App\Console\Commands\Audio;

use App\Models\AudioBook;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Console\Command;

class MakeBooksSluggable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audiobook:slug';

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
        AudioBook::chunk(1000, function ($books){
            foreach ($books as $book){
                if ($book->slug){
                    echo $book->id." - [SKIP]\n";
                    continue;
                }
                $book->slug = SlugService::createSlug(AudioBook::class, 'slug', $book->title);
                $book->save();
                echo $book->id." - [OK]\n";
            }
        });

        return 0;
    }
}
