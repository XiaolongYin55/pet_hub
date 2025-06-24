<?php
// repositories/UserRepository.php
class UserRepository
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function addUserWithAddress($data)
    {
        try {
            // 启动事务
            $this->pdo->beginTransaction();

            // 插入 user
            $stmt = $this->pdo->prepare("
            INSERT INTO users (name, username, password, image, phone_no, email, role, create_time, update_time)
            VALUES (:name, :username, :password, :image, :phone_no, :email, :role, NOW(), NOW())
        ");
            $stmt->execute([
                ':name' => $data['name'],
                ':username' => $data['username'],
                ':password' => $data['password'],
                ':image' => $data['image'] ?? 'default.jpg',
                ':phone_no' => $data['phone_no'],
                ':email' => $data['email'],
                ':role' => $data['role']
            ]);

            $userId = $this->pdo->lastInsertId();

            // 插入 address
            if (isset($data['address'])) {
                $addr = $data['address'];
                $stmt = $this->pdo->prepare("
                INSERT INTO address (user_id, country, state, city, district, street_address, postal_code, phone_number)
                VALUES (:user_id, :country, :state, :city, :district, :street_address, :postal_code, :phone_number)
            ");
                $stmt->execute([
                    ':user_id' => $userId,
                    ':country' => $addr['country'],
                    ':state' => $addr['state'],
                    ':city' => $addr['city'],
                    ':district' => $addr['district'],
                    ':street_address' => $addr['street_address'],
                    ':postal_code' => $addr['postal_code'],
                    ':phone_number' => $addr['phone_number']
                ]);
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Add User Error: " . $e->getMessage());
            return false;
        }
    }
    // public function isUsernameExists($username)
    // {
    //     $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    //     $stmt->execute([':username' => $username]);
    //     return $stmt->fetchColumn() > 0;
    // }
}
