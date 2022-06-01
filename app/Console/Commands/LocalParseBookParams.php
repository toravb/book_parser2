<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LocalParseBookParams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:params';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Local parse params of book for change keys';

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
        Book::query()->chunk(1000, function ($books){
            foreach ($books as $book){
                if ($book->params){
                    $params = json_decode($book->params, true);
                    $new_params = [];

                    if (isset($params['Серия:'])){
                        $new_params['series'] = $params['Серия:'];
                    }
                    if (isset($params['Автор:'])){
                        $new_params['author'] = $params['Автор:'];
                    }
                    if (isset($params['Название:'])){
                        $new_params['title'] = $params['Название:'];
                    }
                    if (isset($params['Издательство:'])){
                        $new_params['publisher'] = $params['Издательство:'];
                    }
                    if (isset($params['Год:'])){
                        $new_params['year'] = $params['Год:'];
                    }
                    if (isset($params['ISBN:'])){
                        $new_params['isbn'] = $params['ISBN:'];
                    }
                    if (isset($params['Страниц:'])){
                        $new_params['pages'] = $params['Страниц:'];
                    }
                    if (isset($params['Тираж:'])){
                        $new_params['count'] = $params['Тираж:'];
                    }
                    if (isset($params['Формат:'])){
                        $new_params['format'] = $params['Формат:'];
                    }
                    if (isset($params['Перевод книги:'])){
                        $new_params['translator'] = $params['Перевод книги:'];
                    }
                    if (isset($params['Язык:'])){
                        $new_params['language'] = $params['Язык:'];
                    }
                    if (isset($params['Жанр:'])){
                        $new_params['genre'] = $params['Жанр:'];
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
