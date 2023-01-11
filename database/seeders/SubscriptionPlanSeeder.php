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
            'price' => 5, 
            'duration_in_days' => 1,
            'payment_type' => SubscriptionPlan::PAYMENT_TYPE_ONE_TIME
        ]);

        SubscriptionPlan::create([
            'type' => SubscriptionPlan::TYPE_MONTHLY,
            'price' => 15, 
            'duration_in_days' => 30,
            'payment_type' => SubscriptionPlan::PAYMENT_TYPE_SUBSCRIPTION,
            'plan_id' => env("BRAINTREE_SUBSCRIPTION_MONTHLY_PLAN_ID")
        ]);

        SubscriptionPlan::create([
            'type' => SubscriptionPlan::TYPE_YEARLY,
            'price' => 120, 
            'duration_in_days' => 365,
            'payment_type' => SubscriptionPlan::PAYMENT_TYPE_SUBSCRIPTION,
            'plan_id' => env("BRAINTREE_SUBSCRIPTION_YEARLY_PLAN_ID")
        ]);
    }
}
