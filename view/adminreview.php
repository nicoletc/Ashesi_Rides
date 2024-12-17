<?php
require_once '../functions/auth_functions.php';
coreCheckLogin('admin'); 
require_once '../db/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Review Management</title>
  <link rel="icon" href="../assets/images/buslogo.png" type="png">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/reviews.css">
</head>
<body>
  <div class="review-management-container">
    <!-- Sidebar -->
    <nav class="sidebar">
      <h2>Menu</h2>
      <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="users.php">Users</a></li>
        <li><a href="available.php">Available Bookings</a></li>
        <li><a href="reviews.php" class="active">Manage Reviews</a></li>
        <a href="../actions/logout.php">
          <button class="btn">Log out</button>
        </a>
      </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
      <h1>Manage Reviews</h1>

      <!-- Reviews Table -->
      <table class="review-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>User Name</th>
            <th>Rating</th>
            <th>Content</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          try {
              // Query to fetch reviews with user names
              $sql = "SELECT r.id, u.name AS user_name, r.rating, r.content, r.created_at 
                      FROM reviews r 
                      JOIN ashesis_users u ON r.user_id = u.id
                      ORDER BY r.created_at DESC";
              $stmt = $pdo->prepare($sql);
              $stmt->execute();

              // Display reviews in table rows
              while ($review = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "<tr data-review-id='" . htmlspecialchars($review['id']) . "'>";
                  echo "<td>" . htmlspecialchars($review['id']) . "</td>";
                  echo "<td>" . htmlspecialchars($review['user_name']) . "</td>";
                  echo "<td>" . htmlspecialchars($review['rating']) . "</td>";
                  echo "<td>" . htmlspecialchars($review['content']) . "</td>";
                  echo "<td>" . htmlspecialchars($review['created_at']) . "</td>";
                  echo "<td>
                          <button class='btn delete' onclick='deleteReview(" . htmlspecialchars($review['id']) . ")'>Delete</button>
                        </td>";
                  echo "</tr>";
              }
          } catch (PDOException $e) {
              echo "<tr><td colspan='6'>Error fetching reviews: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </main>
  </div>

  
</body>
</html>
