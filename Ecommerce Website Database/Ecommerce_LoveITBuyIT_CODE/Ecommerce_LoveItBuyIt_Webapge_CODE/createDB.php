<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "shopping_site";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_create_db = "CREATE DATABASE IF NOT EXISTS lab06";
if ($conn->query($sql_create_db) === FALSE) {
    echo "Error creating database: " . $conn->error;
}

$conn->select_db("lab06");

$sql_create_table = "CREATE TABLE IF NOT EXISTS customer (
    cust_id INT NOT NULL AUTO_INCREMENT,
    PASSWORD VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    NUMBER VARCHAR(20) NOT NULL,
    country VARCHAR(50) NOT NULL,
    province VARCHAR(50) NOT NULL,
    address VARCHAR(255) NOT NULL,
    profile_picture BLOB,
    PRIMARY KEY (cust_id),
    UNIQUE KEY email (email)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";

if ($conn->query($sql_create_table) === FALSE) {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
