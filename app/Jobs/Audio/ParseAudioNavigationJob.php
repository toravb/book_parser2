<?php

namespace App\Jobs\Audio;

use App\Http\Controllers\Audio\AudioParserController;
use App\Models\AudioLetter;
use App\Models\AudioParsingStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParseAudioNavigationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $g_status;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AudioParsingStatus $status)
    {
        $this->g_status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $status = $this->getStatus();
        $links = AudioParserController::parseLetters();
        $status->update([
            'status' => 'Парсим навигацию',
            'created_at' => now(),
            'min_count' => 0,
            'max_count' => count($links),
            'last_parsing' => null,
        ]);
        foreach ($links as $link){
            $status->increment('min_count');
            try {
                AudioLetter::create([
                    'link' => $link,
                ]);
            }catch (\Throwable $e){
                if ($e->getCode() != 23000){
                    $this->fail($e);
                    return;
                }
                continue;
            }
        }
        ParseAudioAuthorsLinksJob::dispatch($status)->onQueue('audio_default');
    }

    public function failed()
    {
        $status = $this->getStatus();
        $status->doParse = 0;
        $status->save();
    }

    public function getStatus()
    {
        return $this->g_status;
    }
}
