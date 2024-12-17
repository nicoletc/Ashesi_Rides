<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rush - Submit a Review</title>
  <link rel="icon" href="../assets/images/buslogo.png" type="png">
  <link rel="stylesheet" href="../assets/css/reviewpage.css">
</head>
<body style="background-image: url('../assets/pic.jpeg')">

<div class="container">
  <h1>Submit a Review</h1>

  <div class="review-form">
    <form action="../actions/submit_review.php" method="POST">
      <label for="rating">Rating (1-5):</label>
      <input type="number" id="rating" name="rating" min="1" max="5" required>

      <label for="comment">Comment:</label>
      <textarea id="comment" name="comment" rows="4" required></textarea>

      <button type="submit">Submit Review</button>
    </form>
  </div>
</div>


<footer>
  <p>Contact us at: <a href="mailto:ashesi.com">ashesirides.com</a></p>
  <p>Call us: (+233) 206-937-890</p>
  <p>&copy; 2024 Ashesi Rides. All rights reserved.</p>
</footer>

</body>
</html>
