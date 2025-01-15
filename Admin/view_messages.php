<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../db_connection.php'; // Include database connection

// Fetch all messages from the contact_form table
$messages_query = "SELECT * FROM contact_form";
$messages_result = mysqli_query($conn, $messages_query);
if (!$messages_result) {
    die("Error fetching messages: " . mysqli_error($conn));
}

// Handle message deletion
if (isset($_GET['delete'])) {
    $message_id = $_GET['delete'];
    $delete_query = "DELETE FROM contact_form WHERE id = '$message_id'";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: view_message.php");
    } else {
        echo "Error deleting message: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Messages</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <!-- Header Start -->
    <?php include 'admin_header.php'; ?>
    <!-- Header End -->

    <div class="container">
        <h1>View Messages</h1>

        <div>
            <h2>Messages</h2>
            <table>
                <thead>
                    <tr>
                        <th>Sender Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($message = mysqli_fetch_assoc($messages_result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($message['cust_name']); ?></td>
                            <td><?php echo htmlspecialchars($message['cust_email']); ?></td>
                            <td><?php echo htmlspecialchars($message['cust_subject']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($message['cust_message'])); ?></td>
                            <td>
                                <a href="view_message.php?delete=<?php echo $message['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this message?');">Delete</a>
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
