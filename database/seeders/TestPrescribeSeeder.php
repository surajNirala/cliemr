<?php

namespace Database\Seeders;

use App\Models\TestPrescribe;
use Illuminate\Database\Seeder;

class TestPrescribeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i <=20 ; $i++) { 
            TestPrescribe::create([
                'title' => 'Test prescribe -'.$i,
                'description' => 'demo Test prescribe - '.$i,
                'user_id' => 2,
            ]);
        }
    }
}
