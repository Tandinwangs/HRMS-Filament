<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CustomUser;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomUser>
 */

class CustomUserFactory extends Factory
{
    protected $model = CustomUser::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // You can use bcrypt() to hash the password
            'name' => $this->faker->name, // Your custom fields
        ];
    }

    public static function newFactory()
    {
        return CustomUserFactory::new();
    }
}
