<?php

namespace App\Console;

use App\Api\Commands\ReindexBookCommand;
use App\Http\Controllers\ParserController;
use App\Jobs\Audio\ParseAudioAuthorsLinksJob;
use App\Jobs\Audio\ParseAudioNavigationJob;
use App\Jobs\Audio\ReleaseAudioBooksLinksJob;
use App\Jobs\ParseBookJob;
use App\Jobs\ParseImageJob;
use App\Jobs\ParseLinksJob;
use App\Jobs\ParsePageJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ReindexBookCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {

        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
