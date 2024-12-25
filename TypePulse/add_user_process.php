<?php
// Set header for JSON response
header('Content-Type: application/json');

// Database connection parameters
$servername = 'localhost';         // Database host (usually 'localhost' for local development)
$username = 'root';                // Database username (update with your actual username, 'root' for local setups)
$password = '';                    // Database password (update with your actual password, leave empty if none)
$dbname = 'typepulse';             // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user input from the form
    $user_username = $conn->real_escape_string($_POST['username']);
    $user_email = $conn->real_escape_string($_POST['email']);
    $user_password = $conn->real_escape_string($_POST['password']);  // No password hashing

    // Prepare SQL query to insert the new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$user_username', '$user_email', '$user_password')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the admin page with success message
        header("Location: admin.html?message=User added successfully");
        exit();
    } else {
        // Handle error if insertion fails
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
