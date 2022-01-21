<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
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
        $title = $this->faker->sentence;
        $slug = str_replace(' ', '-', $title);

        return [
            'title' => $title,
            'slug' => $slug,
            'description' => $this->faker->paragraph(10),
            'keywords' => implode( ",", $this->faker->randomElements(['pakistan', 'international', 'sports', 'finance', 'entertainment'], 2) ),
            'image' => $this->faker->image( 'public/' . config('filepaths.newsImagePath.public_path'), 640, 480, 'cats', false),
            'start_datetime' => null,
            'end_datetime' => null,
            'news_category' => $this->faker->numberBetween(1,3),
            'created_by' => User::all()->random()->id,
            'modified_by' => User::all()->random()->id,
            'active' => $this->faker->randomElement(['0','1']),
        ];
    }
}
