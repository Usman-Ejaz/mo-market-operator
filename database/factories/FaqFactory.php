<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FaqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'question' => $this->faker->paragraph(3),
            'answer' => $this->faker->paragraph(8),
            'created_by' => $this->faker->numberBetween(1,5),
            'modified_by' => $this->faker->numberBetween(1,5),
            'active' => $this->faker->randomElement(['0','1']),
        ];
    }
}
