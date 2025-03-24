<?php
session_start();
include('../includes/db.php');

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Sales Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $buyer_name = $_POST['buyer_name'];
    $buyer_phone = $_POST['buyer_phone'];
    $buyer_address = $_POST['buyer_address'];

    // Fetch Product Price
    $stmt = $pdo->prepare("SELECT price, quantity FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product || $product['quantity'] < $quantity) {
        die("Error: Not enough stock!");
    }

    $total_price = $quantity * $product['price'];

    // Insert Sale Record with Buyers Details
    $stmt = $pdo->prepare("INSERT INTO sales (product_id, quantity, total_price, buyer_name, buyer_phone, buyer_address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$product_id, $quantity, $total_price, $buyer_name, $buyer_phone, $buyer_address]);

    // Update Product Stock
    $stmt = $pdo->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
    $stmt->execute([$quantity, $product_id]);

    header("Location: sales.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            background: #f4f6f9;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
        }
        .card {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            background: white;
        }
        .btn-primary {
            width: 100%;
            background: #007bff;
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .table-striped {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table-striped th, .table-striped td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .table-striped th {
            background-color: #007bff;
            color: white;
        }
        .table-striped tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
<?php include('sidebar.php'); ?>

<div class="main-content">
    <h2 class="mb-4">Sales Management</h2>
    
    <!-- Add Sales Form -->
     <div class="card">
    <form action="" method="POST">
        <div class="mb-3">
            <label class="form-label">Select Product</label>
            <select name="product_id" class="form-control" required>
                <option value="">-- Select Product --</option>
                <?php foreach ($products as $product) { ?>
                    <option value="<?= $product['id'] ?>"><?= $product['name'] ?> (Stock: <?= $product['quantity'] ?>)</option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
        <label class="form-label">Select Quantity</label>
        <select name="quantity" class="form-control" required>
            <option value="">-- Select Quantity --</option>
            <?php
            $selectedProduct = $products[0]; 
            for ($i = 1; $i <= $selectedProduct['quantity']; $i++) { ?>
                <option value="<?= $i ?>"><?= $i ?></option>
            <?php } ?>
        </select>
    </div>

                <!-- Buyers Details -->
        <div class="mb-3">
            <label class="form-label">Buyer's Name</label>
            <input type="text" name="buyer_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" name="buyer_phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="buyer_address" class="form-control" rows="3" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Record Sale</button>
    </form>
        </div>
    <h3 class="mt-4">Sales Records</h3>
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Buyer Name</th>
                <th>Buyer Phone</th>
                <th>Buyer Address</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch Sales Records
            $stmt = $pdo->query("SELECT sales.id, products.name, sales.quantity, sales.total_price, sales.buyer_name, buyer_phone, sales.buyer_address, sales.sale_date
                                 FROM sales JOIN products ON sales.product_id = products.id");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>Rs. {$row['total_price']}</td>
                         <td>{$row['buyer_name']}</td>
                        <td>{$row['buyer_phone']}</td>
                        <td>{$row['buyer_address']}</td>
                        <td>{$row['sale_date']}</td>
                        <td>
                            <a href='edit_sale.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete_sale.php?id={$row['id']}' class='btn btn-danger btn-sm' onClick='return confirm(\"Are You sure want to delete this sale?\")'>Delete</a>
                            <a href='invoice.php?id={$row['id']}' class='btn btn-info btn-sm'>Invoice</a>
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
