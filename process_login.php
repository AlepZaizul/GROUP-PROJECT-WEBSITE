<?php
session_start();

include('db_connection.php');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo "<h1>Please fill in both fields.</h1>";
    exit;
}

$sql = "SELECT user_id, username, role, password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (isset($user['password'])) { 
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; 

            if ($user['role'] == 'admin') {
                header('Location: Admin/admin_dashboard.php');
            } else {
                header('Location: index.php'); 
            }
            exit;
        } else {
            echo "<h1>Invalid username or password</h1>";
            echo '<a href="login.php">Go back to login</a>';
        }
    } else {
        echo "<h1>Error: Password hash not found for this user.</h1>"; 
        echo '<a href="login.php">Go back to login</a>';
    }
} else {
    echo "<h1>Invalid username or password</h1>";
    echo '<a href="login.php">Go back to login</a>';
}

$stmt->close();
$conn->close();
?>