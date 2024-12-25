<?php
session_start();
require_once 'db.php';  // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL query
    $query = "SELECT * FROM users WHERE username = :username OR email = :username LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Successful login
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Show success popup message before redirection
        echo "<script>
                alert('Login successful! Redirecting to the typepulse...');
                setTimeout(function() {
                    window.location.href = 'home/index.html'; // Redirect to games
                }, 2000); // 2 seconds delay before redirection
              </script>";
        exit;
    } else {
        // Invalid login
        echo "<script>alert('Invalid username or password. Please try again.');</script>";
    }
}
?>
