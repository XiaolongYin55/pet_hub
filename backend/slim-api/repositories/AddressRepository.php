<?php
class AddressRepository {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function getAddressByUserId($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM address WHERE user_id = :userId");
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
