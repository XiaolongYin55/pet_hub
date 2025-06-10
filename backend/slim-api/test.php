<?php
require_once './config.php';

try {
    $db = new db();
    $conn = $db->connect();
    echo "Database connection successful!";
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}