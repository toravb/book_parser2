<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;

class AddDonorId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:donor';

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
        $book = Book::where('donor_id', '=', null)->first();
        if ($book) {
            $link = $book->link;
            $id = explode('id=', $link)[1];
            $book->donor_id = $id;
            $book->save();
            echo "$book->id - Donor ID - $id [OK]\n";
            $this->handle();
        }
        return 0;
    }
}
