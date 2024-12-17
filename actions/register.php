<?php
// Include database configuration file
require_once '../db/config.php';

// Initialize variables
$firstName = $lastName = $email = $password = $role = "";
$errors = [];

// Process form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate first name
    if (empty(trim($_POST["first-name"]))) {
        $errors[] = "First name is required.";
    } else {
        $firstName = trim($_POST["first-name"]);
    }

    // Validate last name
    if (empty(trim($_POST["last-name"]))) {
        $errors[] = "Last name is required.";
    } else {
        $lastName = trim($_POST["last-name"]);
    }

    // Validate email
    if (empty(trim($_POST["signup-email"]))) {
        $errors[] = "Email address is required.";
    } elseif (!filter_var(trim($_POST["signup-email"]), FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        $email = trim($_POST["signup-email"]);

        // Check if email is already registered
        $sql = "SELECT id FROM ashesis_users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $errors[] = "This email is already registered.";
        }
    }

    // Validate password
    if (empty(trim($_POST["signup-password"]))) {
        $errors[] = "Password is required.";
    } else {
        $password = trim($_POST["signup-password"]);
        // Perform additional password checks as per your requirements
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters.";
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        } elseif (!preg_match('/\d{3,}/', $password)) {
            $errors[] = "Password must contain at least three digits.";
        } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            $errors[] = "Password must contain at least one special character.";
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        }
    }

    // If no errors, insert user into the database
    if (empty($errors)) {
        $sql = "INSERT INTO ashesis_users (name, email, password, role) VALUES (?, ?, ?, 'student')";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$firstName . ' ' . $lastName, $email, $password])) {
            // Redirect to login page after successful registration
            header("Location: ../view/Login.php");
            exit();
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}
?>