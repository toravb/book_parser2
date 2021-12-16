<?php

namespace App\Console\Commands\Audio;

use App\Http\Controllers\Audio\AudioParserController;
use App\Jobs\Audio\ParseAudioNavigationJob;
use App\Models\AudioAuthorsLink;
use App\Models\AudioBooksLink;
use App\Models\AudioLetter;
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
//        $a = AudioParserController::parse('https://knigavuhe.org/book/ponedelnik-nachinaetsja-v-subbotu/');
//        $a = AudioParserController::parse('https://knigavuhe.org/book/kaleidoskop-4/');
//        $a = AudioParserController::parseAuthor('https://knigavuhe.org/author/sergejj-abramov/');
//        $a = AudioParserController::parse('https://knigavuhe.org/book/chjornyjj-predel/');
//        $a = $authors = AudioAuthorsLink::where('doParse', '=', 1)->limit(1000)->get();
//        dd($a->count());
        $a = AudioBooksLink::where('doParse', '=', 2)->get();
        foreach ($a as $b){
            AudioParserController::parse($b->link);
        }
//        dd($a);
        return 0;
    }
}
