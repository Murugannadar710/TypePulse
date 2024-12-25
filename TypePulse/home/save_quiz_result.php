<?php
// Database connection
$servername = "localhost";
$username = "root";  // Change this to your DB username
$password = "";  // Change this to your DB password
$dbname = "typepulse";  // Database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get data from POST request
$score = $_POST['score'];
$total_questions = $_POST['total_questions'];

// Insert score into the database
$sql = "INSERT INTO quiz_results (score, total_questions) VALUES (?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $score, $total_questions);

if ($stmt->execute()) {
  echo "Score saved successfully!";
} else {
  echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
