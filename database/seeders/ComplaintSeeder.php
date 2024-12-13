<?php

namespace Database\Seeders;

use App\Models\Complaint;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i <=30 ; $i++) { 
            Complaint::create([
                'title' => 'complaint demo -'.$i,
                'user_id' => 2,
            ]);
        }
    }
}
