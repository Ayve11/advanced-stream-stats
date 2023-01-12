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

        $user = Auth::user();
        $subscription = (new Subscription())->fill([
            "user_id" => $user->id,
            "subscription_plan_id" => $plan->id
        ]);
        $subscription->expired_at = now()->addDays($plan->duration_in_days);
        if($plan->payment_type == SubscriptionPlan::PAYMENT_TYPE_ONE_TIME){
            $response = $this->createOneTimeTransactionSubscription($plan->price, $paymentNonce);
            $subscription->status = Subscription::STATUS_ONE_TIME;
        } else if($plan->payment_type == SubscriptionPlan::PAYMENT_TYPE_SUBSCRIPTION){
            $response = $this->createRepeatableTransactionSubscription($plan->type, $paymentNonce);
            $subscription->status = Subscription::STATUS_ACTIVE;
            $subscription->braintree_subscription_id = $response->subscription->id;
        } else {
            throw new NotImplementedException();
        }
        $subscription->save();
        
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

