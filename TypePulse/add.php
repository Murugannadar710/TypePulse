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

// Handle adding a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['email'], $_POST['password'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Plain text password (no hashing)

    // SQL query to insert the new user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo "New user added successfully!";
    } else {
        echo "Error adding user: " . $stmt->error;
    }

    $stmt->close();
}

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // SQL query to delete user
    $deleteQuery = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        echo "User deleted successfully!";
    } else {
        echo "Error deleting user: " . $stmt->error;
    }
    
    $stmt->close();
}

// Fetch all users to display
$usersResult = $conn->query("SELECT * FROM users");
$users = [];
while ($row = $usersResult->fetch_assoc()) {
    $users[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - Admin Panel</title>
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
        table {
            border-collapse: collapse;
            width: 80%;
            background: #34495E;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #E74C3C;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #BDC3C7;
        }
        tr:nth-child(odd) {
            background-color: #95A5A6;
        }
        td {
            color: #2C3E50;
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
        .action-buttons button {
            background-color:rgb(22, 137, 119);
            margin: 8px;

            margin-right: 5px;
        }
        .action-buttons button:hover {
            background-color: #2980B9;
        }
        /* Eye Icon Styles */
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h2>Add New User</h2>
    <form method="POST" action="add.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <div style="position: relative;">
            <input type="password" id="password" name="password" required>
            <i class="eye-icon" id="toggle-password">
                👁️
            </i>
        </div>

        <button type="submit">Save User</button>
    </form>

    <h2>Users List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Password</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['password']); ?></td>

                <td class="action-buttons">
                    <a href="edit.php?id=<?php echo $user['id']; ?>">
                        <button>Edit</button>
                    </a>
                    <a href="add.php?delete_id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">
                        <button>Delete</button>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="back-link">
        <a href="admin.html">Back to Admin Panel</a>
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
