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

// Fetch Products Data
$stmt = $pdo->query("SELECT id, name, quantity FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get sales data for the last 6 months, grouped by month
$query_sales = "SELECT DATE_FORMAT(sale_date, '%Y-%m') as month, SUM(total_price) as total_sales
                FROM sales
                WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY month
                ORDER BY month";
                
$stmt_sales = $pdo->prepare($query_sales);
$stmt_sales->execute();
$salesData = $stmt_sales->fetchAll(PDO::FETCH_ASSOC);

// Format data for the sales chart
$salesLabels = [];
$salesValues = [];

foreach ($salesData as $row) {
    $salesLabels[] = $row['month']; // The formatted month (e.g., 2025-03)
    $salesValues[] = $row['total_sales']; // The total sales for that month
}

// Get purchase data for the last 6 months, grouped by month
$query_purchase = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(quantity) as total_purchases
                   FROM products
                   WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                   GROUP BY month
                   ORDER BY month";

$stmt_purchase = $pdo->prepare($query_purchase);
$stmt_purchase->execute();
$purchaseData = $stmt_purchase->fetchAll(PDO::FETCH_ASSOC);

// Format data for the purchase chart
$purchaseLabels = [];
$purchaseValues = [];

foreach ($purchaseData as $row) {
    $purchaseLabels[] = $row['month'];  // This will hold the formatted months like '2025-03'
    $purchaseValues[] = $row['total_purchases'];  // This will hold the total quantity purchased for each month
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
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
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
        /* Available Products */
        /* General Styles for Available Products List */
.card1 {
    background: linear-gradient(135deg, #6a11cb, #2575fc); /* Gradient background */
    padding: 20px;
    border-radius: 15px; /* Rounded corners */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Shadow for depth */
    margin-top: 20px; /* Space from previous content */
    color: white;
}

.card1 h4 {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 15px;
}

.list-group {
    padding-left: 0; /* Remove default padding */
}

.list-group-item {
    background-color: transparent; /* Transparent background for list items */
    border: none; /* Remove border */
    padding: 15px;
    color: white;
    font-size: 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1); /* Light bottom border */
}

.list-group-item .badge {
    font-size: 14px;
    background-color: #00bcd4; /* Teal background for the stock badge */
}

.list-group-item:hover {
    background-color: rgba(255, 255, 255, 0.1); /* Hover effect for list item */
    cursor: pointer;
}

.list-group-item .badge {
    font-size: 14px;
    background-color: #ff5722; /* Stock badge color */
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
        <!-- Available Products Lists -->
         <div class="card1">
            <h4>Available Products
                <ul class="list-group">
                    <?php foreach ($products as $product): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($product['name']); ?>
                            <span class="badge bg-primary rounded-pill"><?= $product['quantity']; ?> in stock</span>
                        </li>
                        <?php endforeach; ?>
                </ul>
            </h4>
         </div>

        <!-- Chart Container -->
<div id="salesChart"></div>
<div class="container">
        <h1 class="my-4">Sales Data $ Purchase Data (Last 7 Days)</h1>
        <!-- Chart Container -->
        <div id="combinedChart" style="width: 100%; height: 350px;"></div>
        </div>
</div>
<script>
    var salesLabels = <?= json_encode($salesLabels); ?>;
    var salesValues = <?= json_encode($salesValues); ?>;
    var purchaseLabels = <?= json_encode($purchaseLabels); ?>;
    var purchaseValues = <?= json_encode($purchaseValues); ?>;

    // Check if both datasets have the same length
    console.log("Sales Labels:", salesLabels);
    console.log("Sales Values:", salesValues);
    console.log("Purchase Labels:", purchaseLabels);
    console.log("Purchase Values:", purchaseValues);

    if (salesLabels.length !== purchaseLabels.length) {
        console.warn("Warning: Sales Labels and Purchase Labels have different lengths.");
    }

    // Use salesLabels for the x-axis (assuming the labels are the same)
    var combinedChartOptions = {
    series: [
        {
            name: 'Total Sales',
            data: salesValues
        },
        {
            name: 'Total Purchases',
            data: purchaseValues
        }
    ],
    chart: {
        type: 'line',
        height: 350,
        toolbar: {
            show: false // Disables the toolbar
        }
    },
    plotOptions: {
        bar: {
            columnWidth: '50%'  // Adjust the width of the bars to prevent overlap
        }
    },
    xaxis: {
        categories: salesLabels,  // Use salesLabels for x-axis
    },
    colors: ["#007bff", "#28a745"], // Blue for Sales, Green for Purchases
    title: {
        text: 'Sales vs Purchases',
        align: 'center'
    },
    legend: {
        position: 'top', // Display the legend at the top
    }
};


    // Render the combined chart
    var combinedChart = new ApexCharts(document.querySelector("#combinedChart"), combinedChartOptions);
    combinedChart.render();
</script>

</body>
</html>
