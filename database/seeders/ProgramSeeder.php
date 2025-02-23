<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;

class ProgramSeeder extends Seeder
{
    public function run()
    {
        // Bachelor Programs
        Program::create([
            'name' => 'Electronic Engineering',
            'type' => 'Bachelor',
        ]);

        Program::create([
            'name' => 'Microelectronic Engineering',
            'type' => 'Bachelor',
        ]);

        Program::create([
            'name' => 'Computer Engineering',
            'type' => 'Bachelor',
        ]);

        Program::create([
            'name' => 'Biomedical Electronic Engineering',
            'type' => 'Bachelor',
        ]);

        Program::create([
            'name' => 'Electronic Engineering Technology (Electronic System)',
            'type' => 'Bachelor',
        ]);

        Program::create([
            'name' => 'Electronic Engineering Technology (Telecommunication Design)',
            'type' => 'Bachelor',
        ]);

        Program::create([
            'name' => 'Electronic Engineering (Electronic Network Design)',
            'type' => 'Bachelor',
        ]);

        Program::create([
            'name' => 'Technology In Industrial Electronic Automation',
            'type' => 'Bachelor',
        ]);

        //Diploma Programs 
        Program::create([
            'name' => 'Electronic Engineering',
            'type' => 'Diploma',
        ]);

        Program::create([
            'name' => 'Computer Engineering',
            'type' => 'Diploma',
        ]);

    }
}
