<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../db_connection.php'; // Include database connection

// Fetch all users from the database
$users_query = "SELECT * FROM users";
$users_result = mysqli_query($conn, $users_query);
if (!$users_result) {
    die("Error fetching users: " . mysqli_error($conn));
}

// Handle user deletion
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $delete_query = "DELETE FROM users WHERE user_id = '$user_id'";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: manage_users.php");
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <!-- Header Start -->
    <?php include 'admin_header.php'; ?>
    <!-- Header End -->

    <div class="container">
        <h1>Manage Users</h1>

        <div>
            <h2>Registered Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($users_result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['phone']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $user['user_id']; ?>" class="edit">Edit</a>
                                <a href="manage_users.php?delete=<?php echo $user['user_id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <p>&copy; DTD3033 Class D - Group 8</p>
    </footer>
</body>
</html>
