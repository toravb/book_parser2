<?php

namespace App\Console\Commands\Audio;

use App\Http\Controllers\AudioParserController;
use App\Models\AudioLetter;
use Illuminate\Console\Command;

class ParseLetters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audio:letters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $links = AudioParserController::parseLetters();
        $bar = $this->output->createProgressBar(count($links));
        $bar->start();
        foreach ($links as $link){
            try {
                AudioLetter::create([
                    'link' => $link,
                ]);
            }catch (\Exception $e){
                continue;
            }
            $bar->advance();
        }
        $bar->finish();
        return Command::SUCCESS;
    }
}
