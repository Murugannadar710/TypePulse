<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "typepulse";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch all data from typing_results
$sql = "SELECT id, wpm, cpm, mistakes, timestamp FROM typing_results ORDER BY id DESC"; // Fetch the 'timestamp' field
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch data as an associative array
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = $row; // Add each row of data to the array
    }

    // Return the data as JSON
    echo json_encode($data);
} else {
    // If no data found, return an empty array
    echo json_encode([]);
}

// Close the connection
$conn->close();
?>
