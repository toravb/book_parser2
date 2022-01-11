<?php

namespace App\Console\Commands\Audio;

use App\Models\AudioAudiobook;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FixAudioName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audio:fix-name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix json encoded name of audio files';

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

        $audios = AudioAudiobook::all();
//        $audios = AudioAudiobook::query()->where('book_id', '=', 1)->get();
        foreach ($audios as $audio){
            $title = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
                return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
            }, $audio->title);
            $audio->title = $title;
            $audio->save();
            echo $audio->book_id . ' - ' . $audio->index . ' - [OK] - ' .$title."\n";
        }

        return 0;
    }
}
