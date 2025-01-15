<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../db_connection.php'; // Include database connection

// Get booking ID from the query string
if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    // Fetch the booking details to get the room_id
    $booking_query = "SELECT room_id FROM bookings WHERE booking_id = $booking_id";
    $booking_result = mysqli_query($conn, $booking_query);
    if (!$booking_result) {
        die("Error fetching booking details: " . mysqli_error($conn));
    }

    $booking = mysqli_fetch_assoc($booking_result);
    if ($booking) {
        $room_id = $booking['room_id'];

        // Increase room availability by 1
        $update_availability_query = "UPDATE rooms SET room_availability = room_availability + 1 WHERE room_id = $room_id";
        $update_result = mysqli_query($conn, $update_availability_query);
        if (!$update_result) {
            die("Error updating room availability: " . mysqli_error($conn));
        }

        // Delete the booking from the bookings table
        $delete_booking_query = "DELETE FROM bookings WHERE booking_id = $booking_id";
        $delete_result = mysqli_query($conn, $delete_booking_query);
        if (!$delete_result) {
            die("Error deleting booking: " . mysqli_error($conn));
        }

        // Redirect back to the bookings list or confirmation page
        header("Location: admin_dashboard.php?status=cancelled");
        exit;
    } else {
        echo "Booking not found.";
    }
} else {
    echo "Invalid booking ID.";
}
?>
