<?php
session_start();
include('../includes/db.php'); // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Initialize an empty array for errors
    $errors = [];

    // Validate username
    if (empty($username)) {
        $errors['username'] = "Username is required!";
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = "Password is required!";
    }

    // If no errors, proceed with login
    if (empty($errors)) {
        try {
            // Prepare SQL query using PDO
            $stmt = $pdo->prepare("SELECT username, password FROM users WHERE username = :username");
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();
            
            // Fetch the user data
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Verify password against the stored hash
                if (password_verify($password, $user['password'])) {
                    // Password is correct, set session variables
                    $_SESSION['username'] = $user['username'];

                    // Redirect to dashboard
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $errors['login'] = "Invalid username or password!";
                }
            } else {
                $errors['login'] = "Invalid username or password!";
            }
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    // Display errors if any
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
}
?>
