<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Listings</title>
    <link rel="icon" href="../assets/images/buslogo.png" type="png">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-image: url('../assets/images/available.jpg');
            padding: 20px;
        }

        .header {
            text-align: center;
            padding-bottom: 50px;
        }

        /* Grid Container */
        .bus-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        /* Card Styling */
        .bus-card {
            display: flex;
            align-items: center;
            background-color:rgb(248, 236, 244);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
            overflow: hidden;
            position: relative;
            cursor: pointer; /* Make it clear the card is clickable */
            transition: transform 0.2s ease-in-out; /* Smooth hover animation */
        }

        .bus-card:hover {
            transform: translateY(-5px);
        }

        .bus-card .left-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 120px;
        }

        h1 {
            color: white;
        }

        .bus-card .left-section img {
            width: 80px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .bus-card .left-section .ratings {
            font-size: 14px;
            color: #666;
        }

        .bus-card .dotted-line {
            border-left: 2px dotted #ccc;
            height: 100%;
            margin: 0 15px;
        }

        .bus-card .right-section {
            flex: 1;
        }

        .bus-card .right-section h3 {
            font-size: 16px;
            margin-bottom: 5px;
            color: #333;
        }

        .bus-card .right-section p {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .search-container {
        display: flex-end;
        gap: 10px;
        }

        .search-container input {
        width: 250px;
        padding: 8px;
        font-size: 14px;
        border-radius: 5px;
        border: 1px solid #ccc;
        }

        .search-container button {
        background-color: #e00026;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        }

        .search-container button:hover {
        background-color: #b0001b;
        }


        .bus-card .right-section .fare {
            font-size: 20px;
            font-weight: bold;
            color: #e00026;
            text-align: right;
            margin-top: 10px;
        }

        /* Modal Styling */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            text-align: center;
        }

        .modal-content h2 {
            margin-bottom: 15px;
            color: #333;
        }

        .modal-content p {
            margin-bottom: 15px;
            color: #555;
        }

        button close-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background: #e00026;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background: #e00026;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Available Rides</h1>
        <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search for rides..." style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
        <button id="searchButton">Search</button>
    </div>
    </div>

    <!-- Bus Cards Grid -->
    <div class="bus-list">
    <?php
    require_once '../db/config.php';

    try {
        // Fetch bookings from the database including bus_name
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Update SQL query with a WHERE clause for search
$sql = "SELECT id, journey_date, departure_time, route, fare, image_url, bus_name 
        FROM ashesis_bookings 
        WHERE bus_name LIKE :search 
           OR route LIKE :search 
           OR journey_date LIKE :search";

$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);


        // Display each booking as a bus card
        while ($booking = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="bus-card" data-info="' . htmlspecialchars($booking['journey_date']) . ', ' . htmlspecialchars($booking['departure_time']) . '|'
                . htmlspecialchars($booking['route']) . '|GH₵' . htmlspecialchars($booking['fare']) . '" data-id="' . htmlspecialchars($booking['id']) . '">';
            echo '<div class="left-section">';
            echo '<img src="' . (!empty($booking['image_url']) ? htmlspecialchars($booking['image_url']) : '../assets/images/default-bus.jpeg') . '" alt="Bus">';
            echo '</div>';
            echo '<div class="dotted-line"></div>';
            echo '<div class="right-section">';
            echo '<h3>' . htmlspecialchars($booking['bus_name']) . '</h3>'; // Display bus_name
            echo '<p>Journey Start: ' . htmlspecialchars($booking['journey_date']) . ', ' . htmlspecialchars($booking['departure_time']) . '</p>';
            echo '<p>From - To: ' . htmlspecialchars($booking['route']) . '</p>';
            echo '<p class="fare">GH₵' . htmlspecialchars($booking['fare']) . '</p>';
            echo '</div>';
            echo '</div>';
        }
    } catch (PDOException $e) {
        echo '<p>Error fetching bookings: ' . $e->getMessage() . '</p>';
    }
    ?>
</div>


    <!-- Modal Structure -->
    <div class="modal" id="popupModal">
    <div class="modal-content">
        <h2>Additional Information</h2>
        <div id="modalContent"></div>
        <button class="close-btn" onclick="closeModal()">Close</button>
        <a href="BusseatSelect.php"><button type="submit">Book a Seat</button></a>
    </div>
</div>


<script>
        document.querySelectorAll('.bus-card').forEach((card) => {
    card.addEventListener('click', function () {
        const additionalInfo = this.getAttribute('data-info');
        const busId = this.getAttribute('data-id'); // Fetch the bus ID from data-id attribute
        const modalContent = document.getElementById('modalContent');

        // Split the data into separate pieces
        const infoParts = additionalInfo.split('|');
        modalContent.innerHTML = `
            <p><strong>Journey Time:</strong> ${infoParts[0]}</p>
            <p><strong>Route:</strong> ${infoParts[1]}</p>
            <p><strong>Fare:</strong> ${infoParts[2]}</p>
        `;

        // Update the "Book a Seat" button link dynamically
        const bookSeatButton = document.querySelector('#popupModal a');
        bookSeatButton.setAttribute('href', `BusseatSelect.php?id=${busId}`);

        // Display the modal
        document.getElementById('popupModal').style.display = 'flex';
    });
});

function closeModal() {
    document.getElementById('popupModal').style.display = 'none';
}

document.getElementById('searchButton').addEventListener('click', () => {
    const searchInput = document.getElementById('searchInput').value.trim();
    if (searchInput) {
        window.location.href = `?search=${encodeURIComponent(searchInput)}`;
    } else {
        alert('Please enter a search term.');
    }
});
</script>
</body>
</html>
