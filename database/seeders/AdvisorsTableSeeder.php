<?php

namespace Database\Seeders;

use App\Models\Advisor;
use Illuminate\Database\Seeder;

class AdvisorsTableSeeder extends Seeder
{
    public function run()
    {
        // Use the factory to create 10 advisors
        Advisor::factory()->count(10)->create(); // This is the correct method
        
    }
}
