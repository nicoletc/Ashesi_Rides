<?php
require_once '../db/config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['name'])) {
    die('Please log in to access this page.');
}

$userName = htmlspecialchars($_SESSION['name']);

// Check if the bus ID is in the query string
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $busId = intval($_GET['id']);

    try {
        // Fetch the bus details
        $sql = "SELECT bus_name, route, journey_date, departure_time, fare FROM ashesis_bookings WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$busId]);

        $busDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($busDetails) {
            $busName = htmlspecialchars($busDetails['bus_name']);
            $route = htmlspecialchars($busDetails['route']);
            $journeyDate = htmlspecialchars($busDetails['journey_date']);
            $departureTime = htmlspecialchars($busDetails['departure_time']);
            $fare = htmlspecialchars($busDetails['fare']);
        } else {
            die('Bus not found.');
        }
    } catch (PDOException $e) {
        die('Error fetching bus details: ' . $e->getMessage());
    }
} else {
    die('Invalid bus ID.');
}

try {
    // Fetch all reserved seats for this bus
    $sql = "SELECT seats_reserved FROM reservations WHERE booking_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$busId]);
    $reservedSeats = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Normalize seat names and split into an array
        $seats = preg_split('/,\s*/', $row['seats_reserved']); // Handles both ', ' and ','
        $reservedSeats = array_merge($reservedSeats, $seats);
    }

    // Remove duplicate seat names (if any) and send them to the frontend
    $reservedSeats = array_unique($reservedSeats);
    $reservedSeats = array_values($reservedSeats); // Reset array keys to sequential indices


    // Pass reserved seats as JSON to the frontend
    echo "<script>const reservedSeats = " . json_encode($reservedSeats) . ";</script>";
} catch (PDOException $e) {
    die("Error fetching reserved seats: " . $e->getMessage());
}
echo "<script>console.log('Reserved Seats:', " . json_encode($reservedSeats) . ");</script>";


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Seat Select</title>
    <link rel="icon" href="../assets/images/buslogo.png" type="png">
    <link rel="stylesheet" href="../assets/css/busseatstyle.css">
    <style>
        .seat.selected {
            background-color: #4caf50; 
            color: white;
        }
    </style>
</head>
<body>
    <!-- Card Header -->
    <div class="card-header">
        <h2>Bus Details</h2>
        <p><strong>Bus Name:</strong> <?= $busName; ?></p>
        <p><strong>Route:</strong> <?= $route; ?></p>
        <p><strong>Journey Date:</strong> <?= $journeyDate; ?></p>
        <p><strong>Departure Time:</strong> <?= $departureTime; ?></p>
        <p><strong>Fare:</strong> GH₵<?= $fare; ?></p>
    </div>

    <!-- Page Header -->
    <div class="header">
        <h1>Available Bus Seats</h1>
        <p>Select your seat from the available options below:</p>
    </div>

    <!-- Seat Container -->
    <div class="seat-container">
        <?php
        $rows = range('A', 'J'); // Rows A to J
        $columns = range(1, 4); // Columns 1 to 4

        foreach ($rows as $row) {
            foreach ($columns as $column) {
                $seat = $row . $column;
                echo '<div class="seat">' . $seat . '</div>';
                if ($column == 2) echo '<div style="grid-column: span 1;"></div>';
            }
        }
        ?>
    </div>

    <!-- Ticket -->
    <div class="more-info">
        <div class="info-text">
            <p><strong>Passenger:</strong> <?= $userName; ?></p>
            <p><strong>Ticket Number:</strong> <span id="ticketNumber"></span></p>
            <p><strong>Phone Number:</strong> <input type="text" id="phoneNumber" placeholder="Enter your phone number"></p>
            <p><strong>Seats Booked:</strong> <span id="seatsBooked">0</span></p>
            <p><strong>Amount Paid:</strong> GH₵<span id="amountPaid">0</span></p>
        </div>
    </div>

    <!-- Total Section -->
    <div class="total-section" id="totalSection">
        TOTAL GH₵<span id="totalAmount">0</span><br>
        SEATS SELECTED: <span id="selectedSeatsCount">0</span><br>
        <span>NEXT</span>
    </div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const seats = document.querySelectorAll('.seat');
    const totalSection = document.querySelector('.total-section');
    const seatsBooked = document.getElementById('seatsBooked');
    const amountPaid = document.getElementById('amountPaid');
    const ticketNumber = document.getElementById('ticketNumber');
    const phoneNumberInput = document.getElementById('phoneNumber'); 
    const totalAmountDisplay = document.getElementById('totalAmount');
    const selectedSeatsCountDisplay = document.getElementById('selectedSeatsCount');

    let selectedSeats = []; // Track selected seats
    const seatPrice = <?= $fare; ?>; // Dynamic seat price

    // Generate a unique ticket number
    const generateTicketNumber = () => Math.floor(100000 + Math.random() * 900000);
    ticketNumber.textContent = generateTicketNumber();

    // Handle seat selection
    seats.forEach((seat) => {
        seat.addEventListener('click', () => {
            if (!seat.classList.contains('seat-booked')) {
                seat.classList.toggle('selected');
                const seatName = seat.textContent;

                if (seat.classList.contains('selected')) {
                    selectedSeats.push(seatName);
                } else {
                    selectedSeats = selectedSeats.filter(s => s !== seatName);
                }

                const totalAmount = selectedSeats.length * seatPrice;

                // Update the UI
                seatsBooked.textContent = selectedSeats.length;
                amountPaid.textContent = totalAmount.toFixed(2);
                totalAmountDisplay.textContent = totalAmount.toFixed(2);
                selectedSeatsCountDisplay.textContent = selectedSeats.length;
            }
        });
    });

    // Handle "NEXT" button click
    totalSection.addEventListener('click', () => {
    const phoneNumber = phoneNumberInput.value.trim();

    if (!phoneNumber) {
        alert("Please enter your phone number before proceeding.");
        return;
    }

    if (selectedSeats.length === 0) {
        alert("Please select at least one seat.");
        return;
    }

    // Redirect to payment page with reservation details as query parameters
    const queryParams = new URLSearchParams({
        bus_id: <?= $busId; ?>,
        seats: selectedSeats,
        total_amount: selectedSeats.length * seatPrice,
        phone_number: phoneNumber,
        ticket_number: ticketNumber.textContent,
    });

    window.location.href = `payment.php?${queryParams.toString()}`;
});

});
document.addEventListener('DOMContentLoaded', () => {
    const seats = document.querySelectorAll('.seat'); // All seat elements

    // Gray out reserved seats
    seats.forEach(seat => {
        const seatName = seat.textContent.trim();
        if (reservedSeats.includes(seatName)) {
            seat.classList.add('seat-booked'); // Add class to gray out
        }
    });

    // Prevent interaction with reserved seats
    seats.forEach(seat => {
        seat.addEventListener('click', () => {
            if (seat.classList.contains('seat-booked')) {
                alert('Sorry, this seat has already been reserved.');
            }
        });
    });
});


</script>

</body>
</html>
