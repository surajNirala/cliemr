<?php

namespace Database\Seeders;

use App\Models\MedicineType;
use Illuminate\Database\Seeder;

class MedicineTypeSeeder extends Seeder
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
                'name'=> 'TAB.',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'SYB.',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'CRM.',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'POW.',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'INJ.',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'CAP.',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'DRP.',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'SUS.',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'LIQ.',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'SAC.',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'EXP.',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'OIN.',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],

        ];
        MedicineType::insert($customArr);
    }
}
