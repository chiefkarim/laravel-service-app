<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create(['name' => 'Web Development']);
        Service::create(['name' => 'Mobile App Development']);
        Service::create(['name' => 'UI/UX Design']);
        Service::create(['name' => 'Digital Marketing']);
    }
}
