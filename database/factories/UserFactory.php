<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fname = $this->faker->firstName;
        $lname = $this->faker->lastName;
        $plainPassword = 'password';

        return [
            'first_name' => $fname,
            'last_name' => $lname,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt($plainPassword),
            'plain_password' => $plainPassword,
            'user_type' => 'user',
            'mat_id' => 'MAT25' . $this->faker->unique()->numerify('#####'),
        ];
    }
}
