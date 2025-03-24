<?php
include ('../includes/db.php');

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect to login page
    exit();
}

$username = $_SESSION['username']; // Fetch logged-in user's username
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">


    <style>
        .sidebar {
    width: 250px;
    height: 100vh;
    background: linear-gradient(to bottom, #0f2027, #203a43, #2c5364);
    color: white;
    padding: 20px;
    box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.5), -5px -5px 15px rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease-in-out;
}

.sidebar:hover {
    width: 280px;
    background: linear-gradient(to bottom, #1e3c72, #2a5298);
}

.sidebar a {
    color: white;
    text-decoration: none;
    display: block;
    padding: 12px;
    font-size: 16px;
    transition: all 0.3s ease-in-out;
    border-radius: 8px;
}

.sidebar a:hover {
    background: rgba(28, 194, 180, 0.89);
    box-shadow: 5px 6px 20px rgba(88, 202, 236, 0.2);
    transform: scale(1.05);
    border-radius: 12px;
}

    </style>
</head>
<body>
    
<!-- Sidebar Design -->
<div class="sidebar">
    <h4>INVENTORY SYSTEM</h4>
    <p>Welcome, <strong><?= htmlspecialchars($username); ?></strong>!</p>

    <a href="dashboard.php" class="nav-link text-white"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="./users.php" class="nav-link text-white"><i class="fas fa-users"></i>User Management</a>
    <a href="categories.php" class="nav-link text-white"><i class="fas fa-th-list"></i> Categories</a>
    <a href="products.php" class="nav-link text-white"><i class="fas fa-boxes"></i> Products</a>
    <a href="sales.php" class="nav-link text-white"><i class="fas fa-shopping-cart"></i> Sales</a>
    <a href="reports.php" class="nav-link text-white"><i class="fas fa-chart-line"></i> Reports</a>
    <a href="logout.php" class="nav-link text-white"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>
</body>
</html>
