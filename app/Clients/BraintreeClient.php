<?php

namespace App\Clients;

use App\Exceptions\ExternalCallException;
use Braintree\Gateway;

class BraintreeClient
{

    /** @var Gateway $gateway */
    private $gateway;

    public function __construct(){
        $this->gateway = new Gateway([
            'environment' => config("services.braintree.environment"),
            'merchantId' => config("services.braintree.merchantId"),
            'publicKey' => config("services.braintree.publicKey"),
            'privateKey' => config("services.braintree.privateKey"),
        ]);
    }

    public function createToken(){
        return $this->gateway->clientToken()->generate();
    }

    public function createSaleTransaction($amount, $nonce){
        $response = $this->gateway->transaction()->sale([
            'amount' => $amount,
            'paymentMethodNonce' => $nonce,
            'options' => [
              'submitForSettlement' => True
            ]
          ]);
        if(isset($response->success) && !$response->success) throw new ExternalCallException("Could not create sale transaction");
        return $response;
    }

    public function createSubscription($planId, $paymentMethodToken){
        $response = $this->gateway->subscription()->create([
            'planId' => $planId,
            'paymentMethodToken' => $paymentMethodToken,
        ]);
        if(isset($response->success) && !$response->success) throw new ExternalCallException("Cound not create subscription");
        return $response;
    }
    
    public function cancelSubscription($subscriptionId){
        $response = $this->gateway->subscription()->cancel($subscriptionId);
        if(isset($response->success) && !$response->success) throw new ExternalCallException("Could not cancel subscription");
        return $response;
    }

    public function createCustomer($email, $nonce){
        $response = $this->gateway->customer()->create([
            'email' => $email,
            'paymentMethodNonce' => $nonce,
          ]);
        if(isset($response->success) && !$response->success) throw new ExternalCallException("Could not create customer");
        return $response;
    }
    
    public function findCustomer($customerId){
        $response = $this->gateway->customer()->find($customerId);
        if(isset($response->success) && !$response->success) throw new ExternalCallException("Could not find customer");
        return $response;
    }

}

