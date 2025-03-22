<?php
include('../includes/db.php'); // Your database connection

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Delete the product from the database
    $delete_stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $delete_stmt->execute([$product_id]);

    // Redirect to the products list or a confirmation page
    header("Location: products.php");
    exit();
} else {
    die("Invalid product ID.");
}
?>
