<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatBotKnowledgeBaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'question' => $this->faker->paragraph(3),
            'answer' => $this->faker->paragraph(8),
            'keywords' => implode(",", $this->faker->randomElements(['Personal', 'Information', 'ISMO', 'Electricity', 'Government', 'Punjab'], 2)),
            'created_by' => User::all()->random()->id,
            'modified_by' => User::all()->random()->id,
            'published_at' => $this->faker->randomElement([null, now()])
        ];
    }
}
