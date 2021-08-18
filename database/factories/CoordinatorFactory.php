<?php

namespace Database\Factories;

use Hash;
use Str;
use App\Models\Coordinator;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoordinatorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Coordinator::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'department_id' => 1,
            'coordinator_fname' => $this->faker->firstName,
            'coordinator_lname' => $this->faker->lastName,
            'coordinator_email' => $this->faker->unique()->safeEmail,
            'coordinator_password' => Hash::make('password'),
            'coordinator_gender' => $this->faker->randomElement(['male' ,'female', 'other']),
            'coordinator_contact' => $this->faker->numerify('###########'),
            'coordinator_position' => 'Teacher II',
            'coordinator_link' => '/avatar.jpg',
            'coordinator_state' => 1,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at'=> now(),
        ];
    }
}