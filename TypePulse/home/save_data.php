<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "typepulse";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the JSON data from the request
$data = json_decode(file_get_contents("php://input"));

// Extract WPM, CPM, and mistakes
$wpm = $data->wpm;
$cpm = $data->cpm;
$mistakes = $data->mistakes;

// Insert the data into the database
$sql = "INSERT INTO typing_results (wpm, cpm, mistakes) VALUES ('$wpm', '$cpm', '$mistakes')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Data saved successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
}

$conn->close();
?>
