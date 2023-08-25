<?php

namespace App\PaymentGateways;
use Illuminate\Support\Facades\Http;

class DefaultPaymentGateway implements PaymentGatewayInterface
{
    public function processPayment($paymentData)
    {
        return [
            'status' => 'success',
            'message' => 'Payment Successful default payment'
        ];
    }
}