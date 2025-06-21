<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHandler {
    private static $secret = 'LAURENCE';
    private static $issuer = 'pet-hub.local';

    public static function generateToken($userId, $email, $role) {
        $payload = [
            'iss' => self::$issuer,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24), // 1天
            'data' => [
                'id' => $userId,
                'email' => $email,
                'role' => $role
            ]
        ];

        return JWT::encode($payload, self::$secret, 'HS256');
    }

    // 可选：用于将来验证
    public static function validateToken($jwt) {
        try {
            return JWT::decode($jwt, new Key(self::$secret, 'HS256'));
        } catch (Exception $e) {
            return null;
        }
    }
}
