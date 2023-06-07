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
        $start = Carbon::now()->month(6)->day(rand(1, 31))->hour(rand(9, 16))->minute(0)->second(0);
        $start_max_hours= 17 - $start->hour;
        $start_max_minutes = ($start_max_hours* 60) - $start->minute;
        $start_max_seconds = ($start_max_minutes * 60) - $start->second;
        $end = $start->copy()->addSeconds(rand(0, $start_max_seconds));

        return [
            'employee_id' => fake()->numberBetween(1, 15),
            'event_type' => fake()->randomElement(['shift', 'leave']),
            'start' => $start,
            'end' => $end,
            'called_in_sick' => fake()->boolean(),
        ];
    }
}
