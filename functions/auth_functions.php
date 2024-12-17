<?php
session_start();


function coreCheckLogin($requiredRole = null) {
    // Check if the user is logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        // User is not logged in, redirect to login page
        header("Location: ../view/Login.php");
        exit();
    }

    // If a specific role is required, verify the user's role
    if ($requiredRole && (!isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole)) {
        // Redirect to the appropriate dashboard
        if ($_SESSION['role'] === 'admin') {
            header("Location: ../view/dashboard.php");
        } elseif ($_SESSION['role'] === 'student') {
            header("Location: ../view/booking.php");
        } else {
            // If the role is unrecognized, log out
            header("Location: ../actions/logout.php");
        }
        exit();
    }
}
