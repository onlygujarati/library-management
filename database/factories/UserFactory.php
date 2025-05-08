<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => null,
            'password' => bcrypt('password'),
            'user_type' => 'user',
            'status' => 'active',
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            if ($user->user_type) {
                $user->assignRole($user->user_type);
            }
        });
    }
}
