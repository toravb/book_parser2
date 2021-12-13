<?php

namespace App\Console\Commands\Audio;

use App\Http\Controllers\AudioParserController;
use Illuminate\Console\Command;

class Request extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audio:parse';

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
        AudioParserController::parseAuthor();

        return 0;
    }
}
