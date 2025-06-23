<?php
// backend/slim-api/routes/UploadController.php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function ($app) {
    $app->post('/upload/image', function (Request $request, Response $response) {
        $directory = __DIR__ . '/../public/oss/images'; // 实际上传目录
        $uploadedFiles = $request->getUploadedFiles();

        if (empty($uploadedFiles['file'])) {
            $response->getBody()->write(json_encode(['error' => 'No file uploaded']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $file = $uploadedFiles['file'];

        if ($file->getError() === UPLOAD_ERR_OK) {
            $filename = uniqid() . '_' . $file->getClientFilename();
            $file->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

            // ✅ 返回相对路径 oss/images/xxx.jpg
            $imagePath = "oss/images/" . $filename;

            $response->getBody()->write(json_encode(['imagePath' => $imagePath]));
            return $response->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode(['error' => 'Upload failed']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    });

    $app->post('/delete/image', function ($request, $response) {
    $data = json_decode($request->getBody()->getContents(), true);
    $path = $data['path'] ?? '';

    if ($path && file_exists(__DIR__ . "/../public/" . $path)) {
        unlink(__DIR__ . "/../public/" . $path);
        $response->getBody()->write(json_encode(['message' => 'Image deleted']));
    } else {
        $response->getBody()->write(json_encode(['error' => 'Image not found']));
        return $response->withStatus(404);
    }

    return $response->withHeader('Content-Type', 'application/json');
});
};
