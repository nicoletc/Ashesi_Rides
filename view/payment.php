<?php
require_once '../db/config.php';

// Retrieve reservation details from query parameters
if (
    isset($_GET['bus_id'], $_GET['seats'], $_GET['total_amount'], $_GET['phone_number'], $_GET['ticket_number'])
) {
    $busId = intval($_GET['bus_id']);
    $seatsReserved = htmlspecialchars($_GET['seats']);
    $totalAmount = floatval($_GET['total_amount']);
    $phoneNumber = htmlspecialchars($_GET['phone_number']);
    $ticketNumber = intval($_GET['ticket_number']);

    // Fetch bus and user details (user already logged in)
    session_start();
    if (!isset($_SESSION['id'], $_SESSION['name'])) {
        die("Please log in to proceed.");
    }

    $userId = $_SESSION['id'];
    $userName = htmlspecialchars($_SESSION['name']);

    

    try {
        $sql = "SELECT bus_name, route, journey_date, departure_time 
                FROM ashesis_bookings 
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$busId]);
        $busDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$busDetails) {
            die("Invalid bus details.");
        }
    } catch (PDOException $e) {
        die("Error fetching bus details: " . $e->getMessage());
    }
} else {
    die("Invalid reservation details.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Page</title>
  <link rel="icon" href="../assets/images/buslogo.png" type="png">
  <link rel="stylesheet" href="../assets/css/paymentstyles.css">
</head>
<body>
  <header>
    <button onclick="window.history.back()">← Back</button>
    <h1>Payment</h1>
  </header>

  <section class="payment-details">
    <div>
      <h2>Going From: <?= htmlspecialchars(explode(' to ', $busDetails['route'])[0]); ?></h2>
      <h2>Going To: <?= htmlspecialchars(explode(' to ', $busDetails['route'])[1]); ?></h2>
    </div>
    <div>
      <p>Pickup From: <?= htmlspecialchars(explode(' to ', $busDetails['route'])[0]); ?> - <?= htmlspecialchars($busDetails['departure_time']); ?></p>
      <p>Dropping At: <?= htmlspecialchars(explode(' to ', $busDetails['route'])[1]); ?></p>
    </div>

    <div>
      <h3>Traveller's Info</h3>
      <p>Passenger: <?= $userName; ?></p>
      <p>Ticket Number: <?= htmlspecialchars($ticketNumber); ?></p>
      <p>Seat No: <?= htmlspecialchars($seatsReserved); ?></p>
      <p>Amount Paid: GH₵<?= number_format($totalAmount, 2); ?></p>
    </div>

    <h3>Payment Method</h3>
    <label>
      <input type="radio" name="payment" value="mastercard" checked> Mastercard
    </label>
    <label>
      <input type="radio" name="payment" value="visa"> Visa
    </label>
    <label>
      <input type="radio" id="mobileMoney" name="payment" value="momo"> Mobile Money
    </label>

    <section class="total-payment">
      <p><strong>Total Payment:</strong> GH₵<?= number_format($totalAmount, 2); ?></p>
    </section>

    <section id="momoNumber"></section>

    <button type="submit" id="payNowButton">Pay Now</button>
  </section>

  <div id="popupOverlay" class="popup-overlay" style="display:none;">
    <div class="popup-content">
        <img src="../assets/images/successicon.jpg" alt="Success Icon">
        <h3>Payment Successful!</h3>
        <p>Your payment has been processed successfully.</p>
        <button id="closePopup">Check Your Ticket</button>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const mobileMoneyOption = document.getElementById('mobileMoney');
      const displayNumberSection = document.getElementById('momoNumber');
      const paymentOptions = document.getElementsByName('payment');
      const popupOverlay = document.getElementById('popupOverlay');
      const closePopupButton = document.getElementById('closePopup');
      const payNowButton = document.getElementById('payNowButton');

      const reservationDetails = {
      bus_id: <?= json_encode($busId); ?>,
      phone_number: <?= json_encode($phoneNumber); ?>,
      seats: <?= json_encode(explode(', ', $seatsReserved)); ?>,
      total_amount: <?= json_encode($totalAmount); ?>,
      ticket_number: <?= json_encode($ticketNumber); ?>
    };


      // Handle payment method selection
      paymentOptions.forEach(option => {
        option.addEventListener('change', function () {
          if (mobileMoneyOption.checked) {
            displayNumberSection.style.display = 'block';
            displayNumberSection.textContent = 'Please pay to: 054-123-4567';
          } else {
            displayNumberSection.style.display = 'none';
          }
        });
      });

      // Handle "Pay Now" button click
      payNowButton.addEventListener('click', function (e) {
        e.preventDefault();

        // Send reservation to the database
        const formData = new FormData();
        formData.append('bus_id', reservationDetails.bus_id);
        formData.append('phone_number', reservationDetails.phone_number);
        formData.append('seats', JSON.stringify(reservationDetails.seats)); // Properly format seats data as JSON
        formData.append('total_amount', reservationDetails.total_amount);
        formData.append('ticket_number', reservationDetails.ticket_number);



        fetch('../functions/reserveSeats.php', {
          method: 'POST',
          body: formData,
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            popupOverlay.style.display = 'block';
          } else {
            alert(data.message || "An error occurred.");
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert("An unexpected error occurred. Please try again.");
        });
      });

      // Close popup
      closePopupButton.addEventListener('click', function () {
        popupOverlay.style.display = 'none';
        window.location.href = `ticketpage.php?ticket_number=<?= htmlspecialchars($ticketNumber); ?>`;
      });
    });
  </script>
</body>
</html>
