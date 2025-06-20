<?php
// models/Order.php
require_once 'Address.php';
require_once 'Items.php';
class Order {
    public $orderId;
    public $userId;
    public $username;
    public $address; // Address object
    public $itemList = []; // array of Items
    public $totalPrice;
    public $createTime;
    public $updateTime;

    public function __construct() {
        $this->address = new Address();
        $this->itemList = [];
    }
}