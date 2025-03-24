<?php
session_start();
include ('../includes/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Fetch all categories from the database
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission for adding a category
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category_name'])) {
    $category_name = $_POST['category_name'];

    // Insert new category into the database
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->execute([$category_name]);

    header("Location: categories.php"); // Refresh the page to show the new category
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>
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
        .category-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .category-table th, .category-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .category-table th {
            background-color: #007bff;
            color: white;
        }
        .category-table tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<!-- Include Sidebar -->
<?php include('sidebar.php'); ?>

<!-- Main Content -->
<div class="main-content">
    <h2 class="mb-4">Manage Categories</h2>

    <!-- Add Category Form -->
    <div class="card">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Category Name</label>
                <input type="text" name="category_name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
        </form>
    </div>

    <!-- Category Table -->
    <table class="category-table mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= htmlspecialchars($category['id']); ?></td>
                    <td><?= htmlspecialchars($category['name']); ?></td>
                    <td><?= htmlspecialchars($category['created_at']); ?></td>
                    <td>
                        <a href="edit_category.php?id=<?= $category['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_category.php?id=<?= $category['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
