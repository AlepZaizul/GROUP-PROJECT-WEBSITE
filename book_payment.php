<?php
session_start();
include 'db_connection.php'; // Ensure this file establishes the database connection

// Get the POST data from the form
$room_id = $_POST['room_id'];
$room_name = $_POST['room_name'];
$room_price = $_POST['room_price'];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$adults = $_POST['adults'];
$children = $_POST['children'];
$special_request = $_POST['special_request'];
$total_price = $_POST['total_price'];

// You can integrate a payment API here (like PayPal or Stripe)
// For now, we'll just assume the payment is successful and proceed with the booking

// Insert the booking details into the database
$user_id = $_SESSION['user_id']; // Get user ID from session

// Insert booking into the bookings table
$insert_booking_sql = "INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, adults, children, special_request, total_price, booking_status)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";

$stmt = $conn->prepare($insert_booking_sql);
$stmt->bind_param("iissiiis", $user_id, $room_id, $checkin, $checkout, $adults, $children, $special_request, $total_price);

if ($stmt->execute()) {
    // Redirect to booking confirmation page or payment gateway
    echo "Booking Confirmed! Please proceed with payment.";
} else {
    echo "Error: " . $stmt->error;
}

// Close the database connection
$stmt->close();
mysqli_close($conn);
?>
