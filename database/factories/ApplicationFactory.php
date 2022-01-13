<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'gender' => implode(",", $this->faker->randomElements(['male', 'female', 'other'], 1)),
            'phone' => implode(",", $this->faker->randomElements(['921547825', '921547826', '921547827', '921547828', '921547829'], 1)),
            'address' => $this->faker->paragraph(10),
            'city' => implode(",", $this->faker->randomElements(['Lahore', 'Karachi', 'Islamabad', 'Quetta', 'Peshawar'], 1)),
            'experience' => implode(",", $this->faker->randomElements(['2 years', '3 years', '4 years', '5 years', '6 years'], 1)),
            'degree_level' => $this->faker->paragraph(8),
            'degree_title' => $this->faker->paragraph(10),
            'resume' => '',
            'job_id' => $this->faker->randomElement(['11','12','13','17']),
        ];
    }
}