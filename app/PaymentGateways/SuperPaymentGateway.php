<?php

namespace App\PaymentGateways;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\RequestException;

class SuperPaymentGateway implements PaymentGatewayInterface
{
    public function processPayment($paymentData)
    {
        try {
            $url = getenv('PAYMENT_ENDPOINT');
            $res = Http::post($url, $paymentData);
            $response = $res->json();
            $paymentMessage = $response['message'] ?? null;
            $response['status'] = $res->status();
            $response['success'] = $res->successful();
            if($response['message'] != 'Payment Successful'){
                $response['success'] = false;
            }
            return $response;
        } catch (RequestException $e) {
            $response = [
                'message' => $e->getMessage(),
                'success' => false,
                'status' => $e->getCode()
            ];
            Log::error("SuperPaymentGateway processPayment failed || ". $e->getMessage());
            return $response;
        }
    }
}