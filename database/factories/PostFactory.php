<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $users = User::pluck('id')->toArray();
        return [
            "heading" => fake()->sentence(3),
            "subheading" => fake()->sentence(2),
            "body" => fake()->text(300),
            "user" => fake()->randomElement($users),
        ];
    }
}
