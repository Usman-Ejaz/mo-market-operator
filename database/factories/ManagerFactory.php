<?php

namespace Database\Factories;

use App\Models\Manager;
use Illuminate\Database\Eloquent\Factories\Factory;

class ManagerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $basePath = config('settings.storage_disk_base_path');

        if (!is_dir($basePath . Manager::STORAGE_DIRECTORY)) {
            mkdir($basePath . Manager::STORAGE_DIRECTORY, 0777, true);
        }

        return [
            'name' => $this->faker->name(),
            'designation' => $this->faker->randomElement(['manager', 'employee']),
            'description' => '<p>' . $this->faker->realText(1000) . '</p>',
            'order' => $this->faker->randomElement([1, 2, 3, 4, 5, 6]),
            'image' => $this->faker->image($basePath . Manager::STORAGE_DIRECTORY, 640, 480, 'cats', false),
        ];
    }
}
