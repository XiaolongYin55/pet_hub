<?php
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
//     $targetDir = __DIR__ . '/oss/images/';
//     $filename = uniqid() . '_' . basename($_FILES['file']['name']);
//     $targetFile = $targetDir . $filename;

//     if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
//         echo json_encode(["imagePath" => "oss/images/" . $filename]);
//     } else {
//         http_response_code(500);
//         echo json_encode(["error" => "Failed to move uploaded file."]);
//     }
// } else {
//     http_response_code(400);
//     echo json_encode(["error" => "Invalid request."]);
// }