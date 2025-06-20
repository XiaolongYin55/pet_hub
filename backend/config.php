<?php
class db {
  private $dbuser = 'root';
  private $dbpass = ''; // 空密码
  private $dbname = 'pet-hub';

  function connect() {
    // 使用TCP连接（更稳定）
    $dsn = "mysql:host=127.0.0.1;port=3306;dbname=$this->dbname";
    $pdo = new PDO($dsn, $this->dbuser, $this->dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  }
}

