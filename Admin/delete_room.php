<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../db_connection.php'; // Include database connection

if (isset($_GET['id'])) {
    $room_id = intval($_GET['id']); // Sanitize the ID

    // Delete room query
    $delete_query = "DELETE FROM rooms WHERE room_id = $room_id";
    $result = mysqli_query($conn, $delete_query);

    if ($result) {
        echo "<script>
            alert('Room deleted successfully!');
            window.location.href = 'edit_room.php';
        </script>";
    } else {
        echo "<script>
            alert('Failed to delete room: " . mysqli_error($conn) . "');
            window.location.href = 'edit_room.php';
        </script>";
    }
} else {
    echo "<script>
        alert('Invalid room ID.');
        window.location.href = 'edit_room.php';
    </script>";
}
?>
