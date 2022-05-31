<?php

namespace App\Console\Commands\Audio;

use App\Models\AudioBook;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LocalParseBookParams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audiobook:params';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Local parse params of audiobook for change keys';

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
        AudioBook::query()->chunk(1000, function ($books){
            foreach ($books as $book){
                if ($book->params){
                    $params = json_decode($book->params, true);
                    $new_params = [];
                    if (isset($params['Поджанры'])){
                        $new_params['sub_genres'] = $params['Поджанры'];
                    }
                    if (isset($params['Общие характеристики'])){
                        $new_params['characteristic'] = $params['Общие характеристики'];
                    }
                    if (isset($params['Место действия'])){
                        $new_params['place'] = $params['Место действия'];
                    }
                    if (isset($params['Время действия'])){
                        $new_params['time'] = $params['Время действия'];
                    }
                    if (isset($params['Сюжетные ходы'])){
                        $new_params['story_m'] = $params['Сюжетные ходы'];
                    }
                    if (isset($params['Линейность сюжета'])){
                        $new_params['story_l'] = $params['Линейность сюжета'];
                    }
                    if (isset($params['Возраст читателя'])){
                        $new_params['age'] = $params['Возраст читателя'];
                    }
                    try {
                        DB::transaction(function () use ($new_params, $book){
                            $book->params = json_encode($new_params);
                            $book->save();
                        });
                    }catch (\Exception $exception){
                        dd($exception->getMessage());
                    }
                    echo $book->id.' - [OK]'."\n";
                }
            }
        });
        echo 'COMPLETE'."\n";
        return 0;
    }
}
