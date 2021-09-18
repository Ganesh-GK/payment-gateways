<?php

namespace Sparkouttech\PaymentGateway\App\Controllers;

use Illuminate\Routing\Controller;
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

    public function createCharge($amount, $stripeToken, $currency = "usd"){

        Stripe\Charge::create ([
            "amount" => $amount * 100,
            "currency" => $currency,
            "source" => $stripeToken,
            "description" => "This payment is tested purpose."
        ]);
    }
}
