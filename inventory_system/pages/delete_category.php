<?php
session_start();
include ('../includes/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Handle category deletion
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute delete query
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);

    // Redirect back to categories page after deletion
    header("Location: categories.php");
    exit();
} else {
    // Redirect if ID is not found
    header("Location: categories.php");
    exit();
}
?>
