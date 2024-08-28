<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
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
      'id' => $this->faker->unique()->numberBetween(1, 100),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => $this->faker->dateTime,
            'password' => Hash::make('password'),
            'photo' => $this->faker->optional()->word,
            'phone' => $this->faker->optional()->phoneNumber,
            'address' => $this->faker->optional()->address,
            'age' => $this->faker->optional()->numberBetween(18, 65),
            'city' => $this->faker->optional()->city,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'remember_token' => $this->faker->uuid,
            'created_at' => $this->faker->dateTime,

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
}
