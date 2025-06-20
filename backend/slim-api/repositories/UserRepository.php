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
}
