<?php
// repositories/ProductRepository.php

class ProductRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllProducts() {
        $stmt = $this->pdo->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addProduct($data) {
    $stmt = $this->pdo->prepare("
        INSERT INTO products (title, image, price, quantity, allowance, category, description, create_time, update_time)
        VALUES (:title, :image, :price, :quantity, :allowance, :category, :description, NOW(), NOW())
    ");
    return $stmt->execute([
        ':title' => $data['title'],
        ':image' => $data['image'],
        ':price' => $data['price'],
        ':quantity' => $data['quantity'],
        ':allowance' => $data['allowance'],
        ':category' => $data['category'],
        ':description' => $data['description']
    ]);
}

public function updateProduct($data) {
    $stmt = $this->pdo->prepare("
        UPDATE products
        SET title = :title,
            image = :image,
            price = :price,
            quantity = :quantity,
            allowance = :allowance,
            category = :category,
            description = :description,
            update_time = NOW()
        WHERE id = :id
    ");
    return $stmt->execute([
        ':id' => $data['id'],
        ':title' => $data['title'],
        ':image' => $data['image'],
        ':price' => $data['price'],
        ':quantity' => $data['quantity'],
        ':allowance' => $data['allowance'],
        ':category' => $data['category'],
        ':description' => $data['description']
    ]);
}

public function deleteProduct($id) {
    $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = :id");
    return $stmt->execute([':id' => $id]);
}

}
