<?php

namespace Database\Factories;

use App\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $types = ProductType::pluck('id')->toArray();
        return [
            "title" => fake()->words(4, true),
            "description" => fake()->words(10, true),
            "sleeve_condition" => fake()->words(1, true),
            "media_condition" => fake()->words(1, true),
            "sku" => fake()->words(1, true),
            "price" => fake()->numberBetween(15, 9999),
            "rating" => fake()->randomFloat(2, 0, 5),
            "product_type" => fake()->randomElement($types),
            "number_of_ratings" => fake()->numberBetween(0, 100),
        ];
    }
}
