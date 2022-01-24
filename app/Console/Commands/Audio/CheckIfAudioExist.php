<?php

namespace App\Console\Commands\Audio;

use App\Models\AudioBook;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckIfAudioExist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audio:check-audio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check existing audio file ';

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

        $books = AudioBook::with('audiobooks')
            ->orderBy('id')
            ->where('id', '>=', '28157')
            ->get();

        foreach ($books as $book){

            foreach ($book->audiobooks as $link) {
                $extension = File::extension($link->link);
                if($extension){
                    $extension = explode('?', $extension)[0];
                }
                if ($extension == null) {
                    $extension = 'mp3';
                }
                $audio_title = $link->title;
                $file_name = Str::slug($audio_title) . '.' . $extension;
                $path = $book->slug . '/' . $file_name;

                if ($disk->exists($path)){
                    echo $book->id.' - file -'.$link->index.' - [OK]'."\n";
                }else{
                    $link->doParse = 1;
                    $link->save();
                    echo $book->id.' - file -'.$link->index.' - [PARSE]'."\n";
                }
            }
        }
        return 0;
    }
}
