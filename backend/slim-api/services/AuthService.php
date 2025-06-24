<?php
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../jwt.php'; // 引入 JWT 工具类

class AuthService
{
    private $userRepo;

    public function __construct($pdo)
    {
        $this->userRepo = new UserRepository($pdo);
    }

    public function login($username, $password)
    {
        $user = $this->userRepo->findUserByUsernameAndPassword($username, $password);
        if (!$user) return null;

        $timestamp = time();
        $randomHex = bin2hex(random_bytes(8)); // 16 字节 = 32 个字符
        $encodedUsername = base64_encode($user['username']); // 编码用户名
        $signature = substr(hash('sha256', $user['id'] . $user['username'] . $timestamp), 0, 16); // 签名截断

        $token = "TKN.$encodedUsername.$timestamp.$randomHex.$signature";

        return [
            'token' => $token,
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
            'email' => $user['email']
        ];
    }
    public function addUserWithAddress($data)
    {
        return $this->userRepo->addUserWithAddress($data);
    }

    public function isUsernameExists($username)
    {
        return $this->userRepo->isUsernameExists($username);
    }
}
