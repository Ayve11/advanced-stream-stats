<?php

namespace App\Http\Controllers;

use App\Clients\BraintreeClient;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{

    private $braintreeClient;

    public function __construct(BraintreeClient $braintreeClient)
    {
        $this->braintreeClient = $braintreeClient;
    }

    public function show(Request $request) : Response
    {
        $subscriptionPlans = SubscriptionPlan::all();

        $token = $this->braintreeClient->createToken();

        return Inertia::render('Subscription', [
            "subscriptionPlans" => $subscriptionPlans,
            "clientToken" => $token
        ]);
    }

    public function create(Request $request)
    {
        logger()->debug(json_encode($request->all()));
        $validation = [
            "payment_nonce" => ['required'],
            "payment_type" => ['required'],
            "plan" => ['required', 'exists:subscription_plans,type']
        ];

        $request->validate($validation);

        dd($request->all());
        // $this->braintreeClient->

        
    }
}

