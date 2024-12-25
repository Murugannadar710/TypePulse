<?php
// Database connection parameters
$host = 'localhost';         // Database host (usually 'localhost' for local development)
$user = 'root';              // Database username (update with your actual username, 'root' for local setups)
$password = '';              // Database password (update with your actual password, leave empty if none)
$dbname = 'typepulse';       // Database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection and handle errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data to edit
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // SQL query to fetch the user by ID
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        die("User not found.");
    }
    
    $stmt->close();
}

// Handle form submission to update user details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['username'], $_POST['email'], $_POST['password'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Directly use the password entered

    // If the password is empty, retain the old password
    if (empty($password)) {
        $password = $user['password'];
    }

    // SQL query to update the user data
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $password, $id);

    if ($stmt->execute()) {
        echo "User updated successfully!";
        header("Location: add.php"); // Redirect back to the user list page after updating
    } else {
        echo "Error updating user: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin Panel</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #2C3E50;
            color: #ECF0F1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        h2 {
            color: #E74C3C;
            margin-bottom: 20px;
        }
        form {
            background: #34495E;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            width: 80%;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #7F8C8D;
            border-radius: 8px;
            background-color: #BDC3C7;
            color: #2C3E50;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #E74C3C;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #C0392B;
        }
        .back-link {
            margin-top: 20px;
        }
        .back-link a {
            color: #ECF0F1;
            text-decoration: none;
            font-size: 16px;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h2>Edit User</h2>
    
    <form method="POST" action="edit.php?id=<?php echo $user['id']; ?>">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="password">Password:</label>
        <div style="position: relative;">
            <input type="password" id="password" name="password" required> <i class="eye-icon" id="toggle-password">
                👁️
            </i>
           
        </div>

        <button type="submit">Update User</button>
    </form>

    <div class="back-link">
        <a href="add.php">Back to Users List</a>
    </div>
    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('toggle-password');
        const passwordField = document.getElementById('password');
        
        togglePassword.addEventListener('click', () => {
            // Toggle the input type between password and text
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            // Toggle eye icon
            togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>
</body>
</html>
