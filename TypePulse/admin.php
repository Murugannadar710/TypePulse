<?php
// Set header for JSON response
header('Content-Type: application/json');

// Database connection parameters
$host = 'localhost';         // Database host (usually 'localhost' for local development)
$user = 'root';              // Database username (update with your actual username, 'root' for local setups)
$password = '';              // Database password (update with your actual password, leave empty if none)
$dbname = 'typepulse';       // Database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection and handle errors
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Fetch users data
$usersResult = $conn->query("SELECT * FROM users");
$users = [];
while ($row = $usersResult->fetch_assoc()) {
    $users[] = $row;
}

// Fetch other data (quiz results, typing results, scores)
$quizResultsResult = $conn->query("SELECT * FROM quiz_results");
$quizResults = [];
while ($row = $quizResultsResult->fetch_assoc()) {
    $quizResults[] = $row;
}

$typingResultsResult = $conn->query("SELECT * FROM typing_results");
$typingResults = [];
while ($row = $typingResultsResult->fetch_assoc()) {
    $typingResults[] = $row;
}

$scoresResult = $conn->query("SELECT * FROM scores");
$scores = [];
while ($row = $scoresResult->fetch_assoc()) {
    $scores[] = $row;
}

// Prepare the response array
$response = [
    'users' => $users,
    'quizResults' => $quizResults,
    'typingResults' => $typingResults,
    'scores' => $scores
];

// Return the response as JSON
echo json_encode($response);

// Close connection
$conn->close();
?>
