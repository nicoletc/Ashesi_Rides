<?php
require_once 'auth_functions.php';
require_once '../db/config.php';
coreCheckLogin('admin'); 

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $name = trim($_POST["user_name"]);
    $email = trim($_POST["user_email"]);
    $password = trim($_POST["user_password"]);

    // Validate input
    if (empty($name) || empty($email) || empty($password)) {
        echo "<p style='color:red;'>All fields are required.</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color:red;'>Invalid email format.</p>";
    } else {
        try {
            // Check if the email is already in use
            $checkSql = "SELECT id FROM ashesis_users WHERE email = ?";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([$email]);
            
            if ($checkStmt->rowCount() > 0) {
                echo "<p style='color:red;'>This email is already registered.</p>";
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert the user into the database
                $sql = "INSERT INTO ashesis_users (name, email, password) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                if ($stmt->execute([$name, $email, $hashedPassword])) {
                    echo "<p style='color:green;'>User added successfully.</p>";
                    header("Location: ../view/users.php");
                } else {
                    echo "<p style='color:red;'>Failed to add user. Please try again.</p>";
                }
            }
        } catch (PDOException $e) {
            echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
        }
    }
}
?>
