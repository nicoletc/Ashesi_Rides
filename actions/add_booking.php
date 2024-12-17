<?php
// Include database configuration
require_once '../db/config.php';

// Start the session
session_start();

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $journeyDate = $_POST['journey-date'];
    $departureTime = $_POST['departure-time'];
    $route = $_POST['route'];
    $fare = $_POST['fare'];
    $busName = $_POST['bus-name'];
    $imagePath = null;

    // Handle file upload
    if (isset($_FILES['image-url']) && $_FILES['image-url']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/';
        $fileName = uniqid('bus_', true) . '.' . pathinfo($_FILES['image-url']['name'], PATHINFO_EXTENSION);
        $imagePath = $uploadDir . $fileName;

        // Move the uploaded file
        if (!move_uploaded_file($_FILES['image-url']['tmp_name'], $imagePath)) {
            die("Error uploading the image.");
        }
    } else {
        die("Please upload a valid image.");
    }

    // Insert data into the database
    $sql = "INSERT INTO ashesis_bookings (journey_date, departure_time, route, fare, image_url, created_by, bus_name) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $createdBy = $_SESSION['id']; // Assuming the logged-in user's ID is stored in the session

    if ($stmt->execute([$journeyDate, $departureTime, $route, $fare, $imagePath, $createdBy, $busName])) {
        echo "Booking added successfully!";
        // Redirect or display a success message
        header("Location: ../view/available.php");
        exit();
    } else {
        echo "Error adding booking. Please try again.";
    }
} else {
    echo "Invalid request.";
}
?>
