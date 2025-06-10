<?php
class db {
    private $host = '127.0.0.1'; // 使用 TCP/IP 连接更可靠
    private $port = 3306;        // XAMPP 默认端口
    private $dbuser = 'root';
    private $dbpass = '';        // XAMPP 默认密码为空
    private $dbname = 'my_database'; // 请确保该数据库存在

  function connect() {
    $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4";
    $pdo = new PDO($dsn, $this->dbuser, $this->dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  }
}