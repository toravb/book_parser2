<?php

namespace App\Console\Commands\Audio;

use App\Models\AudioAudiobook;
use App\Models\AudioBook;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class SetDurationFileSize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audio:duration-size';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set file size and duration on audiofile';

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

        $books = AudioBook::all();

        foreach ($books as $book){
            $audios = $book->audiobooks()->get();
            foreach ($audios as $audio){
                if (!$audio->file_size) {
                    $extension = $audio->extension;
                    $audio_title = $audio->title;
                    $file_name = Str::slug($audio_title) . '.' . $extension;
                    $path = $book->slug . '/' . $file_name;
                    if ($disk->exists($path)) {
                        $size = (int)round($disk->size($path) / 1024);
                        $audio->update(['audio_size' => $size]);
                        echo $book->id . ' - ' . $audio->index .' [SIZE IS '.$size.']'."\n";
                    }
                }
            }
        }

        return 0;
    }
}
