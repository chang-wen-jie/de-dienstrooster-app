<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $last_check_in = Carbon::now()->month(2)->day(rand(1, 28))->hour(rand(0, 23))->second(rand(0, 59));
        $last_check_out = $last_check_in->copy()->addHours(rand(1, 24))->addMinutes(rand(0, 59))->addSeconds(rand(0, 59));

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'role_id' => fake()->numberBetween(1, 2),
            'last_check_in' => $last_check_in,
            'last_check_out'=> $last_check_out,
            'present' => fake()->boolean(),
            'active' => fake()->boolean(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
