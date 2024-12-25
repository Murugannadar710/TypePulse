<?php
// Get the incoming JSON data
$data = json_decode(file_get_contents("php://input"));

// Extract the word, hint, and result from the request
$word = $data->word;
$hint = $data->hint;
$result = $data->result;

// Database connection details
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "typepulse"; // The database where you want to store the word data

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL query
$stmt = $conn->prepare("INSERT INTO word_results (word, hint, result) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $word, $hint, $result);

// Execute the query
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
}

// Close the database connection
$stmt->close();
$conn->close();
?>
