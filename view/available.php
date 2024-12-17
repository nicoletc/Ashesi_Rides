<?php
// Include database configuration
require_once '../db/config.php';
require_once '../functions/auth_functions.php';
coreCheckLogin('admin'); 


// Fetch bookings from the database
$sql = "SELECT * FROM ashesis_bookings";
$stmt = $pdo->query($sql);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Available Bookings</title>
  <link rel="icon" href="../assets/images/buslogo.png" type="png">
  <link rel="stylesheet" href="../assets/css/available.css">
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <nav class="sidebar">
      <h2>Menu</h2>
      <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="users.php">Users</a></li>
        <li><a href="available.php" class="active">Available Bookings</a></li>
        <li><a href="adminreview.php">Reviews</a></li>
        <a href="../actions/logout.php">
        <button class="btn">Log out</button>
        </a>
      </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
      <h1>Available Bookings</h1>

<section> 
      <div class="user-form">
        <form action="../actions/add_booking.php" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="journey-date">Journey Date</label>
            <input type="date" id="journey-date" name="journey-date" required>
          </div>
          <div class="form-group">
            <label for="departure-time">Departure Time</label>
            <input type="time" id="departure-time" name="departure-time" required>
          </div>
          <div class="form-group">
            <label for="route">Route</label>
            <input type="text" id="route" name="route" placeholder="e.g., Ashesi to Accra Mall" required>
          </div>
          <div class="form-group">
            <label for="fare">Fare (GHS)</label>
            <input type="number" id="fare" name="fare" placeholder="e.g., 28" required>
          </div>
          <div class="form-group">
            <label for="bus-name">Enter Bus Name:</label>
            <input type="text" id="bus-name" name="bus-name" placeholder="e.g., Double Decker Bus" required>
            </div>
          <div class="form-group">
            <label for="image-url">Bus Image URL</label>
            <input type="file" id="image-url" name="image-url" accept="image/*" placeholder="Enter Image URL">
          </div>
          
          <button type="submit" class="btn" style= "background-color: rgb(226, 192, 227); color: black";>Add Booking</button>
        </form>
      </div>
</section>

<section>
    <h1>Available Bookings</h1>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Journey Date</th>
          <th>Departure Time</th>
          <th>Route</th>
          <th>Fare</th>
          <th>Bus Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bookings as $booking): ?>
        <tr>
          <td><?= htmlspecialchars($booking['id']); ?></td>
          <td><?= htmlspecialchars($booking['journey_date']); ?></td>
          <td><?= htmlspecialchars($booking['departure_time']); ?></td>
          <td><?= htmlspecialchars($booking['route']); ?></td>
          <td>GHS <?= htmlspecialchars($booking['fare']); ?></td>
          <td><?= htmlspecialchars($booking['bus_name']); ?></td>
          <td>
            <button 
              class="edit-btn" 
              data-id="<?= $booking['id']; ?>"
              data-journey-date="<?= htmlspecialchars($booking['journey_date']); ?>"
              data-departure-time="<?= htmlspecialchars($booking['departure_time']); ?>"
              data-route="<?= htmlspecialchars($booking['route']); ?>"
              data-fare="<?= htmlspecialchars($booking['fare']); ?>"
              data-bus-name="<?= htmlspecialchars($booking['bus_name']); ?>"
              style="background-color:rgb(232, 159, 234); color: black; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s;">
              Edit
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </section>

  <!-- Edit Modal -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Edit Booking</h2>
      <form action="../functions/update_booking.php" id="edit-form" method="post">
    <!-- Correct hidden input for Booking ID -->
    <input type="hidden" id="edit-id" name="id">
    <div class="form-group">
        <label for="edit-journey-date">Journey Date:</label>
        <input type="date" id="edit-journey-date" name="journey_date" required>
    </div>
    <div class="form-group">
        <label for="edit-departure-time">Departure Time:</label>
        <input type="time" id="edit-departure-time" name="departure_time" required>
    </div>
    <div class="form-group">
        <label for="edit-route">Route:</label>
        <input type="text" id="edit-route" name="route" required>
    </div>
    <div class="form-group">
        <label for="edit-fare">Fare (GHS):</label>
        <input type="number" id="edit-fare" name="fare" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="edit-bus-name">Bus Name:</label>
        <input type="text" id="edit-bus-name" name="bus_name" required>
    </div>
    <button type="submit" style="background-color:rgb(232, 159, 234); color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s;">
        Save Changes
    </button>
</form>

      

    </div>
  </div>

  <script>
    // Modal Elements
    const modal = document.getElementById('editModal');
    const closeBtn = document.querySelector('.close');
    // Select the correct form by ID
    const editForm = document.getElementById('edit-form');

    // Open Modal and Populate Data
    document.querySelectorAll('.edit-btn').forEach(button => {
      button.addEventListener('click', () => {
        modal.style.display = 'block';
        document.getElementById('edit-id').value = button.dataset.id;
        document.getElementById('edit-journey-date').value = button.dataset.journeyDate;
        document.getElementById('edit-departure-time').value = button.dataset.departureTime;
        document.getElementById('edit-route').value = button.dataset.route;
        document.getElementById('edit-fare').value = button.dataset.fare;
        document.getElementById('edit-bus-name').value = button.dataset.busName;
      });
    });

    // Close Modal
    closeBtn.addEventListener('click', () => {
      modal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
      if (event.target == modal) {
        modal.style.display = 'none';
      }
    });

    // Handle Form Submission
    editForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const formData = new FormData(editForm);

      const response = await fetch('../functions/update_booking.php', {
        method: 'POST',
        body: formData,
      });

      const result = await response.text();
      alert(result);
      modal.style.display = 'none';
      location.reload(); // Reload to reflect changes
    });

  </script>
</body>
</html>