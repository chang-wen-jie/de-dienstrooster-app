<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'start' => Carbon::now()->month(2)->day(rand(1, 28))->hour(rand(0, 23))->second(rand(0, 59)),
            'employed' => fake()->boolean(),
            'in_office' => fake()->boolean(),
        ];
    }
}
