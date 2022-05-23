<?php

namespace App\Console\Commands;

use App\Api\Http\Controllers\BookController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class SetBooksOrderColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:set-books-order-column';

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
        App(BookController::class)->setReaders();
        return 'success';
    }
}
