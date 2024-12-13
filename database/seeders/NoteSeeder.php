<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i <=30 ; $i++) { 
            Note::create([
                'title' => 'Note demo -'.$i,
                'user_id' => 2,
            ]);
        }
    }
}
