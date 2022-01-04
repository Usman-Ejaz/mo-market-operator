<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name(),
            'slug' => $this->faker->name(),
            'description' => $this->faker->paragraph(10),
            'keywords' => implode(",", $this->faker->randomElements(['pakistan, international, sports, finance, entertainment'], 2)),
            'image' => $this->faker->imageUrl(640, 480, 'cats'),
            'start_datetime' => $this->faker->dateTime('next sunday', 'next wednesday'),
            'end_datetime' => $this->faker->dateTime('+1 week', '+1 month'),
            'newscategory_id' => $this->faker->numberBetween(1,3),
            'created_by' => $this->faker->numberBetween(1,5),
            'modified_by' => $this->faker->numberBetween(1,5),
            'active' => $this->faker->randomElement(0,1),
        ];
    }
}
