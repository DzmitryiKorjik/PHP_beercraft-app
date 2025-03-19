<?php
// Inclusion du modèle Stripe pour gérer les paiements
require_once 'app/models/StripeModel.php';

/**
 * Contrôleur CheckoutController
 * Gère le processus de paiement en utilisant Stripe.
 */
class CheckoutController {
    private $stripeModel;

    public function __construct() {
        // Initialisation du modèle Stripe
        $this->stripeModel = new StripeModel();
    }

    /**
     * Gère la création d'une session de paiement et redirige l'utilisateur vers Stripe.
     *
     * @param float|string $total Montant total de la commande.
     */
    public function handleCheckout($total) {
        // Nettoyage et conversion du montant total en nombre décimal valide
        $total = (float)preg_replace('/[^0-9.]/', '', $total);
        
        // Vérifie si le montant est valide (nombre positif)
        if (!is_numeric($total) || $total <= 0) {
            // Redirection vers la page d'erreur de paiement en cas de montant invalide
            header("Location: " . BASE_URL . "?action=paymentError");
            exit();
        }

        // Création d'une session de paiement avec Stripe
        $checkoutSession = $this->stripeModel->createCheckoutSession(
            'Bière artisanale',  // Description de l'article
            'eur',               // Devise utilisée (euros)
            round($total * 100), // Conversion en centimes pour Stripe (ex : 10,50€ => 1050)
            1,                   // Quantité d'articles (fixé à 1 ici, peut être ajusté)
            'http://localhost/beercraft/?action=paymentSuccess', // URL de succès après paiement
            'http://localhost/beercraft/?action=paymentError'    // URL en cas d'échec du paiement
        );

        // Redirection vers la page de paiement Stripe
        header("Location: " . $checkoutSession->url);
        exit();
    }
}
?>
