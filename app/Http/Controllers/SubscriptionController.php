<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Exception;
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
        if(($request->user())->hasActiveSubscription()){
            return $this->subscriptionDetails($request);
        } 
        return $this->subscriptionCreationPanel($request);
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

    public function cancel(Request $request){
        $user = $request->user();
        $subscription = $user->subscription;

        if($subscription->status != Subscription::STATUS_ACTIVE){
            throw new Exception("Wrong subscription status");
        }

        $this->subscriptionService->cancelSubscription($subscription);

        return redirect()->route('subscription', ["success" => "Successfully cancelled subscription"]);
    }

    private function subscriptionDetails(Request $request) : Response
    {
        $user = Auth::user();
        $subscription = $user->subscription;
        $subscriptionPlan = $subscription->subscriptionPlan;
        $pageProps = [
            "subscription" => $subscription,
            "subscriptionPlan" => $subscriptionPlan,
            "statusDescription" => $subscription->statusDescription
        ];

        return Inertia::render('SubscriptionDetails', $pageProps);
    }

    private function subscriptionCreationPanel(Request $request) : Response {
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
}

