<?php
// repositories/AuthRepository.php

// class AuthRepository {
//     private $pdo;

//     public function __construct($pdo) {
//         $this->pdo = $pdo;
//     }

//     public function findUserByUsernameAndPassword($username, $password) {
//         $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
//         $stmt->execute([
//             ':username' => $username,
//             ':password' => $password
//         ]);
//         return $stmt->fetch(PDO::FETCH_ASSOC);
//     }
// }
