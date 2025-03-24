<?php
include('../includes/db.php'); // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = trim($_POST['username']);
    $mobile = trim($_POST['mobile']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Initialize an empty array for errors
    $errors = [];

    // Validate username
    if (empty($username)) {
        $errors['username'] = "Username is required!";
    } elseif (strlen($username) < 4) {
        $errors['username'] = "Username must be at least 4 characters long!";
    }

    // Validate mobile number
    if (empty($mobile)) {
        $errors['mobile'] = "Mobile number is required!";
    } elseif (!preg_match('/^\d{10}$/', $mobile)) { // Check if mobile is 10 digits
        $errors['mobile'] = "Please enter a valid 10-digit mobile number!";
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = "Password is required!";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters!";
    }

    // Validate confirm password
    if (empty($confirmPassword)) {
        $errors['confirm_password'] = "Confirm password is required!";
    } elseif ($password !== $confirmPassword) {
        $errors['confirm_password'] = "Passwords do not match!";
    }

    // If no errors, proceed with registration (insert into the database)
    if (empty($errors)) {
        try {
            // Hash the password before storing it
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL query to check if the username already exists
            $stmt = $pdo->prepare("SELECT username FROM users WHERE username = :username");
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $errors['username'] = "Username already taken. Please choose a different one.";
            } else {
                // Insert the new user into the database
                $stmt = $pdo->prepare("INSERT INTO users (username, mobile, password) VALUES (:username, :mobile, :password)");
                $stmt->bindParam(":username", $username, PDO::PARAM_STR);
                $stmt->bindParam(":mobile", $mobile, PDO::PARAM_STR);
                $stmt->bindParam(":password", $passwordHash, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    // Registration successful
                    echo "<p style='color: green;'>Registration successful!</p>";
                    header("Location: index.php"); // Redirect to login page
                    exit();
                } else {
                    $errors['database'] = "There was an error with the registration. Please try again.";
                }
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
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
