<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create([
            'service' => 'Consultation',
            'unit_price' => 1000,
            'created_by' => 2,
        ]);
        Service::create([
            'service' => 'Consultation with in week',
            'unit_price' => 1000,
            'created_by' => 2,
        ]);
    }
}
