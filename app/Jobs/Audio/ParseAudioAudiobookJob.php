<?php

namespace App\Jobs\Audio;

use App\Models\AudioAudiobook;
use App\Models\AudioBook;
use App\Models\AudioParsingStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ParseAudioAudiobookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $g_status;
    protected $g_link;
    protected $g_book;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AudioAudiobook $link, AudioBook $book, AudioParsingStatus $status)
    {
        $this->g_status = $status;
        $this->g_link = $link;
        $this->g_book = $book;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $link = $this->getLink();
        $status = $this->getStatus();
        $book = $this->getBook();

        $disk = Storage::disk('sftp');
        $free_space = disk_free_space($disk->path(''));
        if ($free_space) {
            if ($free_space / 1024 / 1024 / 1024 <= 2) {
                $status->paused = 1;
                $status->save();
            }else {
                $host = parse_url($link->link);
                $parse = true;
                if (isset($host['host']) && $host['host'] == 'www.litres.ru'){
                    $link->doParse = 5;
                    $link->save();
                    $parse = false;
                }
                if ($parse) {
                    $file = file_get_contents($link->link);
                    $extension = File::extension($link->link);
		            if($extension){
                        $extension = explode('?', $extension)[0];
		            }
                    if ($extension == null) {
                        $extension = 'mp3';
                    }
		            $link->extension = $extension;
                    $link->save();
                    $file_name = $link->title . '.' . $extension;
                    $path = $book->slug . '/' . $file_name;
                    $disk->put($path, $file);


                    $status->increment('min_count');
                    $link->doParse = 0;
                    $link->save();
                }
            }
        }
    }

    public function getStatus()
    {
        return $this->g_status;
    }

    public function getLink()
    {
        return $this->g_link;
    }

    public function getBook()
    {
        return $this->g_book;
    }

    public function failed()
    {
        $link = $this->getLink();
        $link->doParse = 2;
        $link->save();
    }
}
