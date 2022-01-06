<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Image;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Book::factory()
            ->count(20)
            ->has(Author::factory()->count(20))
            ->has(Image::factory()->count(20))
            ->create()
            ->each(function ($book){
                $user = User::factory()->make();
                $book->rates()->attach($user->id, ['rating' => rand(1, 5)]);

            })
        ;

    }
}
