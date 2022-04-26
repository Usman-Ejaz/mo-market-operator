<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SliderSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'transition' => $this->faker->randomElement([1, 2, 3, 4]),
            'speed' => $this->faker->randomElement([100, 200, 300, 400, 500, 600, 700, 800, 900, 1000])
        ];
    }
}
