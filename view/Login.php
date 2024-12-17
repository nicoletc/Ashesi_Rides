
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Ashesi Campus Ride Booking System</title>
  <link rel="icon" href="../assets/images/buslogo.png" type="png">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/loginxsignup.css">
</head>
<body>
  <section class="login-signup-container">
    <div class="image-side">
      <img src="../assets/images/bus2.jpeg" alt="Campus Ride" class="login-image">
    </div>


    <div class="form-side">
        <div class="form-container" id="login-form">
            <form action="../actions/login.php" method="post">
                <h2>Welcome Back!</h2>
    
                <div class="input-box">
                    <label for="login-email">Email:</label>
                    <input type="email" id="login-email" name="login-email" placeholder="Enter your email" required>
                </div>
    
                <div class="input-box">
                    <label for="login-password">Password:</label>
                    <input type="password" id="login-password" name="login-password" placeholder="Enter your password" required>
                </div>
    
                <a href="booking.php">
                  <button type="submit">Login</button>
                </a>
    
                <p><a href="forgot-password.html">Forgot Password?</a></p>
                <p>Don't have an account? <a href="Signup.php">Sign up here</a></p>
            </form>
        </div>
    
    </body>
    </html>
    