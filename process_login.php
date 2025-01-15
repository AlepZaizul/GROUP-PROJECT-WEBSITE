<?php
session_start(); // Start the session to manage user login

// Include database connection
include('db_connection.php');

// Capture form data
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Simple validation: Check if fields are empty
if (empty($username) || empty($password)) {
    echo "<h1>Please fill in both fields.</h1>";
    exit;
}

// Use prepared statements to avoid SQL injection
$sql = "SELECT user_id, username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if the username exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verify password using password_verify() (assuming password is hashed in the database)
    if (password_verify($password, $user['password'])) {
        // Store user data in session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];

        // Redirect to the homepage (index.php)
        header('Location: index.php');
        exit;
    } else {
        echo "<h1>Invalid username or password</h1>";
        echo '<a href="login.php">Go back to login</a>';
    }
} else {
    echo "<h1>Invalid username or password</h1>";
    echo '<a href="login.php">Go back to login</a>';
}

// Close connection
$conn->close();
?>
