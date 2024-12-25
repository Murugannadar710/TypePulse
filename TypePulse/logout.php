<?php
// Start session to handle session data
session_start();

// Check if the user is logged in, if yes, log out
if (isset($_SESSION['user_id'])) {
    // Unset session variables to log out
    session_unset();
    session_destroy();
    
    // Redirect to index.html after logout
    header("Location: index.html");
    exit();
} else {
    // If not logged in, redirect to login page
    header("Location: index.html");
    exit();
}
?>
