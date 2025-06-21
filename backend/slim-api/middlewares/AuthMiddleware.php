<?php
require_once __DIR__ . '/../jwt.php';

// function AuthMiddleware($request, $handler) {
//     $authHeader = $request->getHeaderLine('Authorization');

//     if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
//         $response = new \Slim\Psr7\Response();
//         $response->getBody()->write(json_encode(['error' => 'Missing or invalid token']));
//         return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
//     }

//     $token = str_replace('Bearer ', '', $authHeader);
//     $userData = JwtHandler::validateToken($token);

//     if (!$userData) {
//         $response = new \Slim\Psr7\Response();
//         $response->getBody()->write(json_encode(['error' => 'Invalid or expired token']));
//         return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
//     }

//     // ✅ 将 user 数据附加到请求中
//     $request = $request->withAttribute('user', $userData);

//     // ✅ 继续处理请求
//     return $handler->handle($request);
// }
