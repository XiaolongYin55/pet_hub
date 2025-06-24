<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../services/AuthService.php';

return function ($app, $pdo) {
    $authService = new AuthService($pdo);

$app->post('/auth/login', function (Request $request, Response $response) use ($authService) {
    $data = json_decode($request->getBody()->getContents(), true);
    $username = $data['username'] ?? null;
    $password = $data['password'] ?? null;

    if (!$username || !$password) {
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => 'Missing username or password'
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $result = $authService->login($username, $password);

    if ($result) {
        $response->getBody()->write(json_encode([
            'success' => true,
            'user' => [
                'id' => $result['id'],
                'username' => $result['username'],
                'role' => $result['role'],
                'email' => $result['email']
            ],
            'token' => $result['token']
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        $response->getBody()->write(json_encode([
            'success' => false,
            'message' => 'Invalid username or password'
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
});
$app->post('/register', function (Request $request, Response $response) use ($authService) {
    $data = json_decode($request->getBody()->getContents(), true);

    // 🔍 检查用户名是否已存在
    if ($authService->isUsernameExists($data['username'])) {
        $response->getBody()->write(json_encode(["error" => "Username already exists"]));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    // 添加用户
    $result = $authService->addUserWithAddress($data);

    if ($result) {
        $response->getBody()->write(json_encode(["message" => "Register success"]));
    } else {
        $response->getBody()->write(json_encode(["error" => "Failed to register user"]));
        return $response->withStatus(500);
    }

    return $response->withHeader('Content-Type', 'application/json');
});

};




