<?php
require_once './config.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode(["success" => false, "message" => "Missing email or password"]);
    exit();
}

$conn = new db();
$db = $conn->connect();

$stmt = $db->prepare("SELECT id, name, email, password FROM users WHERE email = :email");
$stmt->execute([':email' => $data['email']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['password'] !== $data['password']) {
    echo json_encode(["success" => false, "message" => "Invalid credentials"]);
    exit();
}

// 返回用户数据（不包括密码）
echo json_encode(["success" => true, "user" => [
    "id" => $user['id'],
    "name" => $user['name'],
    "email" => $user['email']
]]);
