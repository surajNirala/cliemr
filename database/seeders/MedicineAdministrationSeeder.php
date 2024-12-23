<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicineAdministration;

class MedicineAdministrationSeeder extends Seeder
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
                'name'=> 'Oral-To be swallowed',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Place to tongue',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Oral-self dissolving',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Topical Application',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Transdermal/On Skin',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Intravenous (IV)',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Intramuscular (IM)',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Subcutaneous (SC)',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Intraarterial',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Nasal Application',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Inhalation',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Vaginal Application',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Into Bone',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Eye Application',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Ear Application',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],
            [
                'name'=> 'Rectal Application',
                'user_id' => 1,
                'created_at'=> date("Y-m-d h:i:s"),
                'updated_at'=> date("Y-m-d h:i:s"),
            ],

        ];
        MedicineAdministration::insert($customArr);
    }
}
