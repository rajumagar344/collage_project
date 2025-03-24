<?php
session_start();
include ('../includes/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        die("User not found!");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['username'];
    $new_phone = $_POST['phone'];

    $stmt = $pdo->prepare("UPDATE users SET username = ?, phone = ? WHERE id = ?");
    $stmt->execute([$new_username, $new_phone, $id]);

    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
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
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            width: 50%;
            margin: auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
        }
        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: 0.3s;
            display: inline-block;
        }
        .btn-save {
            background-color: #007bff;
            color: white;
        }
        .btn-save:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<!-- Include Sidebar -->
<?php include('sidebar.php'); ?>

<!-- Main Content -->
<div class="main-content">
    <h2>Edit User</h2>
    
    <div class="form-container">
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="mobile" value="<?= htmlspecialchars($user['mobile']) ?>" required>
            </div>
            <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Save Changes</button>
        </form>
    </div>
</div>

</body>
</html>
