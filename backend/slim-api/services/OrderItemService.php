<?php
// services/OrderItemService.php
require_once __DIR__ . '/../repositories/OrderItemRepository.php';

class OrderItemService {
    private $repo;

    public function __construct($pdo) {
        $this->repo = new OrderItemRepository($pdo);
    }

    public function getItemsByOrderId($orderId) {
        return $this->repo->getItemsByOrderId($orderId);
    }

        public function addItemsToOrder($orderId, $items) {
        return $this->repo->addItemsToOrder($orderId, $items);
    }

    public function deleteItem($itemId) {
        return $this->repo->deleteItem($itemId);
    }
}
