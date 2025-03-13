<?php
require 'vendor/autoload.php';

class StripeModel {
    private $secretKey;

    public function __construct() {
        $this->secretKey = $_ENV['SECRET_KEY'] ?? getenv('SECRET_KEY');
        \Stripe\Stripe::setApiKey($this->secretKey);
    }

    public function createCheckoutSession($productName, $currency, $amount, $quantity, $successUrl, $cancelUrl) {
        return \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $productName,
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => $quantity,
            ]],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);
    }
}
?>
