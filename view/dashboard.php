<?php
require_once '../db/config.php';
require_once '../functions/auth_functions.php';
coreCheckLogin('admin');

// Fetch total users and total bookings
try {
    // Query to count total users
    $stmtUsers = $pdo->query("SELECT COUNT(*) AS total_users FROM ashesis_users");
    $totalUsers = $stmtUsers->fetch(PDO::FETCH_ASSOC)['total_users'];

    // Query to count total bookings
    $stmtBookings = $pdo->query("SELECT COUNT(*) AS total_bookings FROM reservations");
    $totalBookings = $stmtBookings->fetch(PDO::FETCH_ASSOC)['total_bookings'];

    // Query to fetch bookings per month
    $stmtMonthly = $pdo->query("
        SELECT MONTH(reserved_at) AS month, COUNT(*) AS total
        FROM reservations
        GROUP BY MONTH(reserved_at)
        ORDER BY MONTH(reserved_at)
    ");
    $bookingsPerMonth = $stmtMonthly->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching analytics data: " . $e->getMessage());
}

// Convert PHP data into JSON for Chart.js
$monthlyLabels = [];
$monthlyData = [];

foreach ($bookingsPerMonth as $row) {
    $monthlyLabels[] = date('F', mktime(0, 0, 0, $row['month'], 10)); // Convert month number to name
    $monthlyData[] = (int)$row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="icon" href="../assets/images/buslogo.png" type="png">
  <link rel="stylesheet" href="../assets/css/dashboard.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <nav class="sidebar">
      <h2>Menu</h2>
      <ul>
        <li><a href="dashboard.php" class="active">Dashboard</a></li>
        <li><a href="users.php">Users</a></li>
        <li><a href="available.php">Available Bookings</a></li>
        <li><a href="review.php">Reviews</a></li>
        <a href="../actions/logout.php">
        <button class="btn">Log out</button>
        </a>
      </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
      <h1>Analytics</h1>
      <div class="stats">
        <div class="stat-box">
          <h3>Total Users</h3>
          <p><?= htmlspecialchars($totalUsers); ?></p>
        </div>
        <div class="stat-box">
          <h3>Total Bookings</h3>
          <p><?= htmlspecialchars($totalBookings); ?></p>
        </div>
      </div>
      <h2>Bookings Per Month</h2>
      <canvas id="bookingsChart" width="400" height="200"></canvas>
    </main>
  </div>

  <script>
    // Pass PHP data to JavaScript
    const monthlyLabels = <?= json_encode($monthlyLabels); ?>;
    const monthlyData = <?= json_encode($monthlyData); ?>;
  </script>
  <script src="../assets/javascript/dashboard.js"></script>
</body>
</html>
