<?php
// repositories/OrderRepository.php

class OrderRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllOrders() {
        $stmt = $this->pdo->query("SELECT * FROM orders");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            $order['items'] = $this->getItemsByOrderId($order['order_id']);
        }

        return $orders;
    }

    public function getOrderById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE order_id = :id");
        $stmt->execute([':id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $order['items'] = $this->getItemsByOrderId($id);
        }

        return $order;
    }

    public function getOrderByUsername($username) {
    $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE username = :username ORDER BY create_time DESC LIMIT 1");
    $stmt->execute([':username' => $username]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        $order['items'] = $this->getItemsByOrderId($order['order_id']);
    }

    return $order;
}


    private function getItemsByOrderId($orderId) {
        $stmt = $this->pdo->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addOrder($data) {
    $stmt = $this->pdo->prepare("
        INSERT INTO orders (user_id, username, address_id, total_price, create_time, update_time)
        VALUES (:user_id, :username, :address_id, :total_price, NOW(), NOW())
    ");
    $stmt->execute([
        ':user_id' => $data['user_id'],
        ':username' => $data['username'],
        ':address_id' => $data['address_id'],
        ':total_price' => $data['total_price']
    ]);
    return $this->pdo->lastInsertId(); // 返回新订单 ID
}

public function updateOrder($data) {
    $stmt = $this->pdo->prepare("
        UPDATE orders SET 
            user_id = :user_id,
            username = :username,
            address_id = :address_id,
            total_price = :total_price,
            update_time = NOW()
        WHERE order_id = :order_id
    ");
    return $stmt->execute([
        ':user_id' => $data['user_id'],
        ':username' => $data['username'],
        ':address_id' => $data['address_id'],
        ':total_price' => $data['total_price'],
        ':order_id' => $data['order_id']
    ]);
}

public function deleteOrder($id) {
    $stmt = $this->pdo->prepare("DELETE FROM orders WHERE order_id = :id");
    return $stmt->execute([':id' => $id]);
}

}

