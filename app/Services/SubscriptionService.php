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
            $status = Subscription::STATUS_ONE_TIME;
        } else if($plan->payment_type == SubscriptionPlan::PAYMENT_TYPE_SUBSCRIPTION){
            $response = $this->createRepeatableTransactionSubscription($plan->type, $paymentNonce);
            $status = Subscription::STATUS_ACTIVE;
        } else {
            throw new NotImplementedException();
        }

        $user = Auth::user();
        
        Subscription::create([
            "user_id" => $user->id,
            "subscription_plan_id" => $plan->id,
            "braintree_subscription_id" => $response->subscription->id,
            "status" => $status,
            "expired_at" => now()->addDays($plan->duration_in_days)
        ]);
        
        return $response;
    }

    public function cancelSubscription(Subscription $subscription){
        $this->braintreeClient->cancelSubscription($subscription->braintree_subscription_id);
        $subscription->status = Subscription::STATUS_CANCELLED;
        $subscription->save();
        return $subscription;
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

