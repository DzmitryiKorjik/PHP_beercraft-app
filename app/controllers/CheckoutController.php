<?php
require_once 'app/models/StripeModel.php';

class CheckoutController {
    private $stripeModel;

    public function __construct() {
        $this->stripeModel = new StripeModel();
    }

    public function handleCheckout($total) {
        // Clean and convert the total to a proper float
        $total = (float)preg_replace('/[^0-9.]/', '', $total);
        
        if (!is_numeric($total) || $total <= 0) {
            header("Location: " . BASE_URL . "?action=paymentError");
            exit();
        }

        $checkoutSession = $this->stripeModel->createCheckoutSession(
            'Bière artisanale', 
            'eur',              
            round($total * 100), // Convert to cents and ensure integer
            1,                  
            'http://localhost/beercraft/?action=paymentSuccess',
            'http://localhost/beercraft/?action=paymentError'
        );

        header("Location: " . $checkoutSession->url);
        exit();
    }
}
?>
