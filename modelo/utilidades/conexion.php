<?php

/*$host = "localhost";
$dbname = "u151178620_healthy";
$username = "u151178620_root";
$password = "Healthy2025";
$charset = "utf8mb4";*/

/*$host = "10.20.41.160";
$dbname = "hel";
$username = "admin";
$password = "informatica";
$charset = "utf8mb4";*/

// Configuración local WAMP
$host = "localhost";
$dbname = "hel";
$username = "root";
$password = "";
$charset = "utf8mb4";

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
