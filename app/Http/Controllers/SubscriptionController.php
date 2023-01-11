<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class SubscriptionController extends Controller
{

    private $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function show(Request $request) : Response
    {
        $subscriptionPlans = SubscriptionPlan::all();

        $token = $this->subscriptionService->createClientToken();
        $pageProps = [
            "subscriptionPlans" => $subscriptionPlans,
            "clientToken" => $token
        ];

        $success = $request->input("success");
        if(!empty($success)){
            $pageProps['success'] = $success;
        }

        return Inertia::render('Subscription', $pageProps);
    }

    public function details(Request $request) : Response
    {
        $user = Auth::user();
        $subscription = $user->subscription ;

        $pageProps = [
            "subscription" => $subscription
        ];

        return Inertia::render('SubscriptionDetails', $pageProps);
    }

    public function create(Request $request)
    {
        $validation = [
            "payment_nonce" => ['required'],
            "payment_type" => ['required'],
            "plan" => ['required', 'exists:subscription_plans,type']
        ];

        $request->validate($validation);

        $data = $request->all();
        $this->subscriptionService->createSubscription($data['payment_nonce'], $data['plan']);

        return redirect()->route('subscription', ["success" => "Successfully created subscription"]);
    }
}

