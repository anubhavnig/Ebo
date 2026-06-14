<?php
// Database Configuration
$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'ebostay_tours';

// Create connection
$conn = new mysqli($host, $db_user, $db_pass);

// Check connection
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if ($conn->query($sql) === TRUE) {
    $conn->select_db($db_name);
} else {
    die('Error creating database: ' . $conn->error);
}

// Connect to the database
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// Set charset
$conn->set_charset('utf8mb4');

?>