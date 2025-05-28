<?php
// config/database.php

$host = 'localhost';
$db   = 'parisbea_paris_beauty';
$user = 'root';
$pass = "";
$charset = 'utf8mb4';

// $host = '91.204.209.19';
// $db   = 'parisbea_paris_beauty';
// $user = 'parisbea';
// $pass = "3eylgtdd!KN6";
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}


// Connect to Webserver
// Test

// $host = '91.204.209.19';
// $db   = 'payshiac_kdu_test';
// $user = 'payshiac';
// $pass = "1999tr@thilina";
// $charset = 'utf8mb4';
// $dsn = "mysql:host=$host;port=3306;dbname=$db;charset=$charset";