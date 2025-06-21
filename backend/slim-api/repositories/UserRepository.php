<?php
// repositories/UserRepository.php
class UserRepository
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllUsers()
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 为每个 user 查地址
        foreach ($users as &$user) {
            $user['address'] = $this->getAddressByUserId($user['id']);
        }

        return $users;
    }

    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $user['address'] = $this->getAddressByUserId($id);
        }

        return $user;
    }

    private function getAddressByUserId($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM address WHERE user_id = :userId");
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUserWithAddress($data)
    {
        try {
            // ✅ 更新 users 表
            $stmt = $this->pdo->prepare("
            UPDATE users SET 
                name = :name, 
                username = :username, 
                password = :password,
                image = :image,
                phone_no = :phone_no,
                email = :email,
                role = :role,
                update_time = NOW()
            WHERE id = :id
        ");
            $stmt->execute([
                ':id' => $data['id'],
                ':name' => $data['name'],
                ':username' => $data['username'],
                ':password' => $data['password'],
                ':image' => $data['image'],
                ':phone_no' => $data['phone_no'],
                ':email' => $data['email'],
                ':role' => $data['role']
            ]);

            // ✅ 更新 address 表
            if (isset($data['address'])) {
                $addr = $data['address'];

                $stmt = $this->pdo->prepare("
                UPDATE address SET 
                    country = :country,
                    state = :state,
                    city = :city,
                    district = :district,
                    street_address = :street_address,
                    postal_code = :postal_code,
                    phone_number = :phone_number
                WHERE user_id = :user_id
            ");
                $stmt->execute([
                    ':user_id' => $data['id'],
                    ':country' => $addr['country'],
                    ':state' => $addr['state'],
                    ':city' => $addr['city'],
                    ':district' => $addr['district'],
                    ':street_address' => $addr['street_address'],
                    ':postal_code' => $addr['postal_code'],
                    ':phone_number' => $addr['phone_number']
                ]);
            }

            return true;
        } catch (Exception $e) {
            error_log("Update error: " . $e->getMessage());
            return false;
        }
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

    public function deleteUserById($id)
    {
        try {
            // address 有外键时，先删除 address（或者设置为 ON DELETE CASCADE）
            $stmt1 = $this->pdo->prepare("DELETE FROM address WHERE user_id = :id");
            $stmt1->execute([':id' => $id]);

            $stmt2 = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt2->execute([':id' => $id]);

            return true;
        } catch (Exception $e) {
            error_log("Delete User Error: " . $e->getMessage());
            return false;
        }
    }
}
