<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StaticBlockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'contents' => $this->faker->sentence(10)
        ];
    }
}
