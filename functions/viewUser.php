<?php
require_once '../db/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["user_id"])) {
    $userId = intval($_POST["user_id"]);

    $sql = "SELECT id, name, email FROM ashesis_users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);

    if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode(["success" => true, "user" => $user]);
    } else {
        echo json_encode(["success" => false, "message" => "User not found."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
