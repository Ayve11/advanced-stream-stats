<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubscriptionPlan::create([
            'type' => SubscriptionPlan::TYPE_DAILY,
            'price' => 100, 
            'duration_in_days' => 1,
        ]);

        SubscriptionPlan::create([
            'type' => SubscriptionPlan::TYPE_MONTHLY,
            'price' => 1500, 
            'duration_in_days' => 30,
        ]);

        SubscriptionPlan::create([
            'type' => SubscriptionPlan::TYPE_YEARLY,
            'price' => 12000, 
            'duration_in_days' => 365,
        ]);
    }
}
