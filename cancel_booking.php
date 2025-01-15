<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if booking_id is provided
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
    $user_id = $_SESSION['user_id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Step 1: Get the room_id from the booking table
        $sql = "SELECT room_id FROM bookings WHERE booking_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $booking_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $booking = $result->fetch_assoc();
            $room_id = $booking['room_id'];

            // Step 2: Update booking status to 'cancelled'
            $update_booking_sql = "UPDATE bookings SET booking_status = 'cancelled' WHERE booking_id = ? AND user_id = ?";
            $update_stmt = $conn->prepare($update_booking_sql);
            $update_stmt->bind_param('ii', $booking_id, $user_id);
            $update_stmt->execute();

            // Step 3: Update the room availability (increment by 1)
            $update_room_sql = "UPDATE rooms SET room_availability = room_availability + 1 WHERE room_id = ?";
            $room_stmt = $conn->prepare($update_room_sql);
            $room_stmt->bind_param('i', $room_id);
            $room_stmt->execute();

            // Commit the transaction
            $conn->commit();

            // Redirect to my_book.php with success message
            header('Location: my_book.php?message=Booking+Cancelled+and+Room+Availability+Restored');
        } else {
            echo "Booking not found or invalid user.";
        }
    } catch (Exception $e) {
        // If an error occurs, roll back the transaction
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Booking ID not provided.";
}
?>
