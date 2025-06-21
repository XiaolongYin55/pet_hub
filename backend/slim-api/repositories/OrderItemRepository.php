<?php
// repositories/OrderItemRepository.php
class OrderItemRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getItemsByOrderId($orderId) {
        $stmt = $this->pdo->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
        public function addItemsToOrder($orderId, $items) {
        $stmt = $this->pdo->prepare("
            INSERT INTO order_items (order_id, item_name, item_price, item_quantity, category, image)
            VALUES (:order_id, :item_name, :item_price, :item_quantity, :category, :image)
        ");

        foreach ($items as $item) {
            $stmt->execute([
                ':order_id' => $orderId,
                ':item_name' => $item['item_name'],
                ':item_price' => $item['item_price'],
                ':item_quantity' => $item['item_quantity'],
                ':category' => $item['category'],
                ':image' => $item['image']
            ]);
        }

        return true;
    }

    public function deleteItem($itemId) {
        $stmt = $this->pdo->prepare("DELETE FROM order_items WHERE item_id = :id");
        return $stmt->execute([':id' => $itemId]);
    }
}
