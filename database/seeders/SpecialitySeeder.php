<?php

namespace Database\Seeders;

use App\Models\Speciality;
use Illuminate\Database\Seeder;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Speciality::create([
            'name' => 'Consulting Physicain',
            'user_id' => 1,
        ]);
        Speciality::create([
            'name' => 'Diabetlogist',
            'user_id' => 1,
        ]);
        Speciality::create([
            'name' => 'Cardiologist',
            'user_id' => 1,
        ]);
        Speciality::create([
            'name' => 'Gastrologist',
            'user_id' => 1,
        ]);
        Speciality::create([
            'name' => 'Nephrologist',
            'user_id' => 1,
        ]);
        Speciality::create([
            'name' => 'Diabetlogist',
            'user_id' => 1,
        ]);
        Speciality::create([
            'name' => 'ENT etc',
            'user_id' => 1,
        ]);
    }
}
