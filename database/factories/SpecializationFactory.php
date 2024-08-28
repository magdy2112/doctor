<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialization>
 */
class SpecializationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'specialization' => fake()->randomElement(['Cardiologist', 'Dentist', 'Surgeon', 'Radiologist', 'Neurologist', 'Dermatologist', 'ENT Specialist

', 'Hematologist', 'Psychiatrist', 'Audiologist']),
            'created_at' => $this->faker->dateTime,
       
        ];
    }
}
