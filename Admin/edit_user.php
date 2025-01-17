<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../db_connection.php'; // Include database connection

// Fetch user details
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $user_query = "SELECT * FROM users WHERE user_id = $user_id";
    $user_result = mysqli_query($conn, $user_query);
    $user = mysqli_fetch_assoc($user_result);
    if (!$user) {
        die("User not found.");
    }
} else {
    header("Location: manage_users.php");
    exit;
}

// Update user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // Optionally hash this
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $update_query = "UPDATE users SET 
        username = '$username',
        password = '$password', 
        email = '$email',
        full_name = '$full_name',
        phone = '$phone',
        state = '$state',
        address = '$address',
        role = '$role'
        WHERE user_id = $user_id";

    if (mysqli_query($conn, $update_query)) {
        echo "<script>
            alert('User updated successfully!');
            window.location.href = 'manage_users.php';
        </script>";
    } else {
        echo "Error updating user: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <!-- Header Start -->
    <?php include 'admin_header.php'; ?>
    <!-- Header End -->

    <div class="container">
        <h1>Edit User</h1>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($user['password']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

            <label for="state">State:</label>
            <input type="text" name="state" id="state" value="<?php echo htmlspecialchars($user['state']); ?>" required>

            <label for="address">Address:</label>
            <textarea name="address" id="address" rows="4" required><?php echo htmlspecialchars($user['address']); ?></textarea>

            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
            </select>

            <button type="submit" class="save">Save Changes</button>
            <a href="manage_users.php" class="cancel">Cancel</a>
        </form>
    </div>

    <footer>
        <p>&copy; DTD3033 Class D - Group 8</p>
    </footer>
</body>
</html>
