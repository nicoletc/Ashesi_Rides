<?php
require_once '../functions/auth_functions.php';
coreCheckLogin('admin'); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Management</title>
  <link rel="icon" href="../assets/images/buslogo.png" type="png">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/users.css">
</head>
<body>
  <div class="user-management-container">
    <!-- Sidebar -->
    <nav class="sidebar">
      <h2>Menu</h2>
      <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="users.php" class="active">Users</a></li>
        <li><a href="available.php">Available Bookings</a></li>
        <li><a href="review.php">Reviews</a></li>
        <a href="../actions/logout.php">
        <button class="btn">Log out</button>
        </a>
      </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
      <h1>Manage Users</h1>

      <!-- Add User Form -->
      <div class="user-form">
      <form id="add-user-form" method="POST" action="../functions/add_users.php">
          <div class="form-group">
            <label for="user-id">User ID</label>
            <input type="text" id="user-id" name="user-id" placeholder="User ID">
          </div>
          <div class="form-group">
            <label for="user-name">User Name</label>
            <input type="text" id="user-name" name="user_name" placeholder="User Name">
          </div>
          <div class="form-group">
            <label for="user-email">User Email</label>
            <input type="email" id="user-email" name="user_email" placeholder="User Email">
          </div>
          <div class="form-group">
            <label for="user-password">Password:</label>
            <input type="password" id="user-password" name="user_password" placeholder="Enter your password" required>
          </div>
          <button type="submit" class="btn">Add User</button>
        </form>
      </div>

      <div id="viewUserModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeModal('viewUserModal')">&times;</span>
        <h2>User Details</h2>
        <p><strong>ID:</strong> <span id="viewUserId"></span></p>
        <p><strong>Name:</strong> <span id="viewUserName"></span></p>
        <p><strong>Email:</strong> <span id="viewUserEmail"></span></p>
      </div>
    </div>

        <div id="editUserModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeModal('editUserModal')">&times;</span>
        <h2>Edit User</h2>
        <form id="editUserForm">
          <input type="hidden" id="editUserId" name="user_id">
          <div>
            <label>Name:</label>
            <input type="text" id="editUserName" name="user_name" required>
          </div>
          <div>
            <label>Email:</label>
            <input type="email" id="editUserEmail" name="user_email" required>
          </div>
          <button type="submit">Save Changes</button>
        </form>
      </div>
    </div>


      <!-- User Table -->
      <table class="user-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
            <?php
            // Include database configuration
            require_once '../db/config.php';

            // Query to fetch all users from the database
            $sql = "SELECT id, name, email FROM ashesis_users";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            // Fetch all users and display in table rows
            while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr data-user-id='" . htmlspecialchars($user['id']) . "'>";
                echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                echo "<td class='user-name'>" . htmlspecialchars($user['name']) . "</td>";
                echo "<td class='user-email'>" . htmlspecialchars($user['email']) . "</td>";
                echo "<td>
                        <button class='btn view' onclick='viewUser(" . htmlspecialchars($user['id']) . ")'>View</button>
                        <button class='btn edit' onclick='editUser(" . htmlspecialchars($user['id']) . ")'>Edit</button>
                        <button class='btn delete' onclick='deleteUser(" . htmlspecialchars($user['id']) . ")'>Delete</button>
                      </td>";
                echo "</tr>";
            }
            ?>
          </tbody>

      </table>
    </main>
  </div>

  <script>
        function viewUser(userId) {
        fetch("../functions/viewUser.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "user_id=" + userId,
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                document.getElementById("viewUserId").textContent = data.user.id;
                document.getElementById("viewUserName").textContent = data.user.name;
                document.getElementById("viewUserEmail").textContent = data.user.email;
                document.getElementById("viewUserModal").style.display = "block";
            } else {
                alert(data.message);
            }
        })
        .catch((error) => console.error("Error:", error));
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    function editUser(userId) {
    // Fetch user details and populate the edit form
    fetch("../functions/viewUser.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "user_id=" + userId,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            document.getElementById("editUserId").value = data.user.id;
            document.getElementById("editUserName").value = data.user.name;
            document.getElementById("editUserEmail").value = data.user.email;

            // Show the edit modal
            document.getElementById("editUserModal").style.display = "block";
        } else {
            alert(data.message);
        }
    })
    .catch((error) => console.error("Error:", error));
}

// Handle form submission for editing user
document.getElementById("editUserForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("../functions/editUser.php", {
        method: "POST",
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            alert(data.message);

            // Update the table dynamically
            const userId = document.getElementById("editUserId").value;
            const userName = document.getElementById("editUserName").value;
            const userEmail = document.getElementById("editUserEmail").value;

            const row = document.querySelector(`tr[data-user-id="${userId}"]`);
            if (row) {
                row.querySelector(".user-name").textContent = userName;
                row.querySelector(".user-email").textContent = userEmail;
            }

            closeModal("editUserModal");
        } else {
            alert(data.message);
        }
    })
    .catch((error) => console.error("Error:", error));
});

      function deleteUser(userId) {
          if (confirm("Are you sure you want to delete this user?")) {
              fetch("../functions/deleteUser.php", {
                  method: "POST",
                  headers: { "Content-Type": "application/x-www-form-urlencoded" },
                  body: "user_id=" + userId,
              })
              .then((response) => response.json())
              .then((data) => {
                  if (data.success) {
                      alert(data.message);

                      // Remove the user row from the table
                      const userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
                      if (userRow) {
                          userRow.remove();
                      }
                  } else {
                      alert(data.message);
                  }
              })
              .catch((error) => console.error("Error:", error));
          }
      }


</script>
</body>
</html>
