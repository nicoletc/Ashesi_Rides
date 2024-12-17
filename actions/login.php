<?php
// Include the database configuration file
require_once '../db/config.php';

// Start the session
session_start();

// Initialize variables
$email = $password = "";
$errors = [];

// Process form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST["login-email"]))) {
        $errors[] = "Please enter your email.";
    } elseif (!filter_var(trim($_POST["login-email"]), FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        $email = trim($_POST["login-email"]);
    }

    // Validate password
    if (empty(trim($_POST["login-password"]))) {
        $errors[] = "Please enter your password.";
    } else {
        $password = trim($_POST["login-password"]);
    }

    // If no errors, check credentials in the database
    if (empty($errors)) {
        $sql = "SELECT id, name, password, role FROM ashesis_users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        // Check if the user exists
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password
            if (password_verify($password, $user["password"])) {
                // Store data in session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $user["id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["role"] = $user["role"];

                // Redirect based on role
                if ($user["role"] == "student") {
                    header("Location: ../view/booking.php");
                } else {
                    header("Location: ../view/dashboard.php");
                }
                exit();
            } else {
                $errors[] = "Invalid password.";
                echo "invalid password";
            }
        } else {
            $errors[] = "No account found with that email.";
            echo "invalid password";

        }
    }
}
?>