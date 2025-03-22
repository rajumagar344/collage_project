<?php
session_start(); // Start session
include('./includes/db.php'); // Include database connection

?>


<!DOCTYPE html>
<html>
<head>
    <title>Login & Registration Form</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800&display=swap" rel="stylesheet">
   
</head>
<body>
<div class="cont">
    <div class="form sign-in">
        <h2>Sign In</h2>
        <form action="./pages/login.php" method="POST" id="loginForm" onsubmit="return validateLogin()">
            <label>
                <span>Username</span>
                <input type="text" name="username" id="loginUsername" value="">
                <div id="loginUsernameError" class="error-message"></div> <!-- Error message here -->
            </label>
            <label>
                <span>Password</span>
                <input type="password" name="password" id="loginPassword" value="">
                <div id="loginPasswordError" class="error-message"></div> <!-- Error message here -->
            </label>
            <button class="submit" type="submit">Sign In</button>
            <a href="forgot_password.php">
            <p class="forgot-pass">Forgot Password ?</p>
            </a>
        </form>
    </div>

    <div class="sub-cont">
        <div class="img">
            <div class="img__text m--up">
                <h1>New here?</h1>
                <p>sign up and Organize</p>
            </div>
            <div class="img__text m--in">
                <h1>One of us?</h1>
                <p>just sign in</p>
            </div>
            <div class="img__btn">
                <span class="m--up">Sign Up</span>
                <span class="m--in">Sign In</span>
            </div>
        </div>
        <div class="form sign-up">
            <h2>Sign Up</h2>
            <form action="./pages/register.php" method="POST" id="registerForm" onsubmit="return validateRegister()">
                <label>
                    <span>Username</span> 
                    <input type="text" name="username" id="regUsername" value="">
                    <div id="regUsernameError" class="error-message"></div> <!-- Error message here -->
                </label>
                <label>
                    <span>Mobile Number</span>
                    <input type="number" name="mobile" id="regMobile" value="">
                    <div id="regMobileError" class="error-message"></div> <!-- Error message here -->
                </label>
                <label>
                    <span>Password</span>
                    <input type="password" name="password" id="regPassword" value="">
                    <div id="regPasswordError" class="error-message"></div> <!-- Error message here -->
                </label>
                <label>
                    <span>Confirm Password</span>
                    <input type="password" name="confirm_password" id="regConfirmPassword" value="">
                    <div id="regConfirmPasswordError" class="error-message"></div> <!-- Error message here -->
                </label>
                <button type="submit" class="submit">Sign Up Now</button>
            </form>
        </div>
    </div>
</div>
<script src="./assets/js/script.js"></script>

    <script type="text/javascript">
        // For sliding effect
        document.querySelector('.img__btn').addEventListener('click', function() {
            document.querySelector('.cont').classList.toggle('s--signup');
        });


    // Client-side validation for Login
    function validateLogin() {
        var username = document.getElementById('loginUsername').value;
        var password = document.getElementById('loginPassword').value;
        
        var usernameError = document.getElementById('loginUsernameError');
        var passwordError = document.getElementById('loginPasswordError');

        // Clear previous errors
        removeError('loginUsername');
        removeError('loginPassword');

        var valid = true;

        if (username == "") {
            showError('loginUsername', "Username is required.");
            valid = false;
        }

        if (password == "") {
            showError('loginPassword', "Password is required.");
            valid = false;
        }

        return valid;
    }

    // Client-side validation for Registration
    function validateRegister() {
        var username = document.getElementById('regUsername').value;
        var mobile = document.getElementById('regMobile').value;
        var password = document.getElementById('regPassword').value;
        var confirmPassword = document.getElementById('regConfirmPassword').value;

        var valid = true;

        // Clear previous errors
        removeError('regUsername');
        removeError('regMobile');
        removeError('regPassword');
        removeError('regConfirmPassword');

        // Validate Username
        if (username == "") {
            showError('regUsername', "Username is required.");
            valid = false;
        } else if (username.length < 4) {
            showError('regUsername', "Username must be at least 4 characters long.");
            valid = false;
        }

        // Validate Mobile Number
        if (mobile == "") {
            showError('regMobile', "Mobile number is required.");
            valid = false;
        } else if (!/^\d{10}$/.test(mobile)) {
            showError('regMobile', "Please enter a valid 10-digit mobile number.");
            valid = false;
        }

       // Validate Password
if (password == "") {
    showError('regPassword', "Password is required.");
    valid = false;
} else if (password.length < 6) {
    showError('regPassword', "Password must be at least 6 characters.");
    valid = false;
}  else {
    // If all validation passes
    showSuccess('regPassword'); // Assuming you have a function to show success
}


        // Validate Confirm Password
        if (confirmPassword == "") {
            showError('regConfirmPassword', "Confirm Password is required.");
            valid = false;
        } else if (password !== confirmPassword) {
            showError('regConfirmPassword', "Passwords do not match.");
            valid = false;
        }

        return valid;
    }

    // Function to show error messages
    function showError(fieldId, message) {
        var field = document.getElementById(fieldId);
        var errorContainer = document.createElement('div');
        errorContainer.className = 'error-message';
        errorContainer.textContent = message;

        // Add error message container after the input field
        field.classList.add('error');
        field.parentNode.appendChild(errorContainer);
    }

    // Function to remove error messages
    function removeError(fieldId) {
        var field = document.getElementById(fieldId);
        field.classList.remove('error');

        var errorMessages = field.parentNode.getElementsByClassName('error-message');
        for (var i = 0; i < errorMessages.length; i++) {
            errorMessages[i].remove();
        }
    }
</script>
</body>
</html>
