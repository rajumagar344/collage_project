<?php
session_start();
include ('../includes/db.php');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Fetch available products
$products = $pdo->query("SELECT id,  quantity FROM products WHERE quantity > 0")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Sale = Inventory Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Include Sidebar -->
     <?php include ('../pages/sidebar.php'); ?>
<!-- Display Success/Error Messages -->
<?php if (isset($_SESSION['success'])) { ?>
        <div class="alert alert-success"><?= $_SESSION['success']; ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php } ?>

    <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php } ?>

    <form action="process_sale.php" method="POST">
        <div class="mb-3">
            <label for="product" class="form-label">Select Product</label>
            <select class="form-select" id="product" name="product_id" required>
                <option value="">-- Select Product --</option>
                <?php foreach ($products as $product) { ?>
                    <option value="<?= $product['id']; ?>">
                        <?= $product['product_name']; ?> (Stock: <?= $product['quantity']; ?>)
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Total Amount</label>
            <input type="number" class="form-control" id="amount" name="amount" required>
        </div>

        <button type="submit" class="btn btn-primary">Sell Product</button>
        <a href="sales.php" class="btn btn-secondary">Back to Sales</a>
    </form>
     </div>
</body>
</html>