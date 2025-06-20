<?php
// models/User.php
require_once 'Address.php';
class User {
    public $id;
    public $name;
    public $username;
    public $password;
    public $image;
    public $phoneNo;
    public $email;
    public $address; // Address object
    public $role;
    public $createTime;
    public $updateTime;

    public function __construct() {
        $this->address = new Address();
    }
}