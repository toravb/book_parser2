<?php

namespace App\Console\Commands\Audio;

use App\Jobs\Audio\SetAudioPathJob;
use App\Models\AudioAudiobook;
use App\Models\AudioImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SetAudioPath extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audiobooks:set-audio-path';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set public path for audio';

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
        AudioAudiobook::query()->where('id', '>', 0)->chunk(1000, function ($audiobooks){
            foreach ($audiobooks as $audiobook){
                $book = $audiobook->book()->first();
                if ($book){
                    SetAudioPathJob::dispatch($audiobook, $book)->onQueue('setAudioPathQueue');
                    echo $audiobook->id.' - [DISPATCHED]'."\n";
                }else{
                    echo $audiobook->id.' - [SKIP]'."\n";
                }
            }
        });
        echo '[COMPLETE]';
        return 0;
    }
}
