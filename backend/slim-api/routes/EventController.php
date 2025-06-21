<?php
// routes/EventController.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../services/EventService.php';

return function ($app, $pdo) {
    $service = new EventService($pdo);

    // 获取所有事件
    $app->get('/admin/get/events', function (Request $request, Response $response) use ($service) {
        $events = $service->getEvents();
        $response->getBody()->write(json_encode($events));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // 根据 ID 获取单个事件
    $app->get('/admin/get/event/{id}', function (Request $request, Response $response, array $args) use ($service) {
        $id = $args['id'];
        $event = $service->getEventById($id);

        if ($event) {
            $response->getBody()->write(json_encode($event));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(["error" => "Event not found"]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
    });

    $app->post('/admin/add/event', function (Request $request, Response $response) use ($service) {
        $data = json_decode($request->getBody()->getContents(), true);
        $service->addEvent($data);
        $response->getBody()->write(json_encode(["message" => "Event created successfully"]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->post('/admin/update/event', function (Request $request, Response $response) use ($service) {
        $data = json_decode($request->getBody()->getContents(), true);

        if (!isset($data['event_id'])) {
            $response->getBody()->write(json_encode(["error" => "Missing event ID"]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $service->updateEvent($data['event_id'], $data);
        $response->getBody()->write(json_encode(["message" => "Event updated successfully"]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->delete('/admin/delete/event/{id}', function (Request $request, Response $response, array $args) use ($service) {
        $service->deleteEvent($args['id']);
        $response->getBody()->write(json_encode(["message" => "Event deleted"]));
        return $response->withHeader('Content-Type', 'application/json');
    });
};
