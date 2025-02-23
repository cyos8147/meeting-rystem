<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgramFactory extends Factory
{
    protected $model = Program::class;

    public function definition()
    {
        // Define the list of programs with their types
        $programs = [
            ['name' => 'Electronic Engineering', 'type' => 'Bachelor'],
            ['name' => 'Microelectronic Engineering', 'type' => 'Bachelor'],
            ['name' => 'Computer Engineering', 'type' => 'Bachelor'],
            ['name' => 'Biomedical Electronic Engineering', 'type' => 'Bachelor'],
            ['name' => 'Electronic Engineering Technology (Electronic System)', 'type' => 'Bachelor'],
            ['name' => 'Electronic Engineering Technology (Telecommunication Design)', 'type' => 'Bachelor'],
            ['name' => 'Electronic Engineering (Electronic Network Design)', 'type' => 'Bachelor'],
            ['name' => 'Technology In Industrial Electronic Automation', 'type' => 'Bachelor'],
            ['name' => 'Electronic Engineering', 'type' => 'Diploma'],
            ['name' => 'Computer Engineering', 'type' => 'Diploma'],
        ];

        // Randomly pick a program from the predefined list
        $program = $this->faker->randomElement($programs);

        return [
            'name' => $program['name'], // Use the selected program's name
            'type' => $program['type'], // Use the selected program's type
        ];
    }
}
