<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{
    public function show(Request $request) : Response
    {
        $subscriptionPlans = SubscriptionPlan::all();

        return Inertia::render('Subscription', [
            "subscriptionPlans" => $subscriptionPlans
        ]);
    }
}

