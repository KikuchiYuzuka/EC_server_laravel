<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(20),
            'description' => $this->faker->text(1000),
            'image' => $this->faker->imageUrl(),
            'price' => $this->faker->numberBetween(10, 100)
        ];
    }
}
