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

}

