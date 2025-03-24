<?php
session_start();
include('../includes/db.php');

// Check if the sale ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Sale ID is required!");
}

$sale_id = $_GET['id'];

// Fetch the sale details
$stmt = $pdo->prepare("SELECT * FROM sales WHERE id = ?");
$stmt->execute([$sale_id]);
$sale = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sale) {
    die("Sale not found!");
}

// Fetch all products for the product selection
$products_stmt = $pdo->query("SELECT * FROM products");
$products = $products_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission to update sale
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $buyer_name = $_POST['buyer_name'];
    $buyer_phone = $_POST['buyer_phone'];
    $buyer_address = $_POST['buyer_address'];

    // Fetch the selected product's price
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product not found!");
    }

    $total_price = $quantity * $product['price'];

    // Update the sale record
    $stmt = $pdo->prepare("UPDATE sales SET product_id = ?, quantity = ?, total_price = ?, buyer_name = ?, buyer_phone = ?, buyer_address = ? WHERE id = ?");
    $stmt->execute([$product_id, $quantity, $total_price, $buyer_name, $buyer_phone, $buyer_address, $sale_id]);

    header("Location: sales.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Sale</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
<style>
     body {
            display: flex;
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
        }
</style>
</head>
<body>
    <?php include('sidebar.php'); ?>

    <div class="container mt-5">
        <h2>Edit Sale</h2>
        <div class="card">
            <div class="card-body">
                <form action="edit_sale.php?id=<?= $sale['id'] ?>" method="POST">
                    <!-- Select Product -->
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Select Product</label>
                        <select name="product_id" id="product_id" class="form-control" required>
                            <?php foreach ($products as $product) { ?>
                                <option value="<?= $product['id'] ?>" <?= $product['id'] == $sale['product_id'] ? 'selected' : '' ?>><?= $product['name'] ?> (Stock: <?= $product['quantity'] ?>)</option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Quantity -->
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Select Quantity</label>
                        <select name="quantity" class="form-control" required>
                                <option value="">-- Select Quantity --</option>
                             <?php
                             $selectedProduct = $products[0]; 
                             for ($i = 1; $i <= $selectedProduct['quantity']; $i++) { ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php } ?>
                         </select>                    </div>

                    <!-- Buyer Name -->
                    <div class="mb-3">
                        <label for="buyer_name" class="form-label">Buyer Name</label>
                        <input type="text" name="buyer_name" id="buyer_name" class="form-control" value="<?= $sale['buyer_name'] ?>" required>
                    </div>

                    <!-- Buyer Phone -->
                    <div class="mb-3">
                        <label for="buyer_phone" class="form-label">Phone Number</label>
                        <input type="text" name="buyer_phone" id="buyer_phone" class="form-control" value="<?= $sale['buyer_phone'] ?>" required>
                    </div>

                    <!-- Buyer Address -->
                    <div class="mb-3">
                        <label for="buyer_address" class="form-label">Address</label>
                        <textarea name="buyer_address" id="buyer_address" class="form-control" rows="3" required><?= $sale['buyer_address'] ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Sale</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
