<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence;
        $slug = Str::slug($title);
        return [
            'title' => $title,
            'slug' => $slug,
            'description' => $this->faker->paragraph(10),
            'keywords' => implode(",", $this->faker->randomElements(['pakistan', 'international', 'sports', 'finance', 'entertainment'], 2)),
            'image' => $this->faker->image( 'storage/app/' . config('filepaths.pageImagePath.internal_path'), 640, 480, 'cats', false),
            'published_at' => $this->faker->randomElement([null, now()]),
            'start_datetime' => null,
            'end_datetime' => null,
            'created_by' => User::all()->random()->id,
            'modified_by' => User::all()->random()->id,
            'active' => $this->faker->randomElement(['0','1']),
        ];
    }
}
