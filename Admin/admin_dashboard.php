<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../db_connection.php'; // Include database connection

// Fetch bookings and sort by status and check-in date
$bookings_query = "
    SELECT b.booking_id, u.full_name, u.email, u.phone, r.room_name, b.adults, b.children, b.check_in_date, b.check_out_date, b.booking_status
    FROM bookings b
    JOIN users u ON b.user_id = u.user_id
    JOIN rooms r ON b.room_id = r.room_id
    ORDER BY
        FIELD(b.booking_status, 'pending', 'confirmed', 'cancelled'), -- Correct sorting order for status
        b.check_in_date DESC
";
$bookings_result = mysqli_query($conn, $bookings_query);
if (!$bookings_result) {
    die("Error fetching bookings: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <!-- Header Start -->
    <?php include 'admin_header.php'; ?>
    <!-- Header End -->

    <div class="container">
        <h1>Admin Dashboard</h1>

        <!-- Bookings Section -->
        <div>
            <h2>User Bookings</h2>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Room Name</th>
                        <th>Adults</th>
                        <th>Children</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $counter = 1; ?>
                    <?php while ($booking = mysqli_fetch_assoc($bookings_result)) { ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo htmlspecialchars($booking['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['email']); ?></td>
                            <td><?php echo htmlspecialchars($booking['phone']); ?></td>
                            <td><?php echo htmlspecialchars($booking['room_name']); ?></td>
                            <td><?php echo $booking['adults']; ?></td>
                            <td><?php echo $booking['children']; ?></td>
                            <td><?php echo $booking['check_in_date']; ?></td>
                            <td><?php echo $booking['check_out_date']; ?></td>
                            <td>
                                <?php
                                    $status = $booking['booking_status'];
                                    if ($status == 'pending') {
                                        echo '<span class="status-pending">Pending</span>';
                                    } elseif ($status == 'confirmed') {
                                        echo '<span class="status-confirmed">Confirmed</span>';
                                    } elseif ($status == 'cancelled') {
                                        echo '<span class="status-cancelled">Cancelled</span>';
                                    }
                                ?>
                            </td>
                            <td>
                                <?php if ($status != 'cancelled') { ?>
                                    <a href="cancel_booking.php?id=<?php echo $booking['booking_id']; ?>" class="delete" onclick="return confirm('Are you sure you want to cancel this booking?');">Cancel</a>
                                    <a href="reschedule_booking.php?id=<?php echo $booking['booking_id']; ?>" class="edit">Reschedule</a>
                                <?php } else { ?>
                                    <!-- No action buttons if booking is cancelled -->
                                    
                                <?php } ?>
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
