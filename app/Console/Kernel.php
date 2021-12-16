<?php

namespace App\Console;

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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {


//        $schedule->command('queue:restart')
//            ->everyThirtyMinutes();
////
//        $schedule->command('queue:work --name=doParsePages1 --queue=doParsePages  --daemon')
//            ->everyMinute()
//            ->withoutOverlapping();
//        $schedule->command('queue:work --name=doParsePages2 --queue=doParsePages  --daemon')
//            ->everyMinute()
//            ->withoutOverlapping();
//        $schedule->command('queue:work --name=doParsePages3 --queue=doParsePages  --daemon')
//            ->everyMinute()
//            ->withoutOverlapping();
//
//        $schedule->command('queue:work --name=doParsePages4 --queue=doParsePages  --daemon')
//            ->everyMinute()
//            ->withoutOverlapping();
//        $schedule->command('queue:work --name=doParsePages5 --queue=doParsePages  --daemon')
//            ->everyMinute()
//            ->withoutOverlapping();
//
//        $schedule->command('queue:work --name=doParseImages --queue=doParseImages  --daemon')
//            ->everyMinute()
//            ->withoutOverlapping();
//        $schedule->command('queue:work --name=doParseImages2 --queue=doParseImages  --daemon')
//            ->everyMinute()
//            ->withoutOverlapping();
//        $schedule->command('queue:work --name=doParseImages3 --queue=doParseImages  --daemon')
//            ->everyMinute()
//            ->withoutOverlapping();
//
//        $schedule->command('queue:work --name=doParseImages4 --queue=doParseImages  --daemon')
//            ->everyMinute()
//            ->withoutOverlapping();
//        $schedule->command('queue:work --name=doParseImages5 --queue=doParseImages  --daemon')
//            ->everyMinute()
//            ->withoutOverlapping();
//
//        $schedule->command('queue:work --name=doParseBooks --queue=doParseBooks  --daemon')
//            ->everyMinute()
//            ->withoutOverlapping();
//        $schedule->command('queue:work --queue=default --timeout=0  --daemon')
//            ->everyMinute()
//            ->withoutOverlapping();
//////
//        $loveread = DB::table('sites')->where('id', '=', 1)
//            ->select()->first();
////
//        $schedule->job((new ParseLinksJob)::dispatchIf($loveread->doParseLinks)->onQueue('default'))->everyFiveMinutes();
//        $schedule->job((new ParseBookJob)::dispatchIf($loveread->doParseBooks)->onQueue('doParseBooks'))->everyFiveMinutes();
//        $schedule->job((new ParsePageJob)::dispatchIf($loveread->doParsePages)->onQueue('doParsePages'))->everyFiveMinutes();
//        $schedule->job((new ParseImageJob())::dispatchIf($loveread->doParseImages)->onQueue('doParseImages'))->everyFiveMinutes();

//        $schedule->job((new ParseAudioNavigationJob)::dispatchIf(true)->onQueue('audio_default'))->everyFiveMinutes();
        $schedule->job((new ReleaseAudioBooksLinksJob)::dispatchIf(true)->onQueue('audio_default'))->everyFiveMinutes();
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
