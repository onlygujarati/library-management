<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    public function definition()
{
    return [
        'title' => $this->faker->sentence,
        'author_name' => $this->faker->name,
        'status' => 'available',
    ];
}
}