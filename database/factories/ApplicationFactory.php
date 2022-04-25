<?php

namespace Database\Factories;

use App\Models\Job;
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
            'address' => $this->faker->address(),
            'city' => implode(",", $this->faker->randomElements(['Lahore', 'Karachi', 'Islamabad', 'Quetta', 'Peshawar'], 1)),
            'experience' => implode(",", $this->faker->randomElements(['2 years', '3 years', '4 years', '5 years', '6 years'], 1)),
            'degree_level' => $this->faker->realText(20),
            'degree_title' => $this->faker->realText(20),
            'resume' => '',
            'job_id' => Job::all()->random()->id,
        ];
    }
}