<?php 
require_once 'app/models/Order.php';

class OrderController
{
    private $orderModel;

    public function __construct()
    {
        $this->orderModel = new Order();
    }

    public function listOrders()
    {
        $orderItems = $this->orderModel->getOrder();
        return [
            'orderItems' => $orderItems,
            'view' => 'order'
        ];
    }

    public function deleteOrderItem()
    {
        if (isset($_POST['order_id'])) {
            $id = $_POST['order_id'];
            $result = $this->orderModel->deleteOrder($id);
            
            if ($result) {
                header('Location: ' . BASE_URL . '?action=order');
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression de la commande.";
                header('Location: ' . BASE_URL . '?action=order');
            }
            exit();
        }
    }
}
?>