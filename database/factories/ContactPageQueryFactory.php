<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContactPageQueryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->name,
            "email" => $this->faker->email,
            "subject" => $this->faker->sentence(10),
            "message" => $this->faker->sentence(100),
            "status" => $this->faker->randomElement(["pending", "inprocess", "resolved"])
        ];
    }
}
