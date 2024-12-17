<?php
require_once '../db/config.php';

if (isset($_GET['ticket_number']) && is_numeric($_GET['ticket_number'])) {
    $ticketNumber = intval($_GET['ticket_number']);
    error_log("Received ticket number: $ticketNumber");

    try {
        $sql = "SELECT r.*, u.name AS user_name, b.bus_name, b.route, b.journey_date, b.departure_time
                FROM reservations r
                JOIN ashesis_users u ON r.user_id = u.id
                JOIN ashesis_bookings b ON r.booking_id = b.id
                WHERE r.ticket_number = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ticketNumber]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reservation) {
            error_log("No reservation found for Ticket Number: $ticketNumber");
            die("Invalid or missing ticket details.");
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        die("Error: " . $e->getMessage());
    }
} else {
    error_log("Invalid ticket number parameter.");
    die("Invalid ticket number.");
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Tickets</title>
    <link rel="icon" href="../assets/images/buslogo.png" type="png">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/ticketpage.css">
</head>
<body>
    <header class="page-header">
        <div class="header-content">
            <h1>Your Tickets</h1>
            <a href="booking.php">
                <button type="submit">Click here to go back to the Booking Page</button>
            </a>
        </div>
    </header>
    <div class="tickets-container">
        <div class="ticket-box">
            <div class="ticket-header">
                <h3><?= htmlspecialchars($reservation['bus_name']); ?> - Verified Ride</h3>
                <p class="status-completed">CONFIRMED</p>
            </div>
            <div class="ticket-details">
                <div class="ticket-row">
                    <div class="ticket-label">Passenger Name:</div>
                    <div class="ticket-value"><?= htmlspecialchars($reservation['user_name']); ?></div>
                </div>
                <div class="ticket-row">
                    <div class="ticket-label">Going From:</div>
                    <div class="ticket-value"><?= htmlspecialchars(explode(' to ', $reservation['route'])[0]); ?></div>
                </div>
                <div class="ticket-row">
                    <div class="ticket-label">Going To:</div>
                    <div class="ticket-value"><?= htmlspecialchars(explode(' to ', $reservation['route'])[1]); ?></div>
                </div>
                <div class="ticket-row">
                    <div class="ticket-label">Date of Journey:</div>
                    <div class="ticket-value"><?= htmlspecialchars($reservation['journey_date']); ?></div>
                </div>
                <div class="ticket-row">
                    <div class="ticket-label">Departure Time:</div>
                    <div class="ticket-value"><?= htmlspecialchars($reservation['departure_time']); ?></div>
                </div>
                <div class="ticket-row">
                    <div class="ticket-label">Seats Booked:</div>
                    <div class="ticket-value"><?= htmlspecialchars($reservation['seats_reserved']); ?></div>
                </div>
                <div class="ticket-row">
                    <div class="ticket-label">Total Paid:</div>
                    <div class="ticket-value">GH₵<?= number_format($reservation['total_amount'], 2); ?></div>
                </div>
                <div class="ticket-row">
                    <div class="ticket-label">Your Code:</div>
                    <div class="ticket-value"><?= htmlspecialchars($reservation['ticket_number']); ?></div>
                </div>
            </div>
            <div class="pickup-dropoff">
                <div class="pickup">
                    <strong>Pickup From:</strong>
                    <p>Ashesi Main Gate - <?= htmlspecialchars($reservation['departure_time']); ?></p>
                </div>
                <div class="dropoff">
                    <strong>Dropping At:</strong>
                    <p><?= htmlspecialchars(explode(' to ', $reservation['route'])[1]); ?> - Estimated Arrival</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
