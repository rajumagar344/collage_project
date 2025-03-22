<?php

$host = 'localhost';
$dbname = 'inventory_system';
$username = 'root';
$password = '';

try {
    // Create a PDO instance and set the connection options
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set the PDO error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: set the default character set to UTF-8
    $pdo->exec("SET NAMES 'utf8'");

} catch (PDOException $e) {
    // If there is an error, display it
    echo "Connection failed: ".$e->getMessage();
    exit;
}

?>