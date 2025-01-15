<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../db_connection.php'; // Include database connection

if (isset($_GET['id'])) {
    $room_id = $_GET['id'];

    // Fetch room details to get the image filename
    $sql = "SELECT room_image FROM rooms WHERE room_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();

    if ($room) {
        $image_name = $room['room_image'];
        if ($image_name) {
            // Delete image from the server (ensure the correct path)
            $image_path = '../img/' . $image_name;
            if (file_exists($image_path)) {
                unlink($image_path); // Delete the image file from the server
            }

            // Update the database to set the image field to NULL
            $update_sql = "UPDATE rooms SET room_image = NULL WHERE room_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param('i', $room_id);
            $update_stmt->execute();
        }
    }

    // Redirect back to the edit page after deletion
    header("Location: edit_room.php");
    exit;
}
?>
