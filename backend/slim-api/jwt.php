<?php
require_once __DIR__ . '/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
error_log("Autoload loaded successfully!"); // 打印日志

class JwtHandler {
    private static $secret_key = "your_secret_key_here"; // 替换为强密钥
    private static $algorithm = 'HS256';
    
public static function generateToken($id, $email) {
    $payload = [
        'iss' => 'your_issuer',
        'aud' => 'your_audience',
        'iat' => time(),
        'exp' => time() + 3600,
        'data' => [
            'id' => $id,
            'email' => $email
        ]
    ];
    error_log("Payload Data: " . var_export($payload, true)); // 打印 payloa

    return JWT::encode($payload, self::$secret_key, self::$algorithm);
}

    
    public static function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key(self::$secret_key, self::$algorithm));
            return $decoded->data;
        } catch (Exception $e) {
            return false;
        }
    }
}
