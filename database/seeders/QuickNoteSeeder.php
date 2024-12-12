<?php

namespace Database\Seeders;

use App\Models\QuickNote;
use Illuminate\Database\Seeder;

class QuickNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i <=20 ; $i++) { 
            QuickNote::create([
                'title' => 'DM Instructions-'.$i,
                'description' => 'Dawa, Parhej, Excercise etc.'.$i,
                'user_id' => 2,
            ]);
        }
    }
}
