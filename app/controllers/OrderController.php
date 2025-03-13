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
        $orderItems = $this->orderModel->getOrderItems();
        return [
            'orderItems' => $orderItems,
            'view' => 'order'
        ];
    }

    public function deleteOrderItem()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['order_item_id'])) {
            $orderItemId = $_POST['order_item_id'];
            $this->orderModel->deleteOrderItem($orderItemId);
            header('Location: ' . BASE_URL . '?action=order');
            exit;
        }
    }
}
?>