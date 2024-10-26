<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Define the Book Factory
        return [
            'book_name' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'book_cover' => 'https://via.placeholder.com/200',
        ];
    }
}