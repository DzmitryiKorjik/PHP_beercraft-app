<?php 
require_once 'app/models/BuyBeer.php';

/**
 * Contrôleur de gestion du panier
 * Gère les actions liées au panier d'achat
 */
class BuyBeerController {
    private $model;

    public function __construct() {
        $this->model = new BuyBeer();
    }

    /**
     * Gère l'ajout au panier et l'affichage du panier
     * @param int|null $beerId ID de la bière à ajouter (optionnel)
     * @return array|void Données du panier ou redirection
     */
    public function buyBeer($beerId = null) {
        if (!isset($_SESSION['users'])) {
            header('Location: ' . BASE_URL . '?action=signin');
            exit;
        }

        $userId = $_SESSION['users']['id'];

        if ($beerId) {
            $this->model->addToCart($beerId, $userId);
            header('Location: ' . BASE_URL . '?action=buyBeer');
            exit;
        }

        $cartItems = $this->model->getCartItems($userId);
        $total = $this->model->getCartTotal($userId);

        return [
            'cartItems' => $cartItems,
            'total' => $total
        ];
    }

    /**
     * Supprime un article du panier
     * @param int $beerId ID de la bière à supprimer
     */
    public function removeFromCart($beerId) {
        if (!isset($_SESSION['users'])) {
            header('Location: ' . BASE_URL . '?action=signin');
            exit;
        }

        $userId = $_SESSION['users']['id'];
        $this->model->removeFromCart($beerId, $userId);
        header('Location: ' . BASE_URL . '?action=buyBeer');
        exit;
    }

    /**
     * Met à jour la quantité d'un article
     * Vérifie la validité des données avant la mise à jour
     */
    public function updateQuantity() {
        if (!isset($_SESSION['users']) || !isset($_POST['beer_id']) || !isset($_POST['quantity'])) {
            header('Location: ' . BASE_URL . '?action=buyBeer');
            exit;
        }

        $userId = $_SESSION['users']['id'];
        $beerId = $_POST['beer_id'];
        $quantity = max(1, min(99, (int)$_POST['quantity']));

        $this->model->updateQuantity($beerId, $userId, $quantity);
        header('Location: ' . BASE_URL . '?action=buyBeer');
        exit;
    }

    /**
     * Traitement du paiement
     */
    public function placeOrder() {
        if (!isset($_SESSION['users'])) {
            header('Location: ' . BASE_URL . '?action=signin');
            exit;
        }

        $userId = $_SESSION['users']['id'];
        $result = $this->model->placeOrder($userId);
        
        // Après une commande réussie, nous sommes redirigés vers la page de confirmation
        header('Location: ' . BASE_URL . '?action=checkout');
        exit;
    }
}
?>