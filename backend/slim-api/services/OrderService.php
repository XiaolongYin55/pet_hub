<?php
// services/OrderService.php

require_once __DIR__ . '/../repositories/OrderRepository.php';

class OrderService
{
    private $orderRepo;

    public function __construct($pdo)
    {
        $this->orderRepo = new OrderRepository($pdo);
    }

    public function getOrders()
    {
        return $this->orderRepo->getAllOrders();
    }

    public function getOrderById($id)
    {
        return $this->orderRepo->getOrderById($id);
    }

    public function getOrderByUsername($username)
    {
        return $this->orderRepo->getOrderByUsername($username);
    }


    public function addOrder($data)
    {
        return $this->orderRepo->addOrder($data);
    }

    public function updateOrder($data)
    {
        return $this->orderRepo->updateOrder($data);
    }

    public function deleteOrder($id)
    {
        return $this->orderRepo->deleteOrder($id);
    }
}
