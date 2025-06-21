<?php

require 'vendor/autoload.php';
require_once '../config.php';

use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ✅ 创建 Slim 应用
$responseFactory = new ResponseFactory();
AppFactory::setResponseFactory($responseFactory);
$app = AppFactory::create();

// ✅ 连接数据库
$db = new db();
$conn = $db->connect();

// ✅ --------------------------- 加载用户路由 -----------------------------------
$addUserRoutes = require __DIR__ . '/routes/UserController.php';
$addUserRoutes($app, $conn);
$addAddressRoutes = require __DIR__ . '/routes/AddressController.php';
$addAddressRoutes($app, $conn);
$addProductRoutes = require __DIR__ . '/routes/ProductController.php';
$addProductRoutes($app, $conn);
$addContractRoutes = require __DIR__ . '/routes/ContractController.php';
$addContractRoutes($app, $conn);
$addOrderRoutes = require __DIR__ . '/routes/OrderController.php';
$addOrderRoutes($app, $conn);
$addOrderItemRoutes = require __DIR__ . '/routes/OrderItemController.php';
$addOrderItemRoutes($app, $conn);
$addEventRoutes = require __DIR__ . '/routes/EventController.php';
$addEventRoutes($app, $conn);
$addAuthRoutes = require __DIR__ . '/routes/AuthController.php';
$addAuthRoutes($app, $conn);
require_once './middlewares/AuthMiddleware.php';


// ✅ 工具函数：处理 JSON 输入
function get_input_data() {
    return json_decode(file_get_contents('php://input'), true);
}

$app->group('/protected', function (\Slim\Routing\RouteCollectorProxy $group) {
    $group->get('/user-data', function ($req, $res) {
        $user = $req->getAttribute('user');
        $res->getBody()->write(json_encode([
            "message" => "Welcome, {$user->username}!",
            "user" => $user
        ]));
        return $res->withHeader('Content-Type', 'application/json');
    });
})->add('AuthMiddleware'); // 👈 添加中间件

// ✅ 登录接口
// $app->post('/login', function (Request $req, Response $res) use ($conn) {
//     $data = json_decode($req->getBody()->getContents(), true);

//     if (!isset($data['email']) || !isset($data['password'])) {
//         $res->getBody()->write(json_encode(["success" => false, "message" => "Missing email or password"]));
//         return $res->withHeader('Content-Type', 'application/json')->withStatus(400);
//     }

//     $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = :email");
//     $stmt->execute([':email' => $data['email']]);
//     $user = $stmt->fetch(PDO::FETCH_ASSOC);

//     if (!$user || $user['password'] !== $data['password']) {
//         $res->getBody()->write(json_encode(["success" => false, "message" => "Invalid credentials"]));
//         return $res->withHeader('Content-Type', 'application/json')->withStatus(401);
//     }

//     require_once './jwt.php';
//     $token = JwtHandler::generateToken($user['id'], $user['email'], 'user');

//     $res->getBody()->write(json_encode([
//         "success" => true,
//         "token" => $token,
//         "user" => [
//             "id" => $user['id'],
//             "name" => $user['name'],
//             "email" => $user['email']
//         ]
//     ]));
//     return $res->withHeader('Content-Type', 'application/json');
// });

// ✅ 启动 Slim 应用
$app->run();

