<?php
include('../includes/db.php');

$stmt = $pdo->query("SELECT DATE(sale_date) as sale_day, SUM(total_price) as total_sales
                     FROM sales GROUP BY DATE(sale_date)");
$sales_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<!-- Include Sidebar -->

<div class="container mt-5">
    <h2>Sales Report</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Total Sales (Rs.)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sales_data as $row) { ?>
                <tr>
                    <td><?= $row['sale_day'] ?></td>
                    <td>Rs. <?= $row['total_sales'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
