<?php

namespace Database\Factories;

use App\Models\SliderImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class SliderImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'slot_one' => $this->faker->realText(100),
            'slot_two' => $this->faker->realText(100),
            'url' => '',
            'order' => $this->faker->numberBetween(1, 10),
            'image' => $this->faker->image(config('settings.storage_disk_base_path') .  SliderImage::STORAGE_DIRECTORY, 640, 480, 'cats', false)
        ];
    }
}
