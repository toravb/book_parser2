<?php

namespace App\Console\Commands\Audio;

use App\Http\Controllers\Audio\AudioParserController;
use App\Models\AudioAudiobook;
use App\Models\AudioBook;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReparseAudioBook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audio:reparse-book';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reparse audio books';

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
        $audio_audiobooks = AudioAudiobook::query()->where('doParse', '=', 2)->get('book_id');
        $books_ids = $audio_audiobooks->unique('book_id')->pluck('book_id');
        $disk = Storage::disk('sftp');
//        $books = AudioBook::query()->with('link')->whereIn('id', $books_ids)->get();
        $books = AudioBook::query()->with('link')->whereIn('id', [8547, 11413])->get();
        foreach ($books as $book){
            $link = $book->link->link;
            $headers = get_headers($link);
            $code = substr($headers[0], 9, 3);
            if ($code == 200) {
                $data = AudioParserController::parse($link);
                foreach ($data['audio_links'] as $index => $link) {
                    if (!$link['title']) {
                        $link['title'] = 'untitled-' . $index;
                    } else {
                        $link['title'] = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
                            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
                        }, $link['title']);
                    }
                    $audio = $book->audiobook()->where(['index' => $index])->first();

                    if ($audio == null) {
                        $book->audiobook()->create([
                            'link' => str_replace('\\', '', $link['url']),
                            'title' => $link['title'],
                            'index' => $index,
                        ]);
                        echo $book->id . ' - ' . $index . ' - [CREATED]' . "\n";
                    } else {
                        $extension = File::extension($audio->link);
                        if ($extension) {
                            $extension = explode('?', $extension)[0];
                        }
                        if ($extension == null) {
                            $extension = 'mp3';
                        }
                        $audio_title = $audio->title;
                        $file_name = Str::slug($audio_title) . '.' . $extension;
                        $path = $book->slug . '/' . $file_name;
                        if ($disk->exists($path)) {
                            $disk->delete($path);
                            echo $book->id . ' - ' . $audio->index . ' - [DELETED]' . "\n";
                        }
                        $audio->update([
                            'link' => str_replace('\\', '', $link['url']),
                            'title' => $link['title'],
                            'doParse' => 3,
                        ]);
                        echo $book->id . ' - ' . $audio->index . ' - [UPDATED]' . "\n";
                    }
                }
            }else{
                echo $book->id .' - [404]' . "\n";
            }
        }
        return 0;
    }
}
