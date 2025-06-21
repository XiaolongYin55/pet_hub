<?php
// routes/OrderItemController.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../services/OrderItemService.php';

return function ($app, $pdo) {
    $service = new OrderItemService($pdo);

    // GET /orders/{id}/items
    $app->get('/user/get_items/by_order/{id}', function (Request $request, Response $response, array $args) use ($service) {
        $orderId = $args['id'];
        $items = $service->getItemsByOrderId($orderId);

        if ($items) {
            $response->getBody()->write(json_encode($items));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(["error" => "No items found for this order"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    });

$app->post('/user/add_items/by_order/{order_id}', function (Request $request, Response $response, array $args) use ($service) {
    $orderId = $args['order_id'];
    $data = json_decode($request->getBody()->getContents(), true);

    if (!is_array($data)) {
        $response->getBody()->write(json_encode(["error" => "Items should be an array"]));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    $service->addItemsToOrder($orderId, $data);

    $response->getBody()->write(json_encode(["message" => "Items added to order {$orderId}"]));
    return $response->withHeader('Content-Type', 'application/json');
});


    // ✅ Delete single item
    $app->delete('/user/delete/item/{item_id}', function (Request $request, Response $response, array $args) use ($service) {
        $service->deleteItem($args['item_id']);
        $response->getBody()->write(json_encode(["message" => "Item deleted"]));
        return $response->withHeader('Content-Type', 'application/json');
    });
};
