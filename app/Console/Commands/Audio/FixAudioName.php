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

        $audios = AudioAudiobook::all();
        foreach ($audios as $audio){
            dd($audio);
        }

//        $disk = Storage::disk('sftp');
//        $free_space = disk_free_space($disk->path('/'));
//        dd($free_space);

        return 0;
    }
}
