<?php
session_start();
include('../includes/db.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}
// invoice.php
$sale_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT sales.*, products.name AS product_name 
                       FROM sales 
                       JOIN products ON sales.product_id = products.id 
                       WHERE sales.id = ?");
$stmt->execute([$sale_id]);
$sale = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            display: flex;
            background: #f4f6f9;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 220px; /* Assuming sidebar has fixed width */
        }

        .invoice {
            width: 80%;
            margin: auto;
            border: 1px solid #ddd;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }

        .invoice-header h1 {
            font-size: 36px;
            color: #333;
        }

        .invoice-header p {
            font-size: 18px;
            color: #555;
        }

        .invoice-details {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .invoice-details td {
            padding: 12px;
            border-bottom: 1px solid #f4f4f4;
            text-align: left;
            font-size: 16px;
        }

        .invoice-details td strong {
            color: #333;
            font-weight: bold;
        }

        .invoice-details tr:hover {
            background-color: #f1f1f1;
        }

        .total-price {
            text-align: right;
            font-size: 20px;
            color: #000;
            font-weight: bold;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            display: inline-block;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .invoice {
                width: 90%;
            }

            .invoice-header h1 {
                font-size: 28px;
            }

            .invoice-details td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <?php include('sidebar.php'); ?>

    <div class="main-content">
        <div class="invoice">
            <div class="invoice-header">
                <h1>Invoice</h1>
                <p>Sale ID: <?= $sale['id'] ?></p>
            </div>
            <table class="invoice-details">
                <tr>
                    <td><strong>Product Name:</strong> <?= $sale['product_name'] ?></td>
                    <td><strong>Quantity:</strong> <?= $sale['quantity'] ?></td>
                </tr>
                <tr>
                    <td><strong>Price:</strong> Rs. <?= number_format($sale['total_price'], 2) ?></td>
                    <td><strong>Buyer:</strong> <?= $sale['buyer_name'] ?></td>
                </tr>
                <tr>
                    <td><strong>Phone:</strong> <?= $sale['buyer_phone'] ?></td>
                    <td><strong>Address:</strong> <?= $sale['buyer_address'] ?></td>
                </tr>
            </table>
            
            <div class="total-price">
                <p><strong>Total Price:</strong> Rs. <?= number_format($sale['total_price'], 2) ?></p>
            </div>

            <a href="index.php" class="btn">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
