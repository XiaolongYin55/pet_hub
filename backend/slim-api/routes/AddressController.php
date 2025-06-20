<?php
require_once __DIR__ . '/../services/AddressService.php';

return function ($app, $conn) {
    $addressService = new AddressService($conn);

    // ✅ GET /addresses/{user_id}
    $app->get('/addresses/{user_id}', function ($request, $response, $args) use ($addressService) {
        $userId = $args['user_id'];
        $address = $addressService->getAddressByUserId($userId);

        if ($address) {
            $response->getBody()->write(json_encode($address));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['error' => 'Address not found']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    });
};
