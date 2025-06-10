<?php
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// 处理 CORS 预检请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'vendor/autoload.php';
require_once '../config.php';

// 设置 ResponseFactory（Slim 4 需要手动指定）
$responseFactory = new ResponseFactory();
AppFactory::setResponseFactory($responseFactory);
$app = AppFactory::create();

// **正确获取数据库连接**
$db = new db();
$conn = $db->connect();

// **处理 JSON 输入**
function get_input_data() {
    return json_decode(file_get_contents('php://input'), true);
}

// ✅ 这里添加 /login 路由
$app->post('/login', function (Request $req, Response $res) use ($conn) {
    $data = json_decode($req->getBody()->getContents(), true);

    if (!isset($data['email']) || !isset($data['password'])) {
        $res->getBody()->write(json_encode(["success" => false, "message" => "Missing email or password"]));
        return $res->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = :email");
    $stmt->execute([':email' => $data['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || $user['password'] !== $data['password']) {
        $res->getBody()->write(json_encode(["success" => false, "message" => "Invalid credentials"]));
        return $res->withHeader('Content-Type', 'application/json')->withStatus(401);
    }

    $res->getBody()->write(json_encode(["success" => true, "user" => [
        "id" => $user['id'],
        "name" => $user['name'],
        "email" => $user['email']
    ]]));
    return $res->withHeader('Content-Type', 'application/json')->withStatus(200);
});

// ✅ 用户相关 API
$app->get('/users', function (Request $req, Response $res) use ($conn) {
    $stmt = $conn->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res->getBody()->write(json_encode($users));
    return $res->withHeader('Content-Type', 'application/json');
});

$app->post('/users', function (Request $req, Response $res) use ($conn) {
    $data = get_input_data();
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->execute([
        ':name' => $data['name'],
        ':email' => $data['email'],
        ':password' => $data['password']
    ]);
    $res->getBody()->write(json_encode(["message" => "User created", "id" => $conn->lastInsertId()]));
    return $res->withHeader('Content-Type', 'application/json');
});

// ✅ 产品相关 API
$app->get('/products', function (Request $req, Response $res) use ($conn) {
    $stmt = $conn->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res->getBody()->write(json_encode($products));
    return $res->withHeader('Content-Type', 'application/json');
});

$app->post('/products', function (Request $req, Response $res) use ($conn) {
    $data = get_input_data();
    $stmt = $conn->prepare("INSERT INTO products (name, price) VALUES (:name, :price)");
    $stmt->execute([':name' => $data['name'], ':price' => $data['price']]);
    $res->getBody()->write(json_encode(["message" => "Product added"]));
    return $res->withHeader('Content-Type', 'application/json');
});

$app->put('/products/{id}', function (Request $req, Response $res, array $args) use ($conn) {
    $id = $args['id'];
    $data = get_input_data();
    $stmt = $conn->prepare("UPDATE products SET name = :name, price = :price WHERE id = :id");
    $stmt->execute([':name' => $data['name'], ':price' => $data['price'], ':id' => $id]);
    $res->getBody()->write(json_encode(["message" => "Product updated"]));
    return $res->withHeader('Content-Type', 'application/json');
});

$app->delete('/products/{id}', function (Request $req, Response $res, array $args) use ($conn) {
    $id = $args['id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $res->getBody()->write(json_encode(["message" => "Product deleted"]));
    return $res->withHeader('Content-Type', 'application/json');
});

// ✅ 订单相关 API
$app->get('/orders', function (Request $req, Response $res) use ($conn) {
    $stmt = $conn->query("SELECT * FROM orders");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res->getBody()->write(json_encode($orders));
    return $res->withHeader('Content-Type', 'application/json');
});

$app->post('/orders', function (Request $req, Response $res) use ($conn) {
    $data = get_input_data();
    $stmt = $conn->prepare("INSERT INTO orders (userId, productId, quantity) VALUES (:userId, :productId, :quantity)");
    $stmt->execute([
        ':userId' => $data['userId'],
        ':productId' => $data['productId'],
        ':quantity' => $data['quantity']
    ]);
    $res->getBody()->write(json_encode(["message" => "Order placed"]));
    return $res->withHeader('Content-Type', 'application/json');
});

$app->delete('/orders/{id}', function (Request $req, Response $res, array $args) use ($conn) {
    $id = $args['id'];
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $res->getBody()->write(json_encode(["message" => "Order deleted"]));
    return $res->withHeader('Content-Type', 'application/json');
});

// **运行 Slim API**
$app->run();
