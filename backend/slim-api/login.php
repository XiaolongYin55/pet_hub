<?php
// require_once './config.php';
// require_once './jwt.php'; // 确保正确引入 JWT 处理类

// header("Content-Type: application/json");

// $data = json_decode(file_get_contents("php://input"), true);

// if (!isset($data['email']) || !isset($data['password'])) {
//     echo json_encode(["success" => false, "message" => "Missing email or password"]);
//     exit();
// }

// $conn = new db();
// $db = $conn->connect();

// $stmt = $db->prepare("SELECT id, name, email, password FROM users WHERE email = :email");
// $stmt->execute([':email' => $data['email']]);
// $user = $stmt->fetch(PDO::FETCH_ASSOC);

// if (!$user || $user['password'] !== $data['password']) {
//     echo json_encode(["success" => false, "message" => "Invalid credentials"]);
//     exit();
// }

// // 生成 JWT token
// $token = JwtHandler::generateToken($user['id'], $user['email']);
// error_log("Generated Token: " . var_export($token, true)); // 记录 token 结果


// // 返回用户数据及 token
// echo json_encode([
//     "success" => true,
//     "user" => [
//         "id" => $user['id'],
//         "name" => $user['name'],
//         "email" => $user['email']
//     ],
//     "token" => $token
// ]);

// exit(); // 立即退出，确保数据正确返回

