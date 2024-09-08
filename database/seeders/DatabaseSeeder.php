<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\SpecializationFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Specialization::factory(10)->create();


        // Create 50 users
        DB::table('qualifications')->insert([
            ['qualification' => 'Specialist'],
            ['qualification' => 'Consultant'],
            ['qualification' => 'Professor'],
        ]);
        DB::table('specializations')->insert([
            ['specialization' => 'Cardiologist'],
            ['specialization' => 'Dentist'],
            ['specialization' => 'Surgeon'],
            ['specialization' => 'Radiologist'],
            ['specialization' => 'Neurologist'],
            ['specialization' => 'Dermatologist'],
            ['specialization' => 'ENT Specialist'],
            ['specialization' => 'Hematologist'],
            ['specialization' => 'Psychiatrist'],
            ['specialization' => 'Audiologist'],
        ]);
        DB::table('cities')->insert([
                ['city' => 'Alexandria Governorate'],
                ['city' => 'Aswan Governorate'],
                ['city' => 'Asyut Governorate'],
                ['city' => 'Beheira Governorate'],
                ['city' => 'Beni Suef Governorate'],
                ['city' => 'Cairo Governorate'],
                ['city' => 'Dakahlia Governorate'],
                ['city' => 'Damietta Governorate'],
                ['city' => 'Faiyum Governorate'],
                ['city' => 'Gharbia Governorate'],
                ['city' => 'Giza Governorate'],
                ['city' => 'Ismailia Governorate'],
                ['city' => 'Kafr El Sheikh Governorate'],
                ['city' => 'Luxor Governorate'],
                ['city' => 'Matruh Governorate'],
                ['city' => 'Minya Governorate'],
                ['city' => 'Monufia Governorate'],
                ['city' => 'New Valley Governorate'],
                ['city' => 'North Sinai Governorate'],
                ['city' => 'Port Said Governorate'],
                ['city' => 'Qalyubia Governorate'],
                ['city' => 'Qena Governorate'],
                ['city' => 'Red Sea Governorate'],
                ['city' => 'Sharqia Governorate'],
                ['city' => 'Sohag Governorate'],
                ['city' => 'South Sinai Governorate'],
                ['city' => 'Suez Governorate'],
            ]);

            Doctor::factory(25)->create();
            user::factory(70)->create();
        }
    }
