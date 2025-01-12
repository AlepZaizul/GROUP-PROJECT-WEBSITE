<?php
// Simulate a database connection (replace with actual database queries in production)
$users = [
    'user1' => 'password123',
    'admin' => 'adminpass'
];

// Capture form data
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Simple validation
if (isset($users[$username]) && $users[$username] === $password) {
    echo "<h1>Welcome, " . htmlspecialchars($username) . "!</h1>";
} else {
    echo "<h1>Invalid username or password</h1>";
    echo '<a href="login.php">Go back to login</a>';
}
?>
