<?php

namespace Database\Factories;

use App\Models\FaqCategory;
use App\Models\User;
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
            'question' => $this->faker->realText(50),
            'answer' => $this->faker->realText(700),
            'created_by' => User::all()->random()->id,
            'modified_by' => User::all()->random()->id,
            'category_id' => FaqCategory::all()->random()->id,
            'published_at' => $this->faker->randomElement([null, now()]),
            'active' => $this->faker->randomElement(['0','1']),
        ];
    }
}
