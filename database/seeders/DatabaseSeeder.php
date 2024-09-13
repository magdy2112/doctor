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
            ['specialization' => 'Allergy and Immunology'],
            ['specialization' => 'Anesthesiology'],
            ['specialization' => 'Dermatology'],
            ['specialization' => 'Diagnostic Radiology'],
            ['specialization' => 'Emergency Medicine'],
            ['specialization' => 'Family Medicine'],
            ['specialization' => 'Internal Medicine'],
            ['specialization' => 'Medical Genetics'],
            ['specialization' => 'Neurology'],
            ['specialization' => 'Nuclear Medicine'],
            ['specialization' => 'Obstetrics and Gynecology'],
            ['specialization' => 'Ophthalmology'],
            ['specialization' => 'Pathology'],
            ['specialization' => 'Pediatrics'],
            ['specialization' => 'Physical Medicine and Rehabilitation'],
            ['specialization' => 'Preventive Medicine'],
            ['specialization' => 'Psychiatry'],
            ['specialization' => 'Radiation Oncology'],
            ['specialization' => 'Surgery'],
            ['specialization' => 'Urology'],
            ['specialization' => 'Cardiology'],
            ['specialization' => 'Endocrinology'],
            ['specialization' => 'Gastroenterology'],
            ['specialization' => 'Hematology'],
            ['specialization' => 'Infectious Disease'],
            ['specialization' => 'Nephrology'],
            ['specialization' => 'Oncology'],
            ['specialization' => 'Pulmonology'],
            ['specialization' => 'Rheumatology'],
            ['specialization' => 'Geriatrics'],
            ['specialization' => 'Palliative Care'],
            ['specialization' => 'Sports Medicine'],
            ['specialization' => 'Critical Care Medicine'],
            ['specialization' => 'Clinical Neurophysiology'],
            ['specialization' => 'Vascular Medicine'],
            ['specialization' => 'Sleep Medicine'],
            ['specialization' => 'Pain Medicine'],
            ['specialization' => 'Addiction Medicine'],
            ['specialization' => 'Hospitalist'],
            ['specialization' => 'Interventional Radiology']
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
