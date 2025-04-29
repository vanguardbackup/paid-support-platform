<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'timezone' => 'UTC',
            'credit_balance' => 0,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model is a staff member.
     */
    public function staff(): static
    {
        return $this->state(fn (array $attributes) => [
            'staff_at' => now(),
        ]);
    }

    public function positiveCredits(): static
    {
        return $this->state(fn (array $attributes) => [
            'credit_balance' => '100',
        ]);
    }

    public function negativeCredits(): static
    {
        return $this->state(fn (array $attributes) => [
            'credit_balance' => '-100',
        ]);
    }
}
