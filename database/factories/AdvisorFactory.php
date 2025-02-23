<?php

namespace Database\Factories;

use App\Models\Advisor;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvisorFactory extends Factory
{
    protected $model = Advisor::class;

    public function definition()
    {
        // Fetch all existing Program IDs from the database
        $programIds = Program::pluck('id')->toArray();

        // Randomly pick a program_id from the list of existing Program IDs
        $programId = $this->faker->randomElement($programIds);

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => $this->faker->phoneNumber,
            'password' => bcrypt('password'), // Default password
            'metric_number' => $this->faker->unique()->numerify('MET-#####'),
            'max_students' => $this->faker->numberBetween(10, 50),
            'male_count' => $this->faker->numberBetween(0, 20),
            'female_count' => $this->faker->numberBetween(0, 20),
            'program_id' => $programId, // Use an existing Program ID from the database
            'profile_image' => $this->faker->imageUrl(),
        ];
    }
}
