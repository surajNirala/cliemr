<?php

namespace Database\Seeders;

use App\Models\Advice;
use Illuminate\Database\Seeder;

class AdviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i <=20 ; $i++) { 
            Advice::create([
                'title' => 'Advice-'.$i,
                'description' => 'Advice Dummy Data.'.$i,
                'user_id' => 2,
            ]);
        }
    }
}
