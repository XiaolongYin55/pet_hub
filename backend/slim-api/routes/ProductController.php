<?php
// routes/ProductController.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../services/ProductService.php';

return function ($app, $pdo) {
    $service = new ProductService($pdo);

    // ✅ 获取所有产品
    $app->get('/admin/get/products', function (Request $request, Response $response) use ($service) {
        $products = $service->getProducts();
        $response->getBody()->write(json_encode($products));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/user/get/products', function (Request $request, Response $response) use ($service) {
        $products = $service->getProducts();
        $response->getBody()->write(json_encode($products));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // ✅ 获取单个产品
    $app->get('/admin/get/product/{id}', function (Request $request, Response $response, array $args) use ($service) {
        $id = $args['id'];
        $product = $service->getProductById($id);

        if ($product) {
            $response->getBody()->write(json_encode($product));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(["error" => "Product not found"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    });

    // ✅ 添加产品
    $app->post('/admin/add/product', function (Request $request, Response $response) use ($service) {
        $data = json_decode($request->getBody()->getContents(), true);
        $success = $service->addProduct($data);

        $response->getBody()->write(json_encode([
            "message" => $success ? "Product added successfully" : "Failed to add product"
        ]));
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus($success ? 201 : 500);
    });

    // ✅ 更新产品
    $app->post('/admin/update/product', function (Request $request, Response $response) use ($service) {
        $data = json_decode($request->getBody()->getContents(), true);

        if (!isset($data['id'])) {
            $response->getBody()->write(json_encode(["error" => "Missing product ID"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $success = $service->updateProduct($data);
        $response->getBody()->write(json_encode([
            "message" => $success ? "Product updated successfully" : "Failed to update product"
        ]));
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus($success ? 200 : 500);
    });

    // ✅ 删除产品
    $app->delete('/admin/delete/product/{id}', function (Request $request, Response $response, array $args) use ($service) {
        $id = $args['id'];
        $success = $service->deleteProduct($id);

        $response->getBody()->write(json_encode([
            "message" => $success ? "Product deleted successfully" : "Failed to delete product"
        ]));
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus($success ? 200 : 500);
    });
};
