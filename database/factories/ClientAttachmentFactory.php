<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ClientAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientAttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $client = Client::all()->random();
        $categories = explode(",", $client->categories);
        $randomCategory = array_rand($categories, 1);

        return [
            'client_id' => $client->id,
            'category_id' => $this->faker->randomElement([$categories[$randomCategory], null]),
            'phrase' => $this->faker->paragraph(2),
            'file' => $this->faker->image(config('settings.storage_disk_base_path') . ClientAttachment::DIR, 640, 480, 'cats', false),
        ];
    }
}
