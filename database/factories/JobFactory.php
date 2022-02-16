<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobFactory extends Factory
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
            'slug' => Str::slug($this->faker->name()),
            'description' => $this->faker->paragraph(10),
            'location' => implode(",", $this->faker->randomElements(['Lahore', 'Karachi', 'Islamabad', 'Quetta', 'Peshawar'], 2)),
            'experience' => implode(",", $this->faker->randomElements(['2 years', '3 years', '4 years', '5 years', '6 years'], 1)),
            'qualification' => $this->faker->name(),
            'total_positions' => $this->faker->numberBetween(1,5),
            'image' => $this->faker->image( 'storage/app/' . config('filepaths.jobImagePath.internal_path'), 640, 480, 'cats', false),
            'start_datetime' => null,
            'end_datetime' => null,
            'created_by' => User::all()->random()->id,
            'modified_by' => User::all()->random()->id,
            'active' => $this->faker->randomElement(['0','1']),
            'enable' => $this->faker->randomElement(['0','1']),
        ];
    }
}
