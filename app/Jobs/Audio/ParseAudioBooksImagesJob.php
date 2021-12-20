<?php

namespace App\Jobs\Audio;

use App\Models\AudioBook;
use App\Models\AudioImage;
use App\Models\AudioParsingStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ParseAudioBooksImagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $status;
    protected $link;
    protected $book;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AudioImage $link, AudioBook $book, AudioParsingStatus $status)
    {
        $this->status = $status;
        $this->link = $link;
        $this->book = $book;
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

        $disk = Storage::disk('audiobook');
        $free_space = disk_free_space($disk->path(''));
        if ($free_space) {
            if ($free_space / 1024 / 1024 / 1024 <= 2) {
                $status->paused = 1;
                $status->save();
            }else {
                $file = file_get_contents($link->link);
                $relativePath = parse_url($link->link, PHP_URL_PATH);
                $extension = File::extension($relativePath);
                if ($extension == null) {
                    $extension = 'jpg';
                }
                $file_name = 'cover' . '.' . $extension;
                $path = $book->slug . '/' . $file_name;
                $disk->put($path, $file);

                $book->image_name = $file_name;
                $book->save();

                $status->increment('min_count');
                $link->doParse = 0;
                $link->save();
            }
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getBook()
    {
        return $this->book;
    }

    public function failed()
    {
        $link = $this->getLink();
        $link->doParse = 2;
        $link->save();
    }
}
