<?php
session_start();
require_once '../db/config.php'; // Database configuration file

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    die("You must be logged in to submit a review.");
}

$userId = $_SESSION['id']; // User ID from session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize inputs
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;
    $comment = isset($_POST['comment']) ? trim(htmlspecialchars($_POST['comment'])) : null;

    // Validate inputs
    if ($rating < 1 || $rating > 5 || empty($comment)) {
        die("Invalid rating or comment. Please provide a rating between 1 and 5 and a comment.");
    }

    try {
        // Prepare SQL statement to insert review into database
        $sql = "INSERT INTO reviews (user_id, rating, content) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        // Execute the query
        $stmt->execute([$userId, $rating, $comment]);

        // Redirect with success message
        echo "Review Submitted Successfully!"
        header("Location: ../view/review.php"); // Create a success page if needed
        exit;
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage()); // Log error for debugging
        die("Error submitting review. Please try again later.");
    }
} else {
    // If the request method is not POST
    die("Invalid request method.");
}
?>
