<?php
require_once '../db/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["user_id"])) {
    $userId = intval($_POST["user_id"]);
    $name = trim($_POST["user_name"]);
    $email = trim($_POST["user_email"]);

    // Validate input
    if (empty($name) || empty($email)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format."]);
        exit;
    }

    try {
        // Update user information in the database
        $sql = "UPDATE ashesis_users SET name = ?, email = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$name, $email, $userId])) {
            echo json_encode(["success" => true, "message" => "User updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update user."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
