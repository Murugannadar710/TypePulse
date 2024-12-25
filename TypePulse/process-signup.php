<?php
// Database connection
$host = 'localhost'; // Change if necessary
$db = 'typepulse';  // Database name
$user = 'root';      // Database username
$pass = '';          // Database password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validate password match
if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

// Get current date and time
$created_at = date('Y-m-d H:i:s');

// Insert data into the database
$sql = "INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, ?)";

// Prepare and bind statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $username, $email, $password, $created_at); // No password hash

// Execute query and check for success
if ($stmt->execute()) {
    echo "User registered successfully!";
    // Optionally, redirect to login page or home page
    header("Location: login.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

// Close the connection
$stmt->close();
$conn->close();
?>
