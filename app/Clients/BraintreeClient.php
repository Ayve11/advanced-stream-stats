<?php

namespace App\Clients;

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
        return $this->gateway->transaction()->sale([
            'amount' => $amount,
            'paymentMethodNonce' => $nonce,
            'options' => [
              'submitForSettlement' => True
            ]
          ]);
    }

    public function createSubscription($planId, $paymentMethodToken){
        return $this->gateway->subscription()->create([
            'planId' => $planId,
            'paymentMethodToken' => $paymentMethodToken,
        ]);
    }

    public function createCustomer($email, $nonce){
        return $this->gateway->customer()->create([
            'email' => $email,
            'paymentMethodNonce' => $nonce,
          ]);
    }
    
    public function findCustomer($customerId){
        return $this->gateway->customer()->find($customerId);
    }

}

