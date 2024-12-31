<?php

namespace Database\Seeders;

use App\Models\Bill;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 0; $i < 50; $i++) {
            $customArr = [
                'created_by' => rand(1,4),
                'patient_id' => rand(1,10),
                'invoice' =>  date('Ymdhis') . rand(1000, 9999),
                'mode' => 'case',
                'service' => $faker->name,
                'unit_price' => 1000,
                'paid' => 1000,
                'discount' => 0,
            ];
            // Insert into the database (adjust table name if needed)
            Bill::create($customArr);
        }
    }
}
