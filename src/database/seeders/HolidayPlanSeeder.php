<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HolidayPlan;

class HolidayPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HolidayPlan::factory(30)->create();
    }
}
