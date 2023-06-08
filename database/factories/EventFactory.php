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
        $event_start = Carbon::now()->month(6)->day(rand(1, 31))->hour(rand(9, 16))->minute(0)->second(0);
        $event_start_max_hours= 17 - $event_start->hour;
        $event_start_max_minutes = ($event_start_max_hours* 60) - $event_start->minute;
        $event_start_max_seconds = ($event_start_max_minutes * 60) - $event_start->second;
        $event_end = $event_start->copy()->addSeconds(rand(0, $event_start_max_seconds));

        return [
            'employee_id' => fake()->numberBetween(1, 15),
            'event_type' => fake()->randomElement(['shift', 'leave']),
            'event_start' => $event_start,
            'event_end' => $event_end,
            'called_in_sick' => fake()->boolean(),
        ];
    }
}
