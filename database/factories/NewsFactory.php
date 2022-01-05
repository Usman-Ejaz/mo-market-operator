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
            'keywords' => implode(",", $this->faker->randomElements(['pakistan', 'international', 'sports', 'finance', 'entertainment'], 2)),
            'image' => $this->faker->image('storage/uploads', 640, 480, 'cats', false),
            'start_datetime' => '20/01/2021 23:10:01',
            'end_datetime' => '30/01/2021 23:10:01',
            'newscategory_id' => $this->faker->numberBetween(1,3),
            'created_by' => $this->faker->numberBetween(1,5),
            'modified_by' => $this->faker->numberBetween(1,5),
            'active' => $this->faker->randomElement(['0','1']),
        ];
    }
}
