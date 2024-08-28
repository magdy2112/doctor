<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = $this->faker;

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $day = $faker->randomElement($days);

        $startTime = $faker->dateTimeBetween('09:00', '20:00');
        $endTime = $faker->dateTimeBetween($startTime, '23:00:00');


        return [

            'doctor_id'=>Doctor::all()->random()->id,
            'user_id'=>user::all()->random()->id,
            // 'start_time' => $startTime->format('D, H:i'),
            'start_time' => $day . ', ' . $startTime->format('H:i:s'),
            'end_time' => $day . ', ' . $endTime->format('H:i:s'),

            // 'start_time' => $startTimeFormatted,
            // 'end_time' => $endTimeFormatted,
            'status' => 'pending',
            'notes' => $faker->paragraph,
        ];
    }
}
