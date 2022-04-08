<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'identifier' => Str::slug($this->faker->name()),
            'submenu_json' => null,
            'active' => $this->faker->randomElement(['0','1']),
        ];
    }
}
