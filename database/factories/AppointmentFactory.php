<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Carbon\Carbon;
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





        $startHour = $this->faker->numberBetween(8, 22); // assuming you want hours between 8am and 10pm
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $numberOfDaysInMonth = Carbon::createFromDate($currentYear, $currentMonth, 1)->endOfMonth()->day;
        $day = $this->faker->numberBetween(1, $numberOfDaysInMonth); // assuming you want hours between 8am and 10pm

        return [
            'doctor_id' => Doctor::all()->random()->id, // assuming you have 10 doctors
            'date' => Carbon::createFromDate($currentYear, $currentMonth, $day)->format('Y-m-d'),
            'start_time' => Carbon::createFromFormat('H', $startHour)->format('H'),
            'end_time' => Carbon::createFromFormat('H', $startHour + $this->faker->numberBetween(1, 4))->format('H'),
              'status' => $this->faker->randomElement(['active', 'completed','cancelled']),
        ];
    }
}
