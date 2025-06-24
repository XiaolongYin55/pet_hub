<?php
// routes/ContractController.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../services/ContractService.php';

return function ($app, $pdo) {
    $service = new ContractService($pdo);

    // 获取所有 contracts
    $app->get('/user/contracts', function (Request $request, Response $response) use ($service) {
        $contracts = $service->getContracts();
        $response->getBody()->write(json_encode($contracts));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // 获取单个 contract
    $app->get('/user/get/contract/{id}', function (Request $request, Response $response, array $args) use ($service) {
        $id = $args['id'];
        $contract = $service->getContractById($id);

        if ($contract) {
            $response->getBody()->write(json_encode($contract));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(["error" => "Contract not found"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    });
    $app->post('/user/add/contract', function (Request $request, Response $response) use ($service) {
        $data = json_decode($request->getBody()->getContents(), true);
        $result = $service->addContract($data);

        if ($result) {
            $response->getBody()->write(json_encode(["message" => "Contract created"]));
        } else {
            $response->getBody()->write(json_encode(["error" => "Failed to create contract"]));
            return $response->withStatus(500);
        }

        return $response->withHeader('Content-Type', 'application/json');
    });

// routes/ContractController.php
$app->get('/user/get/contracts/by_user/{user_id}', function (Request $request, Response $response, array $args) use ($service) {
    $userId = $args['user_id'];
    $contracts = $service->getContractsByUserId($userId);

    if ($contracts && count($contracts) > 0) {
        $response->getBody()->write(json_encode($contracts));
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        $response->getBody()->write(json_encode(["error" => "No contracts found for user_id: $userId"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
});


    // ✅ 添加 POST /update/contract 到 routes/ContractController.php
    $app->post('/user/update/contract', function (Request $request, Response $response) use ($service) {
        $data = json_decode($request->getBody()->getContents(), true);

        if (!isset($data['id'])) {
            $response->getBody()->write(json_encode(["error" => "Missing contract ID"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $success = $service->updateContract($data['id'], $data);

        if ($success) {
            $response->getBody()->write(json_encode(["message" => "Contract updated successfully"]));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(["error" => "Contract update failed"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    $app->delete('/user/delete/contract/{id}', function (Request $request, Response $response, array $args) use ($service) {
        $id = $args['id'];
        $result = $service->deleteContract($id);

        if ($result) {
            $response->getBody()->write(json_encode(["message" => "Contract deleted"]));
        } else {
            $response->getBody()->write(json_encode(["error" => "Failed to delete contract"]));
            return $response->withStatus(500);
        }

        return $response->withHeader('Content-Type', 'application/json');
    });
};
