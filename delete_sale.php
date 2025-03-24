<?php
session_start();
include('../includes/db.php');

// Check if the sale ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Sale ID is required!");
}

$sale_id = $_GET['id'];

// Fetch the sale to check if it exists
$stmt = $pdo->prepare("SELECT * FROM sales WHERE id = ?");
$stmt->execute([$sale_id]);
$sale = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sale) {
    die("Sale not found!");
}

// Delete the sale record
$stmt = $pdo->prepare("DELETE FROM sales WHERE id = ?");
$stmt->execute([$sale_id]);

header("Location: sales.php");
exit();
?>
