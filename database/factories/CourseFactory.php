<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'content' => $this->faker->paragraph(),
            'cover' => $this->faker->imageUrl(),
            'duration' => $this->faker->randomFloat(2, 1, 10), 
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced']),
            'category_id' => Category::factory(), 
            'user_id' => User::factory(), 
        ];
    }
}
