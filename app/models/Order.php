<?php
require_once 'Database.php';

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getPDO();
    }

    public function getOrder()
    {
        $stmt = $this->db->prepare("SELECT 
                                        o.id,
                                        o.user_id, 
                                        o.total, 
                                        o.status, 
                                        o.created_at,
                                        oi.beer_id,
                                        oi.quantity,
                                        oi.price
                                    FROM orders o
                                    LEFT JOIN order_items oi ON o.id = oi.order_id
                                    ORDER BY o.user_id DESC
                                ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteOrder($orderId)
    {
        try {
            $this->db->beginTransaction();

            // Delete order items first
            $stmtItems = $this->db->prepare("DELETE FROM `order_items` WHERE `order_id` = :orderId");
            $stmtItems->bindParam(':orderId', $orderId, PDO::PARAM_INT);
            $stmtItems->execute();

            // Then delete the order
            $stmtOrder = $this->db->prepare("DELETE FROM `orders` WHERE `id` = :orderId");
            $stmtOrder->bindParam(':orderId', $orderId, PDO::PARAM_INT);
            $stmtOrder->execute();

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error deleting order: " . $e->getMessage());
            return false;
        }
    }
}
