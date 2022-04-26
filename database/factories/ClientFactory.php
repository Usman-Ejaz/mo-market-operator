<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name();
        $email = $this->faker->unique()->safeEmail();
        $address = $this->faker->address();
        $phone = $this->faker->phoneNumber();

        return [
            'name' => $name,
            'type' => Client::TYPE[$this->faker->numberBetween(0, 1)],
            'address' => $address,
            'categories' => '1,2,3,4',
            'pri_name' => $name,
            'pri_address' => $address,
            'pri_telephone' => $phone,
            'pri_facsimile_telephone' => $phone,
            'pri_email' => $email,
            'pri_signature' => $this->faker->image(config('settings.storage_disk_base_path') . Client::SIGNATURE_DIR, 640, 480, 'cats', false),
            'sec_name' => $name,
            'sec_address' => $address,
            'sec_telephone' => $phone,
            'sec_facsimile_telephone' => $phone,
            'sec_email' => $email,
            'sec_signature' => $this->faker->image(config('settings.storage_disk_base_path') . Client::SIGNATURE_DIR, 640, 480, 'cats', false),
        ];
    }
}
