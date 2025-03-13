<?php
require_once 'app/models/StripeModel.php';

class CheckoutController {
    private $stripeModel;

    public function __construct() {
        $this->stripeModel = new StripeModel();
    }

    public function handleCheckout($total) {
        $checkoutSession = $this->stripeModel->createCheckoutSession(
            'Bière artisanale', 
            'eur',              
            $total * 100,       // Use $total variable and convert to cents
            1,                  
            'http://localhost/beercraft/?action=paymentSuccess', // Success URL
            'http://localhost/beercraft/?action=paymentError'    // Cancel URL
        );

        header("Location: " . $checkoutSession->url);
        exit();
    }
}
?>
