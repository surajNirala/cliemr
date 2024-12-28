<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
 
        $customArr = [
            [
                'name'=> 'Hindi',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'English',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Gujrati',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Urdu',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Marathi',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
        ];
        Language::insert($customArr);
    }
}
