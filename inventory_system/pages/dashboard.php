<?php
session_start();
include('../includes/db.php'); // Database Connection

// Total Products Count
$stmt = $pdo->query("SELECT COUNT(*) AS total FROM products");
$totalProducts = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Total Sales Count
$stmt = $pdo->query("SELECT SUM(quantity) AS total FROM sales");
$totalSales = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Total Users Count
$stmt = $pdo->query("SELECT COUNT(*) AS total FROM users");
$totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Total Categories Count
$stmt = $pdo->query("SELECT COUNT(*) AS total FROM categories");
$totalCategories = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Get sales data for the last 7 days
$query = "SELECT DATE(sale_date) as date, SUM(total_price) as total_sales 
          FROM sales 
          WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
          GROUP BY DATE(sale_date)
          ORDER BY date";

$stmt = $pdo->prepare($query);
$stmt->execute();
$salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format data for ApexCharts
$salesLabels = [];
$salesValues = [];

foreach ($salesData as $row) {
    $salesLabels[] = $row['date'];
    $salesValues[] = $row['total_sales'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Inventory Tracking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include ApexCharts Library -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        body {
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .container-wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            background: white;
            overflow-y: auto;
        }

        /* Dashboard Card Styles */
        .dashboard-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        .card {
            width: 250px;
            height: 150px;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 10px 10px 25px rgba(0, 0, 0, 0.3);
        }

        .card i {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .card h4 {
            font-size: 18px;
            margin: 0;
        }

        .card p {
            font-size: 24px;
            font-weight: bold;
        }

        /* Chart Container */
        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
            margin-top: 30px;
            width: 50;
        }

    </style>
</head>
<body>

<div class="container-wrapper">
    <?php include('../pages/sidebar.php'); ?>

    <div class="content">
        <h2 class="mb-4">📊 Dashboard - Inventory Tracking System</h2>

        <!-- Dashboard Cards -->
        <div class="dashboard-cards">
    <div class="card">
        <i class="fas fa-boxes"></i>
        <h4>Total Products</h4>
        <p><?= $totalProducts; ?></p>
    </div>
    <div class="card">
        <i class="fas fa-shopping-cart"></i>
        <h4>Total Sales</h4>
        <p><?= $totalSales; ?></p>
    </div>
    <div class="card">
        <i class="fas fa-user-tie"></i>  
        <h4>Total Users</h4>
        <p><?= $totalUsers; ?></p>
    </div>
    <div class="card">
        <i class="fas fa-th-list"></i>
        <h4>Total Categories</h4>
        <p><?= $totalCategories; ?></p>
    </div>
</div>


        <!-- Chart Container -->
<div id="salesChart" style="width: 600px; height: 400px;"></div>
</div>

<script>
// Convert PHP data to JavaScript
var salesLabels = <?= json_encode($salesLabels); ?>;
var salesValues = <?= json_encode($salesValues); ?>;

var options = {
    series: [{
        name: "Sales Amount (Rs.)",
        data: salesValues
    }],
    chart: {
        type: 'bar',
        height: 350
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
        }
    },
    dataLabels: { enabled: false },
    xaxis: { categories: salesLabels },
    colors: ["#007bff"], // Blue color theme
};

var chart = new ApexCharts(document.querySelector("#salesChart"), options);
chart.render();
</script>

</body>
</html>
