<?php

namespace App\Jobs\Audio;

use App\Models\AudioAudiobook;
use App\Models\AudioBook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SetAudioPathJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected AudioAudiobook $audiobook;
    protected AudioBook $book;

    /**
     * Create a new job instance.
     *
     * @param AudioAudiobook $audiobook
     * @param AudioBook $book
     */
    public function __construct(AudioAudiobook $audiobook, AudioBook $book)
    {
        $this->audiobook = $audiobook;
        $this->book = $book;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $disk = Storage::disk('sftp');
        $file = $this->book->slug.'/'.Str::slug($this->audiobook->title).'.'.$this->audiobook->extension??'mp3';
        if ($disk->exists($file)){
            $path = 'https://audio.loveread.webnauts.pro/audio_books/'.$file;
            AudioAudiobook::query()->where('id', '=', $this->audiobook->id)->update(['public_path' => $path]);
        }
    }
}
