<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\Student;
use App\Models\Advisor;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Assignment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'student_id' => Student::factory(), // Associate a random student
            'advisor_id' => Advisor::factory(), // Associate a random advisor
            'assigned_at' => $this->faker->dateTimeThisYear(), // Random timestamp for the assignment
        ];
    }
}
