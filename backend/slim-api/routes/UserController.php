<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../services/UserService.php';

return function ($app, $pdo) {
    $service = new UserService($pdo);
    // 获取所有用户
    $app->get('/admin/get/users', function (Request $request, Response $response) use ($service) {
        $users = $service->getUsers();
        $response->getBody()->write(json_encode($users));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // ✅ 通过 ID 获取单个用户（包含 address）
    $app->get('/admin/get/user/{id}', function (Request $request, Response $response, array $args) use ($service) {
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

        $app->get('/user/get/user/{id}', function (Request $request, Response $response, array $args) use ($service) {
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

    // ✅ 添加 POST /users/update 到 routes/UserController.php
    $app->post('/admin/update/user', function (Request $request, Response $response) use ($service) {
        $data = json_decode($request->getBody()->getContents(), true);

        if (!isset($data['id'])) {
            $response->getBody()->write(json_encode(["error" => "Missing user ID"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $success = $service->updateUserWithAddress($data);

        if ($success) {
            $response->getBody()->write(json_encode(["message" => "User and address updated"]));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(["error" => "Update failed"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

        // ✅ 添加 POST /users/update 到 routes/UserController.php
    $app->post('/user/update', function (Request $request, Response $response) use ($service) {
        $data = json_decode($request->getBody()->getContents(), true);

        if (!isset($data['id'])) {
            $response->getBody()->write(json_encode(["error" => "Missing user ID"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $success = $service->updateUserWithAddress($data);

        if ($success) {
            $response->getBody()->write(json_encode(["message" => "User and address updated"]));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(["error" => "Update failed"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    // 添加新用户（含 address）
    $app->post('/admin/add/user', function (Request $request, Response $response) use ($service) {
        $data = json_decode($request->getBody()->getContents(), true);
        $result = $service->addUserWithAddress($data);

        if ($result) {
            $response->getBody()->write(json_encode(["message" => "User added successfully"]));
        } else {
            $response->getBody()->write(json_encode(["error" => "Failed to add user"]));
            return $response->withStatus(500);
        }

        return $response->withHeader('Content-Type', 'application/json');
    });

    // 删除用户
$app->delete('/admin/delete/user/{id}', function (Request $request, Response $response, array $args) use ($service) {
    $id = $args['id'];
    $result = $service->deleteUserById($id);

    if ($result) {
        $response->getBody()->write(json_encode(["message" => "User deleted successfully"]));
        return $response->withHeader('Content-Type', 'application/json'); // ✅ 要加这个
    } else {
        $response->getBody()->write(json_encode(["error" => "Failed to delete user"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

};
