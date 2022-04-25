<?php

namespace Database\Factories;

use App\Models\ContactPageQuery;
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
            "name" => $this->faker->name(),
            "email" => $this->faker->safeEmail(),
            "subject" => $this->faker->realText(40),
            "message" => $this->faker->realText(700),
            "status" => $this->faker->randomElement(ContactPageQuery::STATUS_ENUMS),
            "comments" => null,
        ];
    }
}
