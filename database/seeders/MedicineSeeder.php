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
        $customArr = [
            [
                'user_id' => 1,
                'medicine_type_id' => rand(1, 12),
                'name' => 'demo',
                'dosage1' => rand(0, 1),
                'dosage2' => rand(0, 1),
                'dosage3' => rand(0, 1),
                'medicine_administration_id' => rand(1, 16),
                'unit' => rand(1, 12),
                'time' => rand(1, 7),
                'where' => rand(1,17),
                'generic_name' => 'demo generic',
                'frequency' => rand(1, 6),
                'duration' => rand(1, 3),
                'quantity' => 1,
                'notes' => 'demo...',
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'user_id' => 1,
                'medicine_type_id' => rand(1, 12),
                'name' => 'demo2',
                'dosage1' => rand(0, 1),
                'dosage2' => rand(0, 1),
                'dosage3' => rand(0, 1),
                'medicine_administration_id' => rand(1, 16),
                'unit' => rand(1, 12),
                'time' => rand(1, 7),
                'where' => rand(1,17),
                'generic_name' => 'demo generic2',
                'frequency' => rand(1, 6),
                'duration' => rand(1, 3),
                'quantity' => 1,
                'notes' => 'demo2...',
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            
            [
                'user_id' => 1,
                'medicine_type_id' => rand(1, 12),
                'name' => 'demo3',
                'dosage1' => rand(0, 1),
                'dosage2' => rand(0, 1),
                'dosage3' => rand(0, 1),
                'medicine_administration_id' => rand(1, 16),
                'unit' => rand(1, 12),
                'time' => rand(1, 7),
                'where' => rand(1,17),
                'generic_name' => 'demo generic3',
                'frequency' => rand(1, 6),
                'duration' => rand(1, 3),
                'quantity' => 1,
                'notes' => 'demo3...',
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'user_id' => 1,
                'medicine_type_id' => rand(1, 12),
                'name' => 'demo4',
                'dosage1' => rand(0, 1),
                'dosage2' => rand(0, 1),
                'dosage3' => rand(0, 1),
                'medicine_administration_id' => rand(1, 16),
                'unit' => rand(1, 12),
                'time' => rand(1, 7),
                'where' => rand(1,17),
                'generic_name' => 'demo generic4',
                'frequency' => rand(1, 6),
                'duration' => rand(1, 3),
                'quantity' => 1,
                'notes' => 'demo4...',
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'user_id' => 1,
                'medicine_type_id' => rand(1, 12),
                'name' => 'demo5',
                'dosage1' => rand(0, 1),
                'dosage2' => rand(0, 1),
                'dosage3' => rand(0, 1),
                'medicine_administration_id' => rand(1, 16),
                'unit' => rand(1, 12),
                'time' => rand(1, 7),
                'where' => rand(1,17),
                'generic_name' => 'demo generic5',
                'frequency' => rand(1, 6),
                'duration' => rand(1, 3),
                'quantity' => 1,
                'notes' => 'demo5...',
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
        ];

        Medicine::insert($customArr);
    }
}
