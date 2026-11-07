<?php
declare(strict_types=1);
$host = '127.0.0.1';
$db   = 'hostel_room_allocation';
$user = 'root';
$pass = ''; // Default XAMPP local setup. Add your password here only if you configured one.
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    die("Database connection failed. Check that MySQL is running in XAMPP and that database/hostel_room_allocation.sql has been imported. Details: " . htmlspecialchars($e->getMessage()));
}
