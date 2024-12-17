<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Ride Booking</title>
    <link rel="icon" href="../assets/images/buslogo.png" type="png">
    <link rel="stylesheet" href="../assets/css/bookingstyles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <header class="navbar">
        <div class="logo">Ashesi Rides</div>
        <button class="hamburger" onclick="toggleMenu()">☰</button>
        <a href="../actions/logout.php">
        <button class="btn">Log out</button>
        </a>
    </header>

    <!-- Side Menu -->
    <div id="side-menu" class="side-menu">
        <div class="menu-header">
            <p>+91 12345-67890</p>
            <p>Version 1.32</p>
        </div>
        <ul>
            <li><a href="listing.php">Bus Listings</a></li>
            <li><a href="displayticket.php">Your Tickets</a></li>
            <li><a href="review.php">Submit a Review</a><li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-container">
        <!-- Search Section -->
        <section class="search-section">
            <div class="search-container">
                <label for="from">From</label>
                <select id="from">
                    <option value="Ashesi">Ashesi</option>
                </select>

                <label for="to">To</label>
                <select id="to">
                    <option value="Accra">East Legon</option>
                    <option value="Ashesi">Cantonments</option>
                    <option value="Ashesi">Accra Mall</option>

                </select>

                <label for="date">Date</label>
                <input type="date" id="date">

                <a href="listing.php">
                    <button class="search-btn">Search</button>
</a>
            </div>
        </section>

        <!-- Info Cards Section -->
        <section class="info-cards">
            <div class="card">
                <img src="../assets/images/busb.jpeg" alt="Safe Vehicles">
                <p>Safe and Hygienic Vehicles</p>
            </div>
            <div class="card">
                <img src="../assets/images/customersupport.jpeg" alt="Customer Support">
                <p>Best Customer Support</p>
            </div>
            <div class="card">
                <img src="../assets/images/viewseats.jpeg" alt="Track Your Journey">
                <p>View Available Seats</p>
            </div>
            <div class="card">
                <img src="../assets/images/verifieddrivers.jpeg" alt="Verified Drivers">
                <p>Verified Drivers and Vehicles</p>
            </div>
        </section>

        <!-- Additional Section -->
        <section class="additional-section">
            <h2>Bus Discounts For You</h2>
            <p>Enjoy up to 20% off on your first booking!</p>
        </section>
    </main>

    <script>
        function toggleMenu() {
            const sideMenu = document.getElementById('side-menu');
            sideMenu.classList.toggle('active');
        }
    </script>
</body>
</html>
