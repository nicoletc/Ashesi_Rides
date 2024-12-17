<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json'); // Ensure JSON response

require_once '../db/config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}
$userId = $_SESSION['id']; // Corrected session variable name

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // STEP 1: Fetch and validate the POST data
        $busId = isset($_POST['bus_id']) ? intval($_POST['bus_id']) : null;
        $phoneNumber = isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number']) : null;
        $seats = isset($_POST['seats']) ? json_decode($_POST['seats'], true) : null; // DECODE JSON seats
        $totalAmount = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0;
        $ticketNumber = isset($_POST['ticket_number']) ? intval($_POST['ticket_number']) : null; // Fetch pre-generated ticket number

        // Debugging log to check raw POST data (useful during testing)
        error_log("Raw POST data: " . print_r($_POST, true));

        // STEP 2: Validation for required fields
        if (!$busId || !$phoneNumber || empty($seats) || !is_array($seats) || !$totalAmount || !$userId) {
            error_log("Validation failed: Bus ID: $busId, Phone: $phoneNumber, Seats: " . print_r($seats, true) . ", Total: $totalAmount, User: $userId");
            echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
            exit;
        }

        // STEP 3: Log successful decoding of seats (useful for debugging)
        error_log("Decoded seats: " . print_r($seats, true));

        // Convert seats array to comma-separated string
        $seatsReserved = implode(', ', $seats);

        // STEP 4: Insert reservation into the database
        $sql = "INSERT INTO reservations (user_id, booking_id, seats_reserved, total_amount, phone_number, ticket_number)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $busId, $seatsReserved, $totalAmount, $phoneNumber, $ticketNumber]);

        // STEP 5: Send success response
        echo json_encode([
            'success' => true,
            'message' => 'Reservation successful',
            'ticket_number' => $ticketNumber
        ]);
        exit;

    } catch (PDOException $e) {
        // STEP 6: Handle database errors
        error_log('Database error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit;
    }
} else {
    // STEP 7: Handle invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}
