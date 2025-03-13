<?php 
require_once 'Database.php';

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getPDO();
    }

    public function getOrderItems()
    {
        $stmt = $this->db->prepare("SELECT
                                        `id`,
                                        `order_id`, 
                                        `beer_id`, 
                                        `quantity`, 
                                        `price`
                                    FROM `order_items` ORDER BY `order_id` DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteOrderItem($orderItemId)
    {
        $stmt = $this->db->prepare("DELETE FROM `order_items` WHERE `id` = :orderItemId");
        $stmt->bindParam(':orderItemId', $orderItemId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>