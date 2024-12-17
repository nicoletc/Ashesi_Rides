<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Ashesi Campus Ride Booking System</title>
  <link rel="icon" href="../assets/images/buslogo.png" type="png">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/loginxsignup.css">
</head>
<body>
  <section class="login-signup-container">
    <!-- Left Side (Image) -->
    <div class="image-side">
      <img src="../assets/images/bus2.jpeg" alt="Campus Ride" class="signup-image">
    </div>

    <!-- Right Side (Sign Up Form) -->
    <div class="form-side">
        <div class="form-container" id="sign-up-form">
            <form action="../actions/register.php" method="post" onsubmit="return checkPasswordMatch()">
                <h2>Welcome! Let’s Get You Signed Up</h2>
                <p>Create an account to discover rides easy!</p>
    
                <div class="input-row">
                    <div class="input-box">
                        <label for="first-name">First Name:</label>
                        <input type="text" id="first-name" name="first-name" placeholder="Your First name" required>
                    </div>
                
                    <div class="input-box">
                        <label for="last-name">Last Name:</label>
                        <input type="text" id="last-name" name="last-name" placeholder="Your Last name" required>
                    </div>
                </div>            
    
                <div class="input-box">
                    <label for="signup-email">Your Email Address:</label>
                    <input type="email" id="signup-email" name="signup-email" placeholder="Enter a valid email" required>
                    <small>We'll send you a confirmation email.</small>
                </div>
    
                <div class="input-box">
                    <label for="signup-password">Create a Password:</label>
                    <input type="password" id="signup-password" name="signup-password" placeholder="Enter your Password" required onfocus="showRequirements()" oninput="validatePassword()">
                </div>
    
                <ul id="password-requirements" style="display: none;">
                    <li id="min-length">At least 8 characters</li>
                    <li id="uppercase">One uppercase letter</li>
                    <li id="three-digits">At least three digits</li>
                    <li id="special-char">One special character</li>
                </ul>
    
                <div class="input-box">
                    <label for="signup-confirm-password">Confirm Your Password:</label>
                    <input type="password" id="signup-confirm-password" name="signup-confirm-password" placeholder="Re-enter your password" required>
                </div>
    
                <p id="password-error" style="color: red; display: none;"></p>
    
                <button type="submit">Sign Me Up</button>
    
                <p>Already have an account? <a href="Login.php">Log in here</a></p>
            </form>
        </div>
    
        <script>
            function showRequirements() {
                document.getElementById('password-requirements').style.display = 'block';
            }
    
            function validatePassword() {
                const password = document.getElementById('signup-password').value;
    
                // Check if password has at least 8 characters
                if (password.length >= 8) {
                    document.getElementById('min-length').style.textDecoration = 'line-through';
                } else {
                    document.getElementById('min-length').style.textDecoration = 'none';
                }
    
                // Check if password contains at least one uppercase letter
                if (/[A-Z]/.test(password)) {
                    document.getElementById('uppercase').style.textDecoration = 'line-through';
                } else {
                    document.getElementById('uppercase').style.textDecoration = 'none';
                }
    
                // Check if password contains at least three digits
                const digitMatches = password.match(/\d/g);
                if (digitMatches && digitMatches.length >= 3) {
                    document.getElementById('three-digits').style.textDecoration = 'line-through';
                } else {
                    document.getElementById('three-digits').style.textDecoration = 'none';
                }
    
                // Check if password contains at least one special character
                if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                    document.getElementById('special-char').style.textDecoration = 'line-through';
                } else {
                    document.getElementById('special-char').style.textDecoration = 'none';
                }
            }
    
            function checkPasswordMatch() {
                const password = document.getElementById('signup-password').value;
                const confirmPassword = document.getElementById('signup-confirm-password').value;
                const errorMessage = document.getElementById('password-error');
    
                if (password !== confirmPassword) {
                    errorMessage.textContent = "Passwords do not match.";
                    errorMessage.style.display = "block";
                    return false; // Prevent form submission
                }
    
                // Hide error message if passwords match
                errorMessage.style.display = "none";
                return true; // Allow form submission
            }
        </script>
    </body>
    </html>
    