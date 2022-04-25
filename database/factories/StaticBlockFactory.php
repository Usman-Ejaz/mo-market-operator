<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StaticBlockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name();
        
        return [
            'name' => $name,
            'identifier' => Str::slug($name),
            'contents' => $this->faker->realText(1000)
        ];
    }
}
