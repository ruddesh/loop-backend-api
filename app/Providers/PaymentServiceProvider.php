<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\PaymentGateways\PaymentGatewayInterface;
use App\PaymentGateways\SuperPaymentGateway;
use App\PaymentGateways\DefaultPaymentGateway;


class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGatewayInterface::class, function ($app) {
            $paymentGateway = getenv('PAYMENT_GATEWAY') ?? 'superpay';
            switch ($paymentGateway) {
                case 'superpay':
                    return new SuperPaymentGateway();
                    break;
                case 'default':
                    return new DefaultPaymentGateway();
                    break;
                default:
                    return new DefaultPaymentGateway();
                    break;
            }
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
