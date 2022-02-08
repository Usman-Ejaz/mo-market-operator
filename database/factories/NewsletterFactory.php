<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsletterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subject' => $this->faker->sentence,
            'description' => $this->faker->paragraph(10),
            'created_by' => User::all()->random()->id,
            'modified_by' => User::all()->random()->id,
        ];
    }
}
