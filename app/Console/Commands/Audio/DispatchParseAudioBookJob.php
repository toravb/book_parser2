<?php

namespace App\Console\Commands\Audio;

use App\Jobs\Audio\ParseAudioBookJob;
use App\Models\AudioBooksLink;
use App\Models\Book;
use Illuminate\Console\Command;

class DispatchParseAudioBookJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audio:dispatch-book';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch job for parse books';

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
        AudioBooksLink::query()->update(['doParse' => 1]);
        $links = AudioBooksLink::all();
        foreach ($links as $link){
            ParseAudioBookJob::dispatch($link)->onQueue('audio_parse_books');
            echo $link->id . '- [DISPATCHED]';
        }
        return 0;
    }
}
