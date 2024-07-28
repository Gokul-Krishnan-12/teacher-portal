<?php
// db.php

require_once __DIR__ . '/../vendor/autoload.php'; // Load Composer's autoloader

use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__. '/..');
$dotenv->load();

// Database connection variables
$host = $_ENV['DB_HOST'];
$db_name = $_ENV['DB_DATABASE'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

return $conn;
?>
