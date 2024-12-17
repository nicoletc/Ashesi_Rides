<?php
require_once '../db/config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    die("User not logged in.");
}

$userId = $_SESSION['id']; // User ID from the session
error_log("Logged-in User ID: $userId"); // Debug session ID
error_log("Session user_id: " . $_SESSION['id']);


try {
    // Fetch all tickets for the logged-in user
    $sql = "SELECT r.*, u.name AS user_name, b.bus_name, b.route, b.journey_date, b.departure_time
            FROM reservations r
            JOIN ashesis_users u ON r.user_id = u.id
            JOIN ashesis_bookings b ON r.booking_id = b.id
            WHERE r.user_id = ?
            ORDER BY r.reserved_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId]);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);



    if (empty($reservations)) {
        echo "<p>No tickets found for your account.</p>";
        exit;
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Tickets</title>
    <link rel="stylesheet" href="../assets/css/displayticket.css">
</head>
<body>
    <header>
        <h1>Your Tickets</h1>
        <a href="booking.php"><button>Back to Booking Page</button></a>
    </header>
    <div class="ticket-container">
    <?php foreach ($reservations as $reservation): ?>
        <div class="ticket-card">
            <h2><?= htmlspecialchars($reservation['bus_name']); ?></h2>
            <p><strong>Passenger:</strong> <?= htmlspecialchars($reservation['user_name']); ?></p>
            <p><strong>Ticket Number:</strong> <?= htmlspecialchars($reservation['ticket_number']); ?></p>
            <p><strong>Seats Reserved:</strong> <?= htmlspecialchars($reservation['seats_reserved']); ?></p>
            <p><strong>Amount Paid:</strong> GH₵<?= number_format($reservation['total_amount'], 2); ?></p>
            <p><strong>Journey Date:</strong> <?= htmlspecialchars($reservation['journey_date']); ?></p>
            <p><strong>Departure Time:</strong> <?= htmlspecialchars($reservation['departure_time']); ?></p>
            <p><strong>Route:</strong> <?= htmlspecialchars($reservation['route']); ?></p>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
