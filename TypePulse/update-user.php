<?php
header('Content-Type: application/json');

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'typepulse';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $conn->real_escape_string($data['id']);
$username = $conn->real_escape_string($data['username']);
$email = $conn->real_escape_string($data['email']);
$password = $conn->real_escape_string($data['password']);

$query = "UPDATE users SET username='$username', email='$email', password='$password' WHERE id=$id";

if ($conn->query($query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to update user: ' . $conn->error]);
}
?>
