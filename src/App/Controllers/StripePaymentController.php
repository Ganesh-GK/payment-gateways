<?php

namespace Sparkouttech\PaymentGateway\App\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Stripe;

class StripePaymentController extends Controller
{
    public function __construct()
    {
        $this->setup();
    }

    public function setup()
    {
        Stripe\Stripe::setApiKey(config('payment-gateway.stripe_secret'));
    }

    public function createCustomer(array $user)
    {
        return Stripe\Customer::create([
            'email' => $user['email'],
            'name' => $user['name'],
            'phone' => $user['phone'],
            'description' => $user['email']
        ]);
    }

    public function createCard(array $attributes)
    {
        return Stripe\Card::create($attributes);
    }

    public function createCharge($amount, $stripeToken, $currency = "usd")
    {
        return Stripe\Charge::create ([
            "amount" => $amount * 100,
            "currency" => $currency,
            "source" => $stripeToken,
            "description" => "This payment is tested purpose."
        ]);
    }

    public function createSetupIntent(array $attributes)
    {
        return Stripe\SetupIntent::create([
            'customer' => $attributes['customer'],
            'usage' => "on_session"
        ]);
    }

    public function createPaymentIntent(array $attributes)
    {
        return Stripe\PaymentIntent::create([
            'amount' => ($attributes['amount'] * 100),
            'currency' => $attributes['currency'],
            'customer' => $attributes['customer'],
            'payment_method' => $attributes['payment_method']
        ]);
    }

}
