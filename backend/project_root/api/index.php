<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config.php';

function get_input_data() {
    return json_decode(file_get_contents('php://input'), true);
}

$conn = getDbConnection();

$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
$path = trim($request_uri[0], '/');
$segments = explode('/', $path);
$resource = $segments[3] ?? null; // 根据你的 URL 结构，调整这里索引
$id = $segments[4] ?? null;

// Helper to send JSON response with HTTP status code
function send_response($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit();
}

// Users endpoints
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $resource === 'users') {
    if ($id) {
        $result = $conn->query("SELECT * FROM users WHERE id = " . intval($id));
        $user = $result ? $result->fetch_assoc() : null;
        if ($user) {
            send_response($user);
        } else {
            send_response(["message" => "User not found"], 404);
        }
    } else {
        $res = $conn->query("SELECT * FROM users");
        $data = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
        }
        send_response($data);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $resource === 'users') {
    $data = get_input_data();
    if (!isset($data['name'], $data['email'], $data['password'])) {
        send_response(["message" => "Missing required fields"], 400);
    }
    $name = $data['name'];
    $email = $data['email'];
    $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password_hash);
    if ($stmt->execute()) {
        send_response(["message" => "User created", "id" => $conn->insert_id], 201);
    } else {
        send_response(["message" => "Database error: " . $stmt->error], 500);
    }
}

// Products endpoints
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $resource === 'products') {
    $res = $conn->query("SELECT * FROM products");
    $data = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
    }
    send_response($data);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $resource === 'products') {
    $data = get_input_data();
    if (!isset($data['name'], $data['price'])) {
        send_response(["message" => "Missing required fields"], 400);
    }
    $stmt = $conn->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
    $stmt->bind_param("sd", $data['name'], $data['price']);
    if ($stmt->execute()) {
        send_response(["message" => "Product added"], 201);
    } else {
        send_response(["message" => "Database error: " . $stmt->error], 500);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT' && $resource === 'products' && $id) {
    $data = get_input_data();
    if (!isset($data['name'], $data['price'])) {
        send_response(["message" => "Missing required fields"], 400);
    }
    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ? WHERE id = ?");
    $stmt->bind_param("sdi", $data['name'], $data['price'], $id);
    if ($stmt->execute()) {
        send_response(["message" => "Product updated"]);
    } else {
        send_response(["message" => "Database error: " . $stmt->error], 500);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $resource === 'products' && $id) {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        send_response(["message" => "Product deleted"]);
    } else {
        send_response(["message" => "Database error: " . $stmt->error], 500);
    }
}

// Orders endpoints
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $resource === 'orders') {
    $res = $conn->query("SELECT * FROM orders");
    $data = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
    }
    send_response($data);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $resource === 'orders') {
    $data = get_input_data();
    if (!isset($data['userId'], $data['productId'], $data['quantity'])) {
        send_response(["message" => "Missing required fields"], 400);
    }
    $stmt = $conn->prepare("INSERT INTO orders (userId, productId, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $data['userId'], $data['productId'], $data['quantity']);
    if ($stmt->execute()) {
        send_response(["message" => "Order placed"], 201);
    } else {
        send_response(["message" => "Database error: " . $stmt->error], 500);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $resource === 'orders' && $id) {
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        send_response(["message" => "Order deleted"]);
    } else {
        send_response(["message" => "Database error: " . $stmt->error], 500);
    }
}

$conn->close();
