<?php

namespace Database\Factories;

use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupportRequest>
 */
class SupportRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'title' => fake()->sentence(),
            'category' => fake()->randomElement(['technical', 'installation']),
            'preferred_assistance_type' => fake()->randomElement(['zoom', 'teams', 'phone', 'chat', 'email']),
            'preferred_date' => fake()->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'preferred_time' => fake()->time('H:i'),
            'timezone' => fake()->randomElement(['UTC+07:00', 'UTC+08:00', 'UTC+09:00']),
            'additional_details' => fake()->paragraph(),
        ];
    }
}
