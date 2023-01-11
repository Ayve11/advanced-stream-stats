<?php

namespace App\Services;

use App\Clients\BraintreeClient;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Nette\NotImplementedException;

class SubscriptionService
{
    private $braintreeClient;

    public function __construct(BraintreeClient $braintreeClient)
    {
        $this->braintreeClient = $braintreeClient;
    }

    public function createClientToken()
    {
        return $this->braintreeClient->createToken();
    }

    public function createSubscription($paymentNonce, $planType)
    {
        $plan = SubscriptionPlan::where('type', $planType)->first();

        if($plan->payment_type == SubscriptionPlan::PAYMENT_TYPE_ONE_TIME){
            $response = $this->createOneTimeTransactionSubscription($plan->price, $paymentNonce);
        } else if($plan->payment_type == SubscriptionPlan::PAYMENT_TYPE_SUBSCRIPTION){
            $response = $this->createRepeatableTransactionSubscription($plan->type, $paymentNonce);
        } else {
            throw new NotImplementedException();
        }

        $user = Auth::user();
        
        Subscription::create([
            "user_id" => $user->id,
            "subscription_plan_id" => $plan->id,
            "expired_at" => now()->addDays($plan->duration_in_days)
        ]);
        
        return $response;
    }

    private function createOneTimeTransactionSubscription($price, $paymentNonce){
        return $this->braintreeClient->createSaleTransaction($price, $paymentNonce);
    }

    private function createRepeatableTransactionSubscription($planId, $paymentNonce){
        $user = Auth::user();
        $customer = $this->findOrCreateCustomer($user, $paymentNonce);
        $response = $this->braintreeClient->createSubscription($planId, $customer->paymentMethods[0]->token);
        return $response;
    }

    private function findOrCreateCustomer($user, $paymentNonce){
        if(isset($user->customer_id)){
            $customer = $this->braintreeClient->findCustomer($user->customer_id);
        } else {
            $response = $this->braintreeClient->createCustomer($user->email, $paymentNonce);
            $customer = $response->customer;
            $user->customer_id = $customer->id;
            $user->save();
        }

        return $customer;
    }
}

