<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i <=30 ; $i++) { 
            Medicine::create([
                'title' => 'Medicine -'.$i,
                'description' => 'medicine description.'.$i,
                'user_id' => 2,
            ]);
        }
    }
}
