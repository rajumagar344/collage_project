<?php
session_start();
include('../includes/db.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}

// Fetch all products from the database
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle all submission for adding a products
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['category'])) {
        die ("please select a category.");
    }

    $name = $_POST['product_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Insert new products into the database
    $stmt = $pdo->prepare("INSERT INTO products (name, quantity, price, category_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $quantity, $price, $category]);

    header("Location: products.php");
    exit();

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            background: #f4f6f9;
            font-family: 'Poppins', sans-serif;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            background: white;

            max-width: 500px;
            margin: auto;
            padding: 20px;

        }
        .table thead {
            background: linear-gradient(to right, #007bff, #0056b3);
            color: white;
        }
        .table tbody tr:hover {
            background: rgba(0, 123, 255, 0.1);
        }
        .btn {
            border-radius: 5px;
            padding: 6px 12px;
            font-size: 14px;
            transition: 0.3s;
        }
        .btn-add {
            background: #007bff;
            color: white;
        }
        .btn-add:hover {
            background: #0056b3;
        }
        .btn-edit {
            background-color: #28a745;
            color: white;
        }
        .btn-edit:hover {
            background-color: #218838;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c82333;
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

<!-- Include Sidebar -->
<?php include('sidebar.php'); ?>

<!-- Main Content -->
<div class="main-content">
    <h2 class="mb-4">Product Management</h2>

    <!-- Add Product Form -->
     <div class="card">
        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="product_name" class="form-control" required>
            </div>
            <div class="mb3">
                <label class="form-label">Category</label>
                <select name="category" class="form-control category-select" required>
                    <option value="">-- Select Category --</option>
                    <?php
                    $stmt = $pdo->query("SELECT id, name FROM categories");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="">Quantity</label>
                <input type="number" name="quantity" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="">Price</label>
                <input type="number" name="price" step="0.01" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Products</button>
        </form>
     </div>
            <!-- Product Table -->
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Date</th>
                    <th>Actions</th>

                </tr>
            </thead>
            <tbody>
                <?php
                // SQL query to fetch product details along with category names
                $query = "SELECT p.id, p.name, p.quantity, p.price, p.created_at, c.name AS category_name
                          FROM products p
                          LEFT JOIN categories c ON p.category_id = c.id";
                $stmt = $pdo->prepare($query);
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['category_name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>Rs. {$row['price']}</td>
                        <td>{$row['created_at']}</td>
                        <td>
                            <a href='edit_product.php?id={$row['id']}' class='btn btn-edit'><i class='fas fa-edit'></i> Edit</a>
                            <a href='delete_product.php?id={$row['id']}' class='btn btn-delete' onclick='return confirm(\"Are you sure?\")'><i class='fas fa-trash'></i> Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
</div>

</body>
</html>
<style>
        