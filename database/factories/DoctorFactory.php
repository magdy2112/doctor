<?php

namespace Database\Factories;
use App\Models\Specialization;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => $this->faker->optional()->dateTime,
            'password' => Hash::make('password'),
            'photo' => $this->faker->optional()->word,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'age' => $this->faker->numberBetween(18, 65),
            'experience' => $this->faker->numberBetween(5,30),

            'qualification' => fake()->randomElement(['Specialist','Consultant ','Professor ']),

            'description' => $this->faker->optional()->paragraph,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'specialization' => $this->faker->randomElement(['Cardiologist', 'Dentist', 'Surgeon', 'Radiologist', 'Neurologist', 'Dermatologist', 'ENT Specialist

            ', 'Hematologist', 'Psychiatrist', 'Audiologist']),
            'remember_token' => Str::random(10),
            'created_at' => $this->faker->dateTime,

        ];
    }
}
