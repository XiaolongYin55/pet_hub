<?php
// services/ProductService.php

require_once __DIR__ . '/../repositories/ProductRepository.php';

class ProductService {
    private $productRepo;

    public function __construct($pdo) {
        $this->productRepo = new ProductRepository($pdo);
    }

    public function getProducts() {
        return $this->productRepo->getAllProducts();
    }

    public function getProductById($id) {
        return $this->productRepo->getProductById($id);
    }
public function addProduct($data) {
    return $this->productRepo->addProduct($data);
}

public function updateProduct($data) {
    return $this->productRepo->updateProduct($data);
}

public function deleteProduct($id) {
    return $this->productRepo->deleteProduct($id);
}

    
}
