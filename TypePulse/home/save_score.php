<?php
// Get the incoming JSON data
$data = json_decode(file_get_contents("php://input"));

// Extract the score from the request
$score = $data->score;

// Database connection details
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "typepulse"; // The database where you want to store the score

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert score into the database
$sql = "INSERT INTO scores (score) VALUES (?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $score); // "i" stands for integer
$stmt->execute();

// Check if the score was saved successfully
if ($stmt->affected_rows > 0) {
    echo json_encode(["status" => "success", "message" => "Score saved"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to save score"]);
}

// Close connection
$stmt->close();
$conn->close();
?>
