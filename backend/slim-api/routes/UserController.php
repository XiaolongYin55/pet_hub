<?php
// routes/UserController.php
// routes/UserController.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../services/UserService.php';

return function ($app, $pdo) {
    $service = new UserService($pdo);

    // 获取所有用户
    $app->get('/users', function (Request $request, Response $response) use ($service) {
        $users = $service->getUsers();
        $response->getBody()->write(json_encode($users));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // ✅ 通过 ID 获取单个用户（包含 address）
    $app->get('/users/{id}', function (Request $request, Response $response, array $args) use ($service) {
        $id = $args['id'];
        $user = $service->getUserById($id);

        if ($user) {
            $response->getBody()->write(json_encode($user));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(["error" => "User not found"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    });
};
