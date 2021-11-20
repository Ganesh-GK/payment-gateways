<?php

namespace Sparkouttech\PaymentGateway\App\Controllers;

use Illuminate\Routing\Controller;

class StripePaymentController extends Controller
{
    protected $secretKey;

    public function __construct()
    {
        $this->setup();
    }

    public function setup()
    {
        $this->secretKey = config('payment-gateway.stripe_secret');
    }

    public function createCustomer(array $user)
    {
        $url = 'https://api.stripe.com/v1/customers';

        $data['email'] = $user['email'];
        $data['name'] = $user['name'];
        $data['phone'] = $user['phone'];
        $data['description'] = $user['description'];

        $httpBuildQueryData  = http_build_query($data);

        return $this->executeCurlInit($url, $httpBuildQueryData);
    }

    public function createCharge($amount, $stripeToken, $description, $currency = "usd")
    {
        $url = 'https://api.stripe.com/v1/charge';

        $data['amount'] = ($amount * 100);
        $data['currency'] = $currency;
        $data['source'] = $stripeToken;
        $data['description'] = $description;

        $httpBuildQueryData  = http_build_query($data);

        return $this->executeCurlInit($url, $httpBuildQueryData);
    }

    public function createSetupIntent(array $attributes)
    {
        $url = 'https://api.stripe.com/v1/setup_intents';

        $data['customer'] = $attributes['customer'];
        $data['usage'] = "on_session";

        $httpBuildQueryData  = http_build_query($data);

        return $this->executeCurlInit($url, $httpBuildQueryData);
    }

    public function createPaymentIntent(array $attributes)
    {
        $url = 'https://api.stripe.com/v1/payment_intents';

        $data['amount'] = ($attributes['amount'] * 100);
        $data['currency'] = $attributes['currency'];
        $data['customer'] = $attributes['customer'];
        $data['payment_method'] = $attributes['payment_method'];

        $httpBuildQueryData  = http_build_query($data);

        return $this->executeCurlInit($url, $httpBuildQueryData);
    }

    public function createDirectPaymentIntent(array $attributes)
    {
        $url = 'https://api.stripe.com/v1/payment_intents';

        $data['amount'] = ($attributes['amount'] * 100);
        $data['currency'] = $attributes['currency'];

        $httpBuildQueryData  = http_build_query($data);

        return $this->executeCurlInit($url, $httpBuildQueryData);
    }



    protected function executeCurlInit($url, $httpBuildQueryData)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true); /*POST*/
        curl_setopt($ch, CURLOPT_POSTFIELDS, $httpBuildQueryData);
        curl_setopt($ch, CURLOPT_USERPWD, $this->secretKey . ':');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $curlResult = curl_exec($ch);

        curl_close($ch);

        return json_decode($curlResult);
    }

}
