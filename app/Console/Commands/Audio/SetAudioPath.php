<?php

namespace App\Console\Commands\Audio;

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
        $disk = Storage::disk('sftp');
        AudioAudiobook::query()->where('id', '>', 597254)->chunk(1000, function ($audiobooks) use ($disk){
            foreach ($audiobooks as $audiobook){
                $book = $audiobook->book()->first();
                if ($book){
                    $file = $book->slug.'/'.Str::slug($audiobook->title).'.'.$audiobook->extension??'mp3';
                    if ($disk->exists($file)){
                        $path = 'https://audio.loveread.webnauts.pro/audio_books/'.$file;
                        try {
                            DB::transaction(function () use ($path, $audiobook){
                                $audiobook->update(['public_path' => $path]);
                                echo $audiobook->id.' - [OK]'."\n";
                            });
                        }catch (\Exception $exception){
                            dd($exception->getMessage());
                        }
                    }else{
                        echo $audiobook->id.' - [SKIP]'."\n";
                    }
                }else{
                    echo $audiobook->id.' - [SKIP]'."\n";
                }
            }
        });
        echo '[COMPLETE]';
        return 0;
    }
}
