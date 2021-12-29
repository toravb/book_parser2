<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('book_genres')->insert([
            [
              'name' => 'Боевик'
            ],
            [
                'name' => 'Фенези'
            ],
            [
                'name' => 'Проза'
            ],
            [
                'name' => 'Комедия'
            ],
            [
                'name' => 'История'
            ],

        ]);
    }


}
