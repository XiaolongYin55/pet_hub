<?php
// routes/OrderController.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../services/OrderService.php';

return function ($app, $pdo) {
    $service = new OrderService($pdo);

    // 获取所有订单
    $app->get('/admin/get/orders', function (Request $request, Response $response) use ($service) {
        $orders = $service->getOrders();
        $response->getBody()->write(json_encode($orders));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // 获取单个订单
    $app->get('/admin/get/order/{id}', function (Request $request, Response $response, array $args) use ($service) {
        $id = $args['id'];
        $order = $service->getOrderById($id);

        if ($order) {
            $response->getBody()->write(json_encode($order));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(["error" => "Order not found"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    });

    $app->get('/user/get_order/by_username/{username}', function (Request $request, Response $response, array $args) use ($service) {
    $username = $args['username'];
    $order = $service->getOrderByUsername($username);

    if ($order) {
        $response->getBody()->write(json_encode($order));
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        $response->getBody()->write(json_encode(["error" => "No order found for username: $username"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
});

    // 添加订单
$app->post('/user/add/order', function (Request $request, Response $response) use ($service) {
    $data = json_decode($request->getBody()->getContents(), true);
    $orderId = $service->addOrder($data);

    $response->getBody()->write(json_encode([
        "message" => "Order created",
        "order_id" => $orderId
    ]));
    return $response->withHeader('Content-Type', 'application/json');
});

// 更新订单
$app->post('/admin/update/order', function (Request $request, Response $response) use ($service) {
    $data = json_decode($request->getBody()->getContents(), true);

    if (!isset($data['order_id'])) {
        $response->getBody()->write(json_encode(["error" => "Missing order_id"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $success = $service->updateOrder($data);

    $response->getBody()->write(json_encode([
        "message" => $success ? "Order updated" : "Update failed"
    ]));
    return $response->withHeader('Content-Type', 'application/json');
});

// 删除订单
$app->delete('/admin/delete/order/{id}', function (Request $request, Response $response, array $args) use ($service) {
    $id = $args['id'];
    $success = $service->deleteOrder($id);

    $response->getBody()->write(json_encode([
        "message" => $success ? "Order deleted" : "Delete failed"
    ]));
    return $response->withHeader('Content-Type', 'application/json');
});

};
