<?php

namespace Database\Factories;

use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'keywords' => implode(",", $this->faker->randomElements(['pakistan', 'international', 'sports', 'finance', 'entertainment'], 2)),
            'file' => '',
            'published_at' => $this->faker->randomElement([null, now()]),
            'category_id' => DocumentCategory::all()->random()->id,
            'created_by' => User::all()->random()->id,
            'modified_by' => User::all()->random()->id,
        ];
    }
}
