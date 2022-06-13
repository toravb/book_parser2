<?php

namespace App\Console\Commands\Book;

use App\Models\BookLink;
use Illuminate\Console\Command;

class SetDonorIdInBookLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book-links:donor_id';

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
        BookLink::query()->whereNull('donor_id')->chunk(1000, function ($links){
            foreach ($links as $link){
                $donor_id = $donor_id = explode('id=', $link->link);
                $link->donor_id = @end($donor_id);
                $link->save();
                echo $link->id.' - [OK]'."\n";
            }
        });
        echo '[COMPLETED]'."\n";
        return 0;
    }
}
