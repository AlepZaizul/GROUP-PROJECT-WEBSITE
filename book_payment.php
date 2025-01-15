<?php
session_start();
include 'db_connection.php'; 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the POST data
    $user_id = $_SESSION['user_id'];
    $room_id = $_POST['room_id'];
    $room_name = $_POST['room_name'];
    $room_price = $_POST['room_price'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $adults = $_POST['adults'];
    $children = $_POST['children'];
    $special_request = $_POST['special_request'];
    $total_price = $_POST['total_price'];
    
    // Check if payment_method is set in the POST data
    if (isset($_POST['payment_method'])) {
        $payment_method = $_POST['payment_method'];
    } else {
        echo "Error: Payment method not selected!";
        exit;
    }

    // Convert check-in and check-out date format (from MM/DD/YYYY HH:MM AM/PM to YYYY-MM-DD HH:MM:SS)
    $checkin_date = DateTime::createFromFormat('m/d/Y h:i A', $checkin);
    $checkout_date = DateTime::createFromFormat('m/d/Y h:i A', $checkout);

    if ($checkin_date && $checkout_date) {
        $checkin = $checkin_date->format('Y-m-d H:i:s');
        $checkout = $checkout_date->format('Y-m-d H:i:s');
    } else {
        echo "Error: Invalid date format!";
        exit;
    }

    // Automatically set booking_status to 'confirmed'
    $booking_status = 'confirmed';
    $created_at = date('Y-m-d H:i:s');
    
    // Begin a transaction to ensure data integrity
    $conn->begin_transaction();


}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'head_class.php'; ?>
</head>

<body>
    <div class="container-xxl bg-white p-0">

        <!-- Header Start -->
        <?php include 'header.php'; ?>
        <!-- Header End -->

        <div class="container-xxl py-5">
            <div class="container">
                <?php
            try {
        // Step 1: Insert data into bookings table
        $sql_booking = "INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, adults, children, special_request, total_price, payment_method, booking_status, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_booking);
        $stmt->bind_param('iissiiissss', $user_id, $room_id, $checkin, $checkout, $adults, $children, $special_request, $total_price, $payment_method, $booking_status, $created_at);
        $stmt->execute();
        
        // Get the last inserted booking ID
        $booking_id = $stmt->insert_id;
        $stmt->close();

        // Step 2: Update room availability in rooms table (decrease by 1)
        $sql_update_room = "UPDATE rooms SET room_availability = room_availability - 1 WHERE room_id = ?";
        $stmt = $conn->prepare($sql_update_room);
        $stmt->bind_param('i', $room_id);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $conn->commit();

        // Step 3: Display booking confirmation details
        echo '<div class="container py-5">';
        echo '<h2>Your Booking is Confirmed!</h2>';
        echo '<p><strong>Booking ID: </strong>' . $booking_id . '</p>';
        echo '<p><strong>Room: </strong>' . htmlspecialchars($room_name) . '</p>';
        echo '<p><strong>Check-in: </strong>' . date('d/m/Y', strtotime($checkin)) . '</p>';
        echo '<p><strong>Check-out: </strong>' . date('d/m/Y', strtotime($checkout)) . '</p>';
        echo '<p><strong>Adults: </strong>' . htmlspecialchars($adults) . '</p>';
        echo '<p><strong>Children: </strong>' . htmlspecialchars($children) . '</p>';
        echo '<p><strong>Special Request: </strong>' . htmlspecialchars($special_request) . '</p>';
        echo '<p><strong>Total Price: </strong>RM' . number_format($total_price, 2) . '</p>';
        echo '<p><strong>Payment Method: </strong>' . htmlspecialchars($payment_method) . '</p>';
        echo '<p><strong>Booking Status: </strong>Confirmed</p>';
        echo '</div>';
        

    } catch (Exception $e) {
        // Rollback the transaction if any error occurs
        $conn->rollback();
        echo 'Error: ' . $e->getMessage();
    }
    ?>

            </div>
        </div>

        <!-- Footer Start -->
        <?php include 'footer.php'; ?>
        <!-- Footer End -->

        <!-- JavaScript Libraries -->
        <?php include 'js_lib.php'; ?>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>

    </div>
</body>

</html>
