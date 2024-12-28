<?php

namespace Database\Seeders;

use App\Models\Patient;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 0; $i < 5000; $i++) {
            $customArr = [
                // 'image' => $faker->imageUrl(200, 200, 'people'), // Fake profile image
                'created_by' => rand(1,4),
                'title' => $faker->title, // Mr., Ms., Dr., etc.
                'name' => $faker->name,
                'gender' => $faker->randomElement(['Male', 'Female', 'Other']),
                'age' => $faker->numberBetween(1, 100),
                'dob' => $faker->date('Y-m-d'),
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'phone2' => $faker->phoneNumber,
                'address' => $faker->address,
                'city' => $faker->city,
                'pincode' => $faker->postcode,
                'blood_group' => $faker->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
                'referred_by_title' => $faker->title,
                'referred_by_name' => $faker->name,
                'referred_by_speciality' => rand(1,7),
                'language_id' => rand(1,5),
            ];
            // Insert into the database (adjust table name if needed)
            Patient::create($customArr);
        }
    }
}
