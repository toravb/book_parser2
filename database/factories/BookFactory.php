<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'=>$this->faker->title(),
            'text'=>$this->faker->text(200),
            'link'=>$this->faker->text(10),
            'active'=>$this->faker->boolean(80),
            'fixed'=>$this->faker->boolean(80),
            'count_pages'=>$this->faker->randomDigitNotNull(),
            'params'=> '{}',

        ];
    }
}
