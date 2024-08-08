<?php

namespace Database\Factories;

use App\Models\HolidayPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participant>
 */
class ParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $holidayPlans = HolidayPlan::pluck('id');
        return [
            'name' => fake()->name(),
            'holiday_plan_id' => fake()->randomElement($holidayPlans)
        ];
    }
}
