<?php
session_start();
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $amount = $_POST['amount'];

    // Check available stock
    $stmt = $pdo->prepare("SELECT quantity FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product && $product['quantity'] >= $quantity) {
        // Insert sale record
        $insertSale = $pdo->prepare("INSERT INTO sales (product_id, quantity, amount) VALUES (?, ?, ?)");
        $insertSale->execute([$product_id, $quantity, $amount]);

        // Update stock (reduce quantity)
        $updateStock = $pdo->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
        $updateStock->execute([$quantity, $product_id]);

        $_SESSION['success'] = "Sale recorded successfully!";
    } else {
        $_SESSION['error'] = "Insufficient stock!";
    }

    header("Location: add_sale.php");
    exit();
}
?>
