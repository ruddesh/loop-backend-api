<?php

namespace App\PaymentGateways;

interface PaymentGatewayInterface
{
    public function processPayment($paymentData);
}