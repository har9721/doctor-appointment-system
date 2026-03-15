<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patients;
use App\Models\PatientsEmergencyContacts;
use App\Models\PatientsMedicalHistory;
use App\Models\PatientsLifeStyleInformation;
use App\Models\SmokingStatus;
use App\Models\AlcoholStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get available smoking and alcohol statuses
        $smokingStatuses = SmokingStatus::where('isActive', 1)->pluck('id')->toArray();
        $alcoholStatuses = AlcoholStatus::where('isActive', 1)->pluck('id')->toArray();

        // Create 10 fake patients
        for ($i = 1; $i <= 10; $i++) {
            // Create a user with patient role
            $user = User::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('12345678'),
                'mobile' => fake()->unique()->numerify('##########'),
                'age' => fake()->numberBetween(18, 70),
                'address' => fake()->address(),
                'gender_ID' => fake()->numberBetween(1, 2),
                'city_ID' => fake()->numberBetween(1, 50), // Adjust based on your cities count
                'role_ID' => 3, // Patient role
                'isActive' => 1,
                'isDeleted' => 0,
                'created_at' => now(),
            ]);

            // Create patient record
            $patient = Patients::create([
                'user_ID' => $user->id,
                'isActive' => 1,
                'isDeleted' => 0,
                'created_at' => now(),
            ]);

            // Create emergency contact
            PatientsEmergencyContacts::create([
                'patient_ID' => $patient->id,
                'contact_name' => fake()->name(),
                'contact_relation' => fake()->randomElement(['Father', 'Mother', 'Brother', 'Sister', 'Spouse', 'Friend']),
                'phone_no' => fake()->numerify('##########'),
                'createdBy' => $patient->id,
            ]);

            // Create medical history
            PatientsMedicalHistory::create([
                'patient_ID' => $patient->id,
                'illness' => fake()->randomElement(['Diabetes', 'Hypertension', 'None', 'Asthma', 'Thyroid']),
                'surgery' => fake()->randomElement(['Appendix', 'None', 'Hernia', 'Cataract']),
                'allergies' => fake()->randomElement(['Penicillin', 'None', 'Peanuts', 'Shellfish']),
                'chronicDisease' => fake()->randomElement(['None', 'Diabetes', 'Hypertension', 'COPD']),
                'medication' => fake()->randomElement(['Aspirin', 'Metformin', 'Lisinopril', 'None']),
                'createdBy' => $patient->id,
            ]);

            // Create lifestyle information
            PatientsLifeStyleInformation::create([
                'patient_ID' => $patient->id,
                'smokingStatus_ID' => fake()->randomElement($smokingStatuses),
                'alcoholStatus_ID' => fake()->randomElement($alcoholStatuses),
                'exercise' => fake()->randomElement(['Daily', 'Weekly', 'Occasionally', 'Never']),
                'createdBy' => $patient->id,
            ]);

            echo "Patient $i created successfully with ID: {$patient->id}\n";
        }

        $this->command->info('10 fake patients created successfully!');
    }
}
