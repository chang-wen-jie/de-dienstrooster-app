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
        $shift_start = Carbon::now()->month(3)->day(rand(1, 28))->hour(rand(9, 16))->minute(0)->second(0);
        $max_shift_duration = 17 - $shift_start->hour;
        $max_shift_minutes = ($max_shift_duration * 60) - $shift_start->minute;
        $max_shift_seconds = ($max_shift_minutes * 60) - $shift_start->second;
        $shift_end = $shift_start->copy()->addSeconds(rand(0, $max_shift_seconds));

        return [
            'user_id' => fake()->unique()->numberBetween(1, 15),
            'on_duty' => fake()->boolean(),
            'start' => $shift_start,
            'shift_end' => $shift_end,
            'sick' => fake()->boolean(),
        ];
    }
}
