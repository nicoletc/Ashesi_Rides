<?php
require_once '../db/config.php';
header('Content-Type: application/json'); // Force JSON response

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null; 
    $journeyDate = $_POST['journey_date'] ?? null;
    $departureTime = $_POST['departure_time'] ?? null;
    $route = $_POST['route'] ?? null;
    $fare = $_POST['fare'] ?? null;
    $busName = $_POST['bus_name'] ?? null;

    // Validate fields
    if (empty($id) || empty($journeyDate) || empty($departureTime) || empty($route) || empty($fare) || empty($busName)) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit;
    }

    try {
        $sql = "UPDATE ashesis_bookings SET journey_date = ?, departure_time = ?, route = ?, fare = ?, bus_name = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$journeyDate, $departureTime, $route, $fare, $busName, $id]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Booking updated successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating booking.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
