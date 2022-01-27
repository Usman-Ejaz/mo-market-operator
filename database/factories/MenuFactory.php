<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => implode(" ", $this->faker->words(2)),
            'theme' => $this->faker->randomKey(config('settings.themes')),
            'submenu_json' => null,
            'active' => $this->faker->randomElement(['0','1']),
        ];
    }
}
