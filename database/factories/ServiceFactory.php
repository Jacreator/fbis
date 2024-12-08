<?php

namespace Database\Factories;

use App\Enums\ServiceType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word,
            'type' => $this->faker->randomElement(ServiceType::class),
            'description' => $this->faker->sentence,
            'icon' => $this->faker->imageUrl,
            'amount' => $this->faker->numerify(),
            'question_data' => []
        ];
    }
}
