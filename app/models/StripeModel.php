<?php
require 'vendor/autoload.php';

class StripeModel {
    private $secretKey;

    public function __construct() {
        // Vérification de l'existence de la clé secrète
        if (!isset($_ENV['SECRET_KEY']) || empty($_ENV['SECRET_KEY'])) {
            throw new Exception('Stripe Secret Key is not defined.');
        }
        $this->secretKey = $_ENV['SECRET_KEY'];

        // Initialisation de l'API Stripe
        \Stripe\Stripe::setApiKey($this->secretKey);
    }

    /**
     * Crée une session de paiement Stripe Checkout.
     *
     * @param string $productName Nom du produit
     * @param string $currency Devise (par exemple 'eur')
     * @param int $amount Montant en centimes (par exemple 1500 pour 15,00€)
     * @param int $quantity Quantité du produit
     * @param string $successUrl URL de redirection en cas de succès
     * @param string $cancelUrl URL de redirection en cas d'annulation
     * @return \Stripe\Checkout\Session La session Stripe
     * @throws Exception Si une erreur survient lors de la création de la session
     */
    public function createCheckoutSession($productName, $currency, $amount, $quantity, $successUrl, $cancelUrl) {
        // Validation des paramètres
        if (!is_numeric($amount) || $amount <= 0) {
            throw new Exception('Invalid amount');
        }

        if (!filter_var($successUrl, FILTER_VALIDATE_URL)) {
            throw new Exception('Invalid success URL');
        }

        if (!filter_var($cancelUrl, FILTER_VALIDATE_URL)) {
            throw new Exception('Invalid cancel URL');
        }

        $validCurrencies = ['eur', 'usd', 'gbp']; // Ajouter d'autres devises si nécessaire
        if (!in_array(strtolower($currency), $validCurrencies)) {
            throw new Exception('Invalid currency');
        }

        try {
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
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new Exception('Stripe API error: ' . $e->getMessage());
        }
    }
}
?>
