<?php
require_once '../db/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["user_id"])) {
    $userId = intval($_POST["user_id"]);

    try {
        // Delete user from the database
        $sql = "DELETE FROM ashesis_users WHERE id = ?";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$userId])) {
            echo json_encode(["success" => true, "message" => "User deleted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete user."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
