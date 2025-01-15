<?php
session_start();
include 'db_connection.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user's bookings sorted by booking status (confirmed first) and latest date
$sql = "SELECT b.booking_id, b.check_in_date, b.check_out_date, b.adults, b.children, 
               b.payment_method, b.booking_status, r.room_name, r.room_description
        FROM bookings b
        JOIN rooms r ON b.room_id = r.room_id
        WHERE b.user_id = ?
        ORDER BY 
            CASE WHEN b.booking_status = 'confirmed' THEN 0 ELSE 1 END, 
            b.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
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
                <h2>Your Booking History</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Room Name</th>
                            <th>Details</th>
                            <th>Check-in Date</th>
                            <th>Check-out Date</th>
                            <th>Adults</th>
                            <th>Children</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Cancel Booking</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $counter++ . '</td>';
                            echo '<td>' . htmlspecialchars($row['room_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['room_description']) . '</td>';
                            echo '<td>' . date('d/m/Y', strtotime($row['check_in_date'])) . '</td>';
                            echo '<td>' . date('d/m/Y', strtotime($row['check_out_date'])) . '</td>';
                            echo '<td>' . htmlspecialchars($row['adults']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['children']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['payment_method']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['booking_status']) . '</td>';
                            
                            // Show cancel button for confirmed bookings only
                            if ($row['booking_status'] == 'confirmed') {
                                echo '<td><a href="cancel_booking.php?booking_id=' . $row['booking_id'] . '" class="btn btn-danger">Cancel</a></td>';
                            } else {
                                echo '<td>-</td>';
                            }
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
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
