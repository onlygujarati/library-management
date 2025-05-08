<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'author_name' => $this->faker->name,
            'status' => 'available', // or use a random value if needed
        ];
    }
}
