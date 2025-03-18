<?php 
require_once 'app/models/BuyBeer.php';
require_once 'app/models/Order.php';

/**
 * Contrôleur de gestion du panier
 * Gère les actions liées au panier d'achat
 */
class BuyBeerController {
    private $model;
    private $orderModel;

    private function checkAuth() {
        if (!isset($_SESSION['users'])) {
            header('Location: ' . BASE_URL . '?action=signin');
            exit;
        }
        return $_SESSION['users']['id'];
    }

    public function __construct() {
        $this->model = new BuyBeer();
        $this->orderModel = new Order();
    }

    /**
     * Gère l'ajout au panier et l'affichage du panier
     * @param int|null $beerId ID de la bière à ajouter (optionnel)
     * @return array|void Données du panier ou redirection
     */
    public function buyBeer($beerId = null) {
        $userId = $this->checkAuth();

        if ($beerId) {
            $this->model->addToCart($beerId, $userId);
            header('Location: ' . BASE_URL . '?action=order');
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
        $userId = $this->checkAuth();
        
        if (isset($_GET['type']) && $_GET['type'] === 'order') {
            if ($_SESSION['users']['role'] === 'admin') {
                $userEmail = $_GET['email'] ?? '';
                if (empty($userEmail) || empty($beerId)) {
                    header('Location: ' . BASE_URL . '?action=order&error=invalid_params');
                    exit;
                }
                
                $success = $this->orderModel->deleteOrder($beerId, $userEmail);
                if (!$success) {
                    error_log("Failed to delete order: beerId=$beerId, email=$userEmail");
                    header('Location: ' . BASE_URL . '?action=order&error=delete_failed');
                    exit;
                }
                
                header('Location: ' . BASE_URL . '?action=order&success=true');
                exit;
            }
        } else {
            $this->model->removeFromCart($beerId, $userId);
            header('Location: ' . BASE_URL . '?action=buyBeer');
        }
        exit;
    }

    /**
     * Met à jour la quantité d'un article
     * Vérifie la validité des données avant la mise à jour
     */
    public function updateQuantity() {
        $userId = $this->checkAuth();
        
        if (!isset($_POST['beer_id']) || !isset($_POST['quantity'])) {
            header('Location: ' . BASE_URL . '?action=buyBeer');
            exit;
        }

        $this->model->updateQuantity(
            $_POST['beer_id'],
            $userId,
            max(1, min(99, (int)$_POST['quantity']))
        );
        
        header('Location: ' . BASE_URL . '?action=buyBeer');
        exit;
    }

    public function getAllOrders() {
        if (!isset($_SESSION['users']) || $_SESSION['users']['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '?action=home');
            exit;
        }
        return [
            'orders' => $this->orderModel->getAllOrders()
        ];
    }
}
?>