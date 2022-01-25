<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'role_id' => Role::factory()->create()->id,
            'name' => $this->faker->userName,
            'capability' => $this->faker->userName,
        ];
    }
}
