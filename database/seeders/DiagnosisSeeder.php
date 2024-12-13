<?php

namespace Database\Seeders;

use App\Models\Diagnosis;
use Illuminate\Database\Seeder;

class DiagnosisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i <=30 ; $i++) { 
            Diagnosis::create([
                'title' => 'Diagnosis demo -'.$i,
                'user_id' => 2,
            ]);
        }
    }
}
